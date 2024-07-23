<?php

namespace app\controllers;

use Yii;
use app\models\Transaksi;
use app\models\TransaksiDetail;
use app\models\Produk;
use app\models\TransaksiSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * TransaksiController implements the CRUD actions for Transaksi model.
 */
class TransaksiController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Transaksi::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($idTransaksi)
    {
        return $this->render('view', [
            'model' => $this->findModel($idTransaksi),
        ]);
    }

    public function actionCreate()
    {
        $model = new Transaksi();
        $details = [new TransaksiDetail()];

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->tanggal = date('Y-m-d H:i:s');
                $model->kode_transaksi = $this->generateKodeTransaksi(); // Generate kode transaksi
                // $model->uang_diberikan = Yii::$app->request->post('uang_diberikan');
                $model->uang_kembalian = $model->uang_diberikan - $model->total;

                if ($model->save()) {
                    $detailsData = json_decode(Yii::$app->request->post('TransactionDetailsJson'), true);
                    foreach ($detailsData as $detailData) {
                        $detail = new TransaksiDetail();
                        $detail->idTransaksi = $model->idTransaksi;
                        $detail->idProduk = $detailData['idProduk'];
                        $detail->jumlah = $detailData['jumlah'];
                        $detail->harga = $detailData['harga'];
                        if (!$detail->save()) {
                            throw new \Exception('Failed to save transaction detail.');
                        }
                        $produk = Produk::findOne($detail->idProduk);
                        if ($produk->stok < $detail->jumlah) {
                            throw new \Exception('Insufficient stock for product ID: ' . $detail->idProduk);
                        }
                        $produk->stok -= $detail->jumlah;
                        if (!$produk->save()) {
                            throw new \Exception('Failed to update product stock.');
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'idTransaksi' => $model->idTransaksi]);
                } else {
                    foreach ($model->errors as $error){
                        return $error[0];
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    public function actionUpdate($idTransaksi)
    {
        $model = $this->findModel($idTransaksi);
        $details = $model->transaksiDetails;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->uang_diberikan = Yii::$app->request->post('uang_diberikan');
                $model->uang_kembalian = $model->uang_diberikan - $model->total;

                if ($model->save()) {
                    TransaksiDetail::deleteAll(['idTransaksi' => $model->idTransaksi]);
                    $detailsData = json_decode(Yii::$app->request->post('TransactionDetailsJson'), true);
                    foreach ($detailsData as $detailData) {
                        $detail = new TransaksiDetail();
                        $detail->idTransaksi = $model->idTransaksi;
                        $detail->idProduk = $detailData['idProduk'];
                        $detail->jumlah = $detailData['jumlah'];
                        $detail->harga = $detailData['harga'];
                        if (!$detail->save()) {
                            throw new \Exception('Failed to save transaction detail.');
                        }
                        $produk = Produk::findOne($detail->idProduk);
                        if ($produk->stok < $detail->jumlah) {
                            throw new \Exception('Insufficient stock for product ID: ' . $detail->idProduk);
                        }
                        $produk->stok -= $detail->jumlah;
                        if (!$produk->save()) {
                            throw new \Exception('Failed to update product stock.');
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'idTransaksi' => $model->idTransaksi]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    public function actionDelete($idTransaksi)
    {
        $this->findModel($idTransaksi)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($idTransaksi)
    {
        if (($model = Transaksi::findOne(['idTransaksi' => $idTransaksi])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetProdukPrice($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $produk = Produk::findOne($id);
        if ($produk) {
            return ['price' => $produk->harga];
        }
        return ['price' => 0];
    }

    private function generateKodeTransaksi()
    {
        // Generate a unique kode_transaksi, e.g., "TRX-YYYYMMDD-XXX"
        $prefix = 'TRX-' . date('Ymd') . '-';
        $lastTransaksi = Transaksi::find()
            ->where(['like', 'kode_transaksi', $prefix])
            ->orderBy(['idTransaksi' => SORT_DESC])
            ->one();
        $lastNumber = $lastTransaksi ? intval(substr($lastTransaksi->kode_transaksi, -3)) : 0;
        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . $nextNumber;
    }

    public function actionPrintReceipt($idTransaksi)
{
    $model = $this->findModel($idTransaksi);
    $details = TransaksiDetail::find()->where(['idTransaksi' => $idTransaksi])->all();

    return $this->render('struk', [
        'model' => $model,
        'products' => $details,
    ]);
}


    public function actionReport()
    {
        $searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'months' => $months,
        ]);
    }

    public function actionExportPdf($month = null)
    {
        $searchModel = new TransaksiSearch();
        $params = Yii::$app->request->queryParams;
        if ($month !== null) {
            $params['TransactionsSearch']['month'] = $month;
        }
        $dataProvider = $searchModel->search($params);
        $models = $dataProvider->getModels();

        $pdf = new Mpdf();
        $pdfContent = $this->renderPartial('_reportPdf', ['models' => $models]);
        $pdf->WriteHTML($pdfContent);
        $pdf->Output('TransactionReport.pdf', 'D');
        exit;
    }

    public function actionExportExcel($month = null)
    {
        $searchModel = new TransaksiSearch();
        $params = Yii::$app->request->queryParams;
        if ($month !== null) {
            $params['TransaksiSearch']['month'] = $month;
        }
        $dataProvider = $searchModel->search($params);
        $models = $dataProvider->getModels();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('YourName')
            ->setLastModifiedBy('YourName')
            ->setTitle('Transaction Report')
            ->setSubject('Transaction Report')
            ->setDescription('Generated report for transactions.')
            ->setKeywords('transactions report')
            ->setCategory('Report');

        // Add header row
        $sheet->setCellValue('A1', 'Transaction ID')
            ->setCellValue('B1', 'Total')
            ->setCellValue('C1', 'Transaction Date');

        // Add data rows
        $row = 2;
        foreach ($models as $model) {
            $sheet->setCellValue('A' . $row, $model->idTransaksi)
                ->setCellValue('B' . $row, $model->total)
                ->setCellValue('C' . $row, $model->tanggal);
            $row++;
        }

        // Set auto column width
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Write to Excel file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'TransactionReport_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        // Send the file to the browser as a download
        return Yii::$app->response->sendFile($tempFile, $fileName);
    }
}

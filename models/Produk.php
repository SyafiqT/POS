<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Produk extends ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return '{{%produk}}';
    }

    public function rules()
    {
        return [
            [['nama', 'harga', 'idKategori', 'stok'], 'required'],
            [['harga'], 'number'],
            [['idKategori', 'stok'], 'integer'],
            [['nama', 'kode_barang', 'gambar_barang'], 'string', 'max' => 255],
            [['kode_barang'], 'unique'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idProduk' => 'ID Produk',
            'nama' => 'Nama',
            'harga' => 'Harga',
            'idKategori' => 'Kategori',
            'stok' => 'Stok',
            'kode_barang' => 'Kode Barang',
            'gambar_barang' => 'Gambar Barang',
            'imageFile' => 'Upload Gambar',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateKodeBarang();
            }
            if ($this->imageFile) {
                $this->gambar_barang = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            }
            return true;
        }
        return false;
    }

    // public function generateKodeBarang()
    // {
    //     $category = $this->kategori;
    //     $categoryAbbreviation = strtoupper(substr($category->NamaKategori, 0, 2));
    //     $nextNumber = self::find()
    //         ->where(['idKategori' => $this->idKategori])
    //         ->andWhere(['like', 'kode_barang', $categoryAbbreviation . '-%'])
    //         ->count() + 1;

    //     $number = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    //     $this->kode_barang = $categoryAbbreviation . '-' . $number;
    // }

    public function generateKodeBarang()
{
    $connection = Yii::$app->db;
    $transaction = $connection->beginTransaction();

    try {
        // Fetch the kategori model to get kode_kategori
        $kategori = $this->kategori;
        if (!$kategori) {
            throw new \Exception('Kategori not found.');
        }
        $categoryAbbreviation = strtoupper(substr($kategori->kode_kategori, 0, 2)); // Use kode_kategori

        // Lock the produk table
        $connection->createCommand('LOCK TABLES produk WRITE')->execute();

        // Find the last kode_barang in the same category
        $lastKodeBarang = self::find()
            ->where(['idKategori' => $this->idKategori])
            ->andWhere(['like', 'kode_barang', $categoryAbbreviation . '-%'])
            ->orderBy(['kode_barang' => SORT_DESC])
            ->one();

        // Determine the next number for the product code
        if ($lastKodeBarang) {
            $lastNumber = (int) substr($lastKodeBarang->kode_barang, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Generate and check for uniqueness
        do {
            $number = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $generatedKodeBarang = $categoryAbbreviation . '-' . $number;
            $isKodeBarangExists = self::find()->where(['kode_barang' => $generatedKodeBarang])->exists();
            if ($isKodeBarangExists) {
                $nextNumber++;
            }
        } while ($isKodeBarangExists);

        $this->kode_barang = $generatedKodeBarang;

        // Commit transaction and unlock table
        $transaction->commit();
        $connection->createCommand('UNLOCK TABLES')->execute();

        // Log the generated kode_barang
        Yii::info('Generated Kode Barang: ' . $this->kode_barang, __METHOD__);

    } catch (\Exception $e) {
        $transaction->rollBack();
        $connection->createCommand('UNLOCK TABLES')->execute();
        throw $e;
    } catch (\Throwable $e) {
        $transaction->rollBack();
        $connection->createCommand('UNLOCK TABLES')->execute();
        throw $e;
    }
}



    public function getKategori()
    {
        return $this->hasOne(Kategori::class, ['idKategori' => 'idKategori']);
    }

    public function upload()
    {
        if ($this->validate() && $this->imageFile) {
            $filePath = Yii::getAlias('@webroot/uploads/') . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            return $this->imageFile->saveAs($filePath);
        }
        return false;
    }
}

<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

trait MasterTrait
{
    protected function idCreate($table, $field)
    {
        $id = IdGenerator::generate(['table' => $table, 'field' => $field, 'length' => 10, 'prefix' => date('ymd'), 'reset_on_prefix_change' => true]);
        return $id;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id_barang = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    protected function idSupplier()
    {
        // Ambil ID terbaru dari tabel
        $lastId = IdGenerator::generate([
            'table' => 'm_supplier',
            'field' => 'kode_supplier',
            'length' => 5,
            'prefix' => '0',
            'reset_on_prefix_change' => true
        ]);

        // Konversi ID ke integer, tambah 1, dan format dengan padding nol
        $newId = (int)$lastId + 1;
        $paddedId = str_pad($newId, 5, '0', STR_PAD_LEFT);

        return $paddedId;
    }

    protected function kodeBarcode()
    {
        // Buat kode negara (2-3 digit), misalnya "40" untuk Jerman atau "69" untuk China.
        // Di sini kita gunakan placeholder "00" untuk contoh.
        $countryCode = '62';

        // Buat kode pabrikan atau perusahaan (4-5 digit), kita gunakan placeholder "1234".
        $manufacturerCode = '1234';

        // Buat kode produk acak (5 digit) yang unik.
        $productCode = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);

        // Gabungkan bagian-bagian kode untuk mendapatkan 12 digit pertama.
        $barcode = $countryCode . $manufacturerCode . $productCode;

        // Hitung digit pemeriksa
        $checkDigit = $this->calculateCheckDigit($barcode);

        // Gabungkan untuk mendapatkan kode EAN-13 penuh.
        return $barcode . $checkDigit;
    }

    /**
     * Calculate the check digit for EAN-13 barcode.
     *
     * @param string $barcode
     * @return string
     */
    protected function calculateCheckDigit($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < strlen($barcode); $i++) {
            $sum += $barcode[$i] * ($i % 2 == 0 ? 1 : 3);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    protected $primaryKey = 'id_barang';

    public function hasKategori()
    {
        return $this->hasOne(KategoriBarang::class, 'id_kategori_barang', 'id_kategori_barang');
    }

    public function hasSatuan()
    {
        return $this->hasOne(SatuanBarang::class, 'id_satuan_barang', 'id_satuan_barang');
    }
}

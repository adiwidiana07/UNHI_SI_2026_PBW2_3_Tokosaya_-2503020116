<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
      protected $table = 'kecamatans';

    public $incrementing = false;

    protected $fillable = ['id', 'kota_id', 'nama', 'kode_pos'];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

}

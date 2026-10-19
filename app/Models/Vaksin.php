<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaksin extends Model
{
    protected $fillable = ['nama_vaksin', 'is_aktif'];

    public function pendaftarans()
    {
        return $this->belongsToMany(Pendaftaran::class, 'pendaftaran_vaksin');
    }
}
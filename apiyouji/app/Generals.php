<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generals extends Model
{
    use SoftDeletes;
    protected $table = 'tb_general';
    protected $fillable = ['kode', 'kode_tipe', 'keterangan', 'gambar'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}

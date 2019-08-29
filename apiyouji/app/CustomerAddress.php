<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Customer;

class CustomerAddress extends Model
{
    use SoftDeletes;
    protected $table = 'tb_alamat_pelanggan';

    protected $fillable = ['id', 'id_customer', 'keterangan', 'kode_provinsi', 'kode_kota', 'kode_kecamatan', 'kodepos', 'rt', 'rw', 'alamat', 'utama'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function Customer()
    {
        return $this->belongsTo('App\Customer');
    }


}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\CustomerAddress;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'tb_customer';
    protected $appends = ['address'];
    protected $fillable = ['name', 'email', 'phone', 'kodepos'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function CustomerAddress()
    {
        return $this->hasMany('App\CustomerAddress');
    }

    public function SalesOrders()
    {
        return $this->hasMany('App\SalesOrders');
    }

    public function getAddressAttribute()
    {
        $address = CustomerAddress::where('id_customer', $this->id)->get();
        return $address;
    }

}
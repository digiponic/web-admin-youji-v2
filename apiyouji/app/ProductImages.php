<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImages extends Model
{
    use SoftDeletes;
    protected $table = 'mst_product_images';
    protected $filable = ['product_id','name','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    protected $table = 'coupons';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }

}
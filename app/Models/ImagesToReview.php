<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesToReview extends Model {

    /**
     * @var array
     */
    protected $fillable = [
        'review_id',
        'image'
    ];


}

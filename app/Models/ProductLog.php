<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model {

    /**
     * @var array
     */
    protected $fillable = [
        'checked',
        'words'
    ];
}

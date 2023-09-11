<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Attribute
 * @property string                             name
 * @property integer                            id
 * @property \App\Models\ProductAttribute       attributes
 * @package App\Models
 */
class Attribute extends Model {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products_attributes()
    {
        return $this->hasMany(
            'App\Models\ProductAttribute',
            'reference_id',
            'id'
        );
    }

}

<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductAttribute
 * @property integer                            upi_id
 * @property integer                            reference_id
 * @property string                             attribute_value_name
 * @property integer                            id
 * @property \App\Models\CatalogProduct         product
 * @property \App\Models\Attribute              attribute_name
 * @package App\Models
 */
class ProductAttribute extends Model {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute_name()
    {
        return $this->belongsTo(
            'App\Models\Attribute',
            'reference_id',
            'id'

        );

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(
            'App\Models\CatalogProduct',
            'upi_id',
            'upi_id'
        );

    }


}

<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class WaitingProductUser extends Model {

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'notified',
        'product_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'product_id');
    }

    /**
     * @return array
     */
    public static function processCheckNotifiedUserProducts()
    {
        $result = [];

        // search all waitings that not notified and product not hidden
        $waitings = self::where('notified',false)
            ->with('product')
            ->whereHas('product', function ($query){
                $query->where('hidden',false);
            })
            ->get();

        foreach($waitings as $waiting){

            //Send mail to user
            Mail::send('emails.waitingProduct', [
                'name'  	=> $waiting->name,
                'product' 	=> $waiting->product
            ], function($message) use ($waiting)
            {
                $message->to($waiting->email)->subject('Уведомление о поступлении товара на allfor2.com');
            });

            // set notified
            $waiting->notified = true;
            $waiting->save();

            $result[] = $waiting->product->id;
        }

        return $result;
    }
}

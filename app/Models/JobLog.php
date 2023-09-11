<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLog extends Model {


    /**
     * @var array
     */
    protected $fillable = [
        'job',
        'description',
        'amount'
    ];

    /**
     * @param $name
     * @param $order_id
     * @param $status
     * @param null $amount
     * @param bool $paid
     * @param bool $job
     * @return static
     */
    public static function writeJob($name, $order_id, $status, $amount = null, $paid = false, $job = false)
    {
        if($job){
            $newRow = self::create(
                [
                    'job'         => $name,
                    'description' => $status,
                    'amount'      => $amount
                ]
            );
        }else{
            $amount ? $amount_text = ' Amount = ' . $amount : $amount_text = '';
            $description = 'Payment order#' . $order_id . ' ' . $status . $amount_text;
            $paid ? $amount_write = $amount : $amount_write = 0;

            $newRow = self::create(
                [
                    'job' => $name,
                    'description' => $description,
                    'amount' => $amount_write
                ]
            );
        }

        return $newRow;
    }

}

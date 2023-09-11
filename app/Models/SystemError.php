<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class SystemError
 * @property integer                             id
 * @property integer                             user_id
 * @property string                              error
 * @property string                              ip_address
 * @property string                              stack_trace
 * @property \App\Models\User                    user
 * @package App\Models
 */
class SystemError extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'error',
        'ip_address',
        'stack_trace'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * @param $name
     * @param $description
     * @return static
     */
    public static function write($name, $description)
    {
        if (is_array($description)) {
            $description = implode('\n', $description);
        }

        $newRow = self::create(
            [
                'error'         => $name,
                'stack_trace'   => $description,
                'user_id'       => 0,
                'ip_address'    => inet_pton('0.0.0.0')

            ]
        );

        return $newRow;
    }


}

<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Review
 * @property integer                             id
 * @property Carbon                              answered_at
 * @property integer                             user_id
 * @property integer                             product_id
 * @property string                              text
 * @property string                              quest
 * @property boolean                             active
 * @property boolean                             new
 * @property boolean                             user_unread
 * @property integer                             rating
 * @property string                              city
 * @property string                              type
 * @property string                              answer
 * @property \App\Models\CatalogProduct          product
 * @property \App\Models\User                    user
 * @package App\Models
 */
class Review extends Model {

    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'text',
        'active',
        'new',
        'rating',
        'quest',
        'city',
        'email',
        'type',
        'answer',
        'user_unread',
        'answered_at'
    ];


    /**
     * @param $query
     * @return mixed
     */
    public function scopeActiveProductWithImage($query)
    {
        return $query->with(
            ['product' => function($query){
                $query->with(['image' => function ($query) {
                    $query->orderBy('id', 'asc');
                }]);
            }])->whereHas('product', function ($query) {
            $query->active();
        });
    }

    /**
     * Returns reviews that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUser($query, $userId)
    {
        return $query->with(
            ['user' => function ($query) {
                $query->select(
                    'id',
                    'name',
                    'email',
                    'image'
                );
            }
            ]
        )->whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        });
    }

    /**
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithUser($query)
    {
        return $query->with(
            ['user' => function ($query) {
                $query->select(
                    'id',
                    'name',
                    'email',
                    'image',
                    'contacts'
                );
            }
            ]
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAllowProduct($query)
    {
        return $query->with(
            ['product' => function ($query) {
                $query->select(
                    'id',
                    'name_ru',
                    'slug',
                    'upi_id'
                );
            }
            ]
        );
    }
    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active','=',true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTypeReview($query)
    {
        return $query->where('type','=','review');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTypeQA($query)
    {
        return $query->where('type','=','qa');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithAnswer($query)
    {
        return $query->whereNotNull('answer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(
            'App\Models\CatalogProduct',
            'product_id'
        );
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            'App\Models\User',
            'user_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(
            'App\Models\ImagesToReview',
            'review_id'
        );
    }

    /**
     *
     */
    public function markAsReadSupport()
    {
        $this->new = false;
        $this->save();
    }

    /**
     *
     */
    public function markAsAnsweredSupport()
    {
        $this->answered_at = new Carbon;
        $this->save();
    }

    /**
     *
     */
    public function markAsReadUser()
    {
        $this->user_unread = false;
        $this->save();
    }

    /**
     *
     */
    public function markAsUnReadForUser()
    {
        $this->user_unread = true;
        $this->save();
    }
}

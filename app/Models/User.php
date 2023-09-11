<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use DB;
/**
 * Class User
 * @property integer                             id
 * @property string                              name
 * @property string                              email
 * @property string						         password
 * @property boolean                             active
 * @property integer                             social_id
 * @property string                              image
 * @property string					             contacts
 * @property boolean                             isActive
 * @property string                              activationCode
 * @property string                              social_url
 * @property string								 product_pages
 * @property string                              last_name
 * @property \App\Models\Order			         order
 * @property \App\Models\Review			         review
 * @property \App\Models\ProductLikes	         like
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'active',
		'social_id',
		'image',
		'isActive',
		'activationCode',
		'social_url',
		'product_pages',
        'last_name'
	];

	/**
	 * @var array
	 */
	public $contactFields = [
		'd_user_region',
		'd_user_city',
		'd_user_address',
		'd_user_index',
		'd_user_phone'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function order()
	{
		return $this->hasMany(
			'App\Models\Order',
			'user_id',
			'id'
		);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart()
    {
        return $this->hasMany(
            'App\Models\Cart',
            'user_id',
            'id'
        );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function review()
	{
		return $this->hasMany(
			'App\Models\Review',
			'user_id',
			'id'
		);
	}
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function like()
	{
		return $this->hasMany(
			'App\Models\ProductLikes',
			'user_id',
			'id'
		);
	}

	/**
	 * Message relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages()
	{
		return $this->hasMany('App\Models\Message');
	}

	/**
	 * Thread relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function threads()
	{
		return $this->belongsToMany('App\Models\Thread', 'participants');
	}

	/**
	 * Returns the new messages count for user
	 *
	 * @return int
	 */
	public function newMessagesCount()
	{
		return count($this->threadsWithNewMessages());
	}

	/**
	 * Returns the new messages count for user
	 *
	 * @return int
	 */
	public function newAnswersCount()
	{
		return $this->review()->typeQA()
			->where('user_unread', true)->count();
	}

	/**
	 * Returns the new messages count for user
	 *
	 * @return int
	 */
	public function newQuestionsCount()
	{
		return Review::typeQA()
			->where('new', true)->count();
	}

	/**
	 * Returns all threads with new messages
	 *
	 * @return array
	 */
	public function threadsWithNewMessages()
	{
		$threadsWithNewMessages = [];
		$participants = Participant::where('user_id', $this->id)->lists('last_read', 'thread_id');

		if ($participants) {
			$threads = Thread::whereIn('id', array_keys($participants))->get();

			foreach ($threads as $thread) {
				if ($thread->updated_at > $participants[$thread->id]) {
					$threadsWithNewMessages[] = $thread->id;
				}
			}
		}

		return $threadsWithNewMessages;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function getEmailAttribute($value)
	{
		$v = Validator::make(
			['email' => $value],
			['email' => [
				'required',
				'email'
			]
			]);

		if ($v->fails()) {
			return '';
		}else{
			return $value;
		}
	}
	/**
	 * @param $value
	 * @return string
	 */

	public function getCreatedAtAttribute($value)
	{

		return Date::parse($value)->format();
	}

	/**
	 * @param $value
	 * @return string
	 */
	public function getUpdatedAtAttribute($value)
	{

		return Date::parse($value)->format();
	}

	/**
	 * @param $activationCode
	 * @return bool
     */
	public function activateUser($activationCode) {
		// Если пользователь уже активирован, не будем делать никаких
		// проверок и вернем false
		if ($this->isActive) {
			return false;
		}

		// Если коды не совпадают, то также ввернем false
		if ($activationCode != $this->activationCode) {
			return false;
		}

		// Обнулим код, изменим флаг isActive и сохраним
		$this->activationCode = '';
		$this->isActive = true;
		$this->save();

		return true;
	}

	/**
	 * @return mixed
     */
	public static function getCities()
	{
		// get cities and regions
		return DB::table('d_city')
			->leftJoin('d_region', function ($join) {
				$join->on('d_region.id', '=', 'd_city.region_id');
			})
			->select('d_city.*','d_region.name as region_name')
			->orderBy('name')
			->get();
	}

	/**
	 * @param $product_id
     */
	public static function saveProductPageToUser($product_id)
	{
		if(Auth::check()){
			$user = self::find(Auth::user()->id);
			$product_pages = json_decode($user->product_pages);
			if(!is_array($product_pages)){
				$product_pages = [];
				$product_pages[] = $product_id;
				$user->product_pages = json_encode($product_pages);
				$user->save();
			}elseif(!in_array($product_id, $product_pages)){
				$product_pages[] = $product_id;
				$user->product_pages = json_encode($product_pages);
				$user->save();
			}
		}
	}

	/**
	 * @param $id
	 * @return \Illuminate\View\View
	 */
	public static function getUserWithData($id)
	{
		$user = self::where('id', $id)
			->with(['order' => function($query){
				$query->with(
					['order_item' => function($query){
						$query->activeProduct();
					}])->notDeleted();
			}])
            ->with(
                [
                    'cart' => function ($query) {
                        $query->with(
                            [
                                'cart_products' => function ($query) {
                                    $query->activeProduct();
                                }
                            ]
                        );
                    }
                ]
            )
			->with(['review'=>function($query) {
				$query->activeProductWithImage();
			}])
			->with(['like' => function($query){
				$query->activeProduct();
			}])
			->first();

		$order_not_paid = new Collection();
		$count_delivered = 0;
		$count_paid = 0;

		foreach($user->order as $orders){
			$order_items = collect($orders->order_item);
			if($orders->order_status == Order::STATUS_WAITING) {
				$order_temp = $order_items->filter(function($order)
				{
					return $order->deletion_mark_user != 1;
				});
				$order_not_paid = $order_not_paid->merge($order_temp);
			}
			elseif($orders->order_status == Order::STATUS_DELIVERED){
				$count_delivered += $order_items->sum('product_quantity');
			}
			elseif($orders->order_status == Order::STATUS_PAID){
				$count_paid += $order_items->sum('product_quantity');
			}
		}

		$cart_unpaid = $user->cart->filter(
            function ($cart) {
                return ($cart->deletion_mark == false && $cart->posted == false);
            }
        )
            ->sum(
                function (
                    $cart
                ) {
                    return $cart->cart_products->sum('quantity');
                }
            );

		$user->delivered = $count_delivered;
		$user->paid = $count_paid;
		$user->not_paid = count($order_not_paid->lists('product_quantity', 'product_id')) + $cart_unpaid;
		// get viewed products without duplicate products
		$product_pages = json_decode($user->product_pages);
		if(is_array($product_pages)){
			$all_products = CatalogProduct::getProductWithAllRel();
			$all_products = $all_products->whereIn('id', $product_pages)
				->get();

			$block_upi = [];
			$products = new Collection();

			foreach($all_products as $key => $product) {
				if(!in_array($product->upi_id, $block_upi)) {
					$products[$key] = $product;
					if (count($product->attribute)) {
						foreach ($product->attribute as $attribute) {
							if (count($attribute->attribute_name->products_attributes)) {
								$block_upi = array_merge(
									$block_upi,
									collect($attribute->attribute_name->products_attributes)->lists('upi_id')
								);
							}
						}
					}
				}
			}
			$user->product_viewed_count = count($products);
			$user->product_viewed = $products;
		}

		return $user;
	}


	/**
	 * @param $dataArray
	 * @return array
	 */
	public static function getUserContacts($dataArray)
	{
		if (!is_array($dataArray)) {
			$dataArray = (array)$dataArray;
		}

		foreach ($dataArray as $key => $row) {
			$dataArray[$key] = $row;
		}

		return array_intersect_key(
			(array)$dataArray,
			array_flip((new self)->contactFields)
		);
	}

	/**
	 * @param $order
	 * @return bool
     */
	public static function checkUserOrCreateNew($order)
	{
		if($order->user_id){

			return false;
		}
		$order->contacts = unserialize($order->contacts);
		$order->contacts = (array)$order->contacts;

		if(isset($order->contacts['d_user_email']) && $order->contacts['d_user_email']){
			$userExist = self::where('email', $order->contacts['d_user_email'])->first();
			if(!$userExist) {
				$user = new self;
				$user->name = isset($order->contacts['d_user_name']) ? $order->contacts['d_user_name'] : 'New user';
                $user->last_name = isset($order->contacts['d_user_last_name']) ? $order->contacts['d_user_last_name'] : null;
				$user->email = $order->contacts['d_user_email'];
				$password = str_random(8);
				$user->password = bcrypt($password);
				$user->isActive = true;
				$user->save();

				//Send mail to customer if email isset
				Mail::send('emails.createUser', [
					'name'  	=> $user->getFullName(),
					'email'		=> $user->email,
					'password' 	=> $password
				], function($message) use ($user)
				{
					$message->to($user->email)->subject('Регистрация на сайте allfor2.com');
				});

				return $user->id;
			}

			return $userExist->id;
		}

		return false;
	}

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' '. $this->last_name;
    }
}

<?php namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Session;


/**
 * Class Cart
 * @package App\Services
 */
class Cart
{
	/**
	 * @var
	 */
	protected $_instance;
	private $collection;

	/**
	 *
	 */
	public function __construct()
	{

		if (!$this->_instance) {
			$this->init();
		}
	}

	/**
	 *
	 */
	public function init()
	{
		$this->collection = new Collection(DB::table('carts')
			->where('posted', false)
			->where('deletion_mark', false)
			->get()
		);
	}


	/**
	 * @param $id
	 * @return mixed
	 */
	public function checkPostedAndDeleted($id)
	{
		$cart = $this->collection->first(function($key, $cart) use ($id)
		{
			return $cart->id == $id;
		});

		if(!$cart) {
			// else forget session cart data
			Session::forget('cart_id');
			Session::forget('cart_products');

			return false;
		}
		return true;
	}
}
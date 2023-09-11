<?php namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


/**
 * Class Order
 * @package App\Services
 */
class Order
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
		$this->collection = new Collection(DB::table('orders')
			->orderBy('created_at')
			->get()
		);
	}


	/**
	 * @return mixed
	 */
	public function getNewCount()
	{
		return count($this->collection->filter(function($order)
		{
			if ($order->new == true && $order->deletion_mark == false){
				return $order;
			}
		}));
	}
}
<?php namespace App\Services;

use App\Models\CallMe;


/**
 * Class CallMeService
 * @package App\Services
 */
class CallMeService
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
		$this->collection = CallMe::where('completed', false)->get();
	}


	/**
	 * @return mixed
	 */
	public function getCountNotCompleted()
	{

		return count($this->collection);
	}
}
<?php namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


/**
 * Class Review
 * @package App\Services
 */
class Review
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
		$this->collection = new Collection(DB::table('reviews')
			->orderBy('created_at')
			->get()
		);
	}


	/**
	 * @return mixed
	 */
	public function getNewReviewsCount()
	{
		return count($this->collection->filter(function($review)
		{
			if (($review->new == true) && ($review->type == 'review')) {
				return $review;
			}
		}));
	}

	/**
	 * @return mixed
	 */
	public function getNewQaCount()
	{
		return count($this->collection->filter(function($review)
		{
			if (($review->new == true) && ($review->type == 'qa')) {
				return $review;
			}
		}));
	}
}
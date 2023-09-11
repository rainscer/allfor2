<?php namespace App\Services;

use App\Models\CatalogCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


/**
 * Class Catalog
 * @package App\Services
 */
class Catalog
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
		$minutes = 1;

		$this->collection = CatalogCategory::leftJoin('catalog_categories as parent', function($join)
				{
					$join->on('parent.left_key', '<', 'catalog_categories.left_key')
						->on('parent.right_key', '>', 'catalog_categories.right_key')
						->on('parent.level', '=', DB::raw('catalog_categories.level - 1'));
				})
				->with('products')
				->select(
					'catalog_categories.*',
					'parent.id as parent_id'
				)
				->orderBy('catalog_categories.left_key')
				->get();

		/*
		$this->collection = Cache::remember('catalog', $minutes, function() {

			return new Collection(DB::table('catalog_categories')
				->leftJoin('catalog_categories as parent', function($join)
			{
				$join->on('parent.left_key', '<', 'catalog_categories.left_key')
					 ->on('parent.right_key', '>', 'catalog_categories.right_key')
					 ->on('parent.level', '=', DB::raw('catalog_categories.level - 1'));
			})
			->select('catalog_categories.*', 'parent.id as parent_id')
			->orderBy('catalog_categories.left_key')
			->get()
		);
		});*/
	}


	/**
	 * @param $slug
	 * @return mixed
	 */
	public function getAllChildren($slug)
	{
		$category_temp_child = $this->getCategoryBySlug($slug);
		if(!$category_temp_child){

			return false;
		}

		return $this->collection->filter(function($category) use ($category_temp_child)
		{
			if (($category->left_key >= $category_temp_child->left_key) &&
				($category->right_key <= $category_temp_child->right_key)){
				return $category;
			}
		})->lists('id');
	}

	/**
	 * @param $slug
	 * @return mixed
	 */
	public function getAllParents($slug)
	{
		$category_temp_parent = $this->getCategoryBySlug($slug);
		if(!$category_temp_parent){

			return false;
		}

		return $this->collection->filter(function($category) use($category_temp_parent)
		{
			if (($category->left_key <= $category_temp_parent->left_key) &&
				($category->right_key >= $category_temp_parent->right_key)){

				return $category;
			}
		});
	}

	/**
	 * @param $slug
	 * @return mixed
	 */
	public function getSubMenu($slug)
	{
		$collection = $this->collection;
		$category_temp_child = $this->getCategoryBySlug($slug);
		if(!$category_temp_child){

			return false;
		}

		$category_childrens = $collection->filter(function($category) use($category_temp_child)
		{
			if (($category->level == $category_temp_child->level + 1) &&
				($category->left_key >= $category_temp_child->left_key) &&
				($category->right_key <= $category_temp_child->right_key)){

				return $category;
			}
		});

		foreach ($category_childrens as $item)
		{
			$item->subcategory = $collection->filter(function($category) use ($item)
			{
				return $category->parent_id == $item->id;
			});
		}

		return $category_childrens;
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getSlugById($id)
	{
		return $this->collection->first(function($key, $category) use ($id)
		{
			return $category->id == $id;
		});
	}

	/**
	 * @param $slug
	 * @return mixed
	 */
	public function getCategoryBySlug($slug)
	{

		return $this->collection->first(function($key, $category) use ($slug)
		{
			return $category->slug == $slug;
		});
	}

	/**
	 * @return array
	 */
	public function getMenuItems()
	{

		$menu = new Collection();
		$collection = $this->collection;

		$menu->level1 = $collection->filter(function($category)
		{
			if ($category->level == 0) {
				return $category;
			}
		});
		$menu->level2 = $collection->filter(function($category)
		{
			if ($category->level == 1) {
				return $category;
			}
		});

		foreach ($menu->level2 as $item)
		{
			$item->level3 = $collection->filter(function($category) use ($item)
			{
				if (($category->parent_id == $item->id) && ($category->level == 2)) {
					return $category;
				}
			});
		}

		$menu->level2 = $menu->level2->groupBy('parent_id');
		$menu->level1 = $menu->level1->sortBy('sort');
		return $menu;
	}

}
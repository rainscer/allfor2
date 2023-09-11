<?php namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Cache;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.catalogCategory.list');
    }


    /**
     * @return string
     */
    public function getModelClass()
    {
        return CatalogCategory::class;
    }

    /**
     * @return mixed
     */
    public function getTree()
    {
        return response()->json(CatalogCategory::getTree());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function moveNode(Request $request)
    {
        $node = CatalogCategory::find($request->get('node'));

        $target = CatalogCategory::find($request->get('target'));

        $direction = $request->get('direction');

        if ($direction == 'inside') {

            if($node->parent_id != $target->id) {
                if ($target->appendNode($node)
                ) {
                    $moved = $node->hasMoved();

                    return response()->json(
                        [
                            'status' => 'OK'
                        ]
                    );
                }
            }
        } elseif ($direction == 'after') {
            if ($node->afterNode($target)
                ->save()
            ) {
                $moved = $node->hasMoved();

                return response()->json(
                    [
                        'status' => 'OK'
                    ]
                );
            }
        }

        return response()->json(
        [
            'status' => 'fail'
        ]
    );

    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getNode(Request $request)
    {
        $catalog = CatalogCategory::find($request->get('node'));

        return view('admin.catalogCategory.form', compact('catalog'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $node = CatalogCategory::find($id);

        if (!$node->update(Input::all())) {

            return Redirect::back()
                ->with(
                    'message',
                    'Something wrong happened while saving your model'
                )
                ->withInput();
        }

        $this->clearCache();

        return response()->json(
            [
                'status' => 'OK',
                'test'=>$request
            ]
        );
    }

    /**
     * @param $direct
     * @param $target_node_id
     * @return \Illuminate\View\View
     */
    public function addForm($direct, $target_node_id)
    {
        $target_node = CatalogCategory::find($target_node_id);

        return view('admin.catalogCategory.add', compact('target_node', 'direct'));
    }

    /**
     * @param         $direct
     * @param         $target_node_id
     * @param Request $request
     * @return mixed
     */
    public function add($direct, $target_node_id, Request $request)
    {
        $target_node = CatalogCategory::where('id', $target_node_id)
            ->first();


        if($target_node) {
            if ($direct == 'inside') {

                CatalogCategory::create($request->except('_token'), $target_node);

                $this->clearCache();

                return response()->json(
                    [
                        'status' => 'OK'
                    ]
                );

            } elseif ($direct == 'after') {

                $node = CatalogCategory::create($request->except('_token'));

                $node->afterNode($target_node)
                    ->save();

                $this->clearCache();

                return response()->json(
                    [
                        'status' => 'OK'
                    ]
                );
            } else {

                return response()->json(
                    [
                        'status' => 'wrong way'
                    ]
                );
            }
        }

        return response()->json(
            [
                'status' => 'wrong way'
            ]
        );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteNode($id)
    {
        $target_node = CatalogCategory::where('id', '=', $id)
            ->first();

        $target_node->descendants()
            ->delete();

        $target_node->delete();

        $this->clearCache();

        return response()->json(['status' => 'ok']);

    }

    /**
     *
     */
    private function clearCache()
    {
        Cache::forget('catalog_tree');
    }


    /**
     * @param CatalogCategory $category_id
     * @return array
     */
    public function getCategoryChildren(CatalogCategory $category_id)
    {
        return [ 0 => trans('user.selectSubcategory') ] + $category_id->children->lists('name', 'id');
    }
}
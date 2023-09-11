<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\CatalogArticle;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

/**
 * Class MenuController
 * @package App\Http\Controllers
 */
class MenuController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menu = Menu::with('article')
            ->orderBy('sort')
            ->get();

        return view('admin.menu.index', compact('menu'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $articles = CatalogArticle::lists('title_ru', 'id');

        return view('admin.menu.create', compact('articles'));
    }

    /**
     * @param Request $request
     */
    public function updateSortMenu(Request $request)
    {
        $i = 1;
        foreach ($request->menu_items as $id) {
            $menu = Menu::find($id);
            $menu->sort = $i;
            $menu->save();
            $i++;
        }
    }

    /**
     * @param Menu $menu
     * @param Request $request
     * @return Redirect
     */
    public function update(Menu $menu, Request $request)
    {
        $menu->type = $request->type;
        $menu->name = $request->name;
        if ($request->type == 'href') {
            $menu->content = $request->href;
        } else {
            $menu->content = $request->article;
        }
        $menu->save();

        return Redirect::to('administrator/menu')
            ->with('success', trans('admin.updateMenuSuccess'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Menu $menu
     * @return Response
     */
    public function edit(Menu $menu)
    {
        if ($menu->type == 'href') {
            $menu->href = $menu->content;
        } else {
            $menu->article = $menu->content;
        }
        $articles = CatalogArticle::lists('title_ru', 'id');

        return view('admin.menu.edit', compact('menu', 'articles'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $menu = new Menu();
        $menu->type = $request->type;
        $menu->name = $request->name;
        if ($request->type == 'href') {
            $menu->content = $request->href;
        } else {
            $menu->content = $request->article;
        }
        $menu->save();

        return Redirect::to('administrator/menu')
            ->with('success', trans('admin.createNewMenuSuccess'));
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        foreach ($request->items as $menu) {
            Menu::where('id', '=', $menu['itemId'])
                ->delete();
        }

    }
}
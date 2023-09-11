<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\CatalogArticle;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

/**
 * Class CatalogArticlesController
 * @package App\Http\Controllers
 */
class CatalogArticlesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $articles = CatalogArticle::all();

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if($request->title_ru == ""){

            return Redirect::back()
                ->withErrors(trans('admin.errorInputEmptyTitle'))
                ->withInput();
        }
        $obj = new CatalogArticle();
        $obj->title_ua = $request->input('title_ua');
        $obj->title_ru = $request->input('title_ru');
        $obj->title_en = $request->input('title_en');
        $obj->text_ua = $request->input('text_ua');
        $obj->text_ru = $request->input('text_ru');
        $obj->text_en = $request->input('text_en');
        $obj->save();

        if($request->input('menu_item') == true) {
            $menu = new Menu();
            $menu->type = 'article';
            $menu->name = $request->input('title_ru');
            $menu->content = $obj->id;
            $menu->save();
        }

        return Redirect::to('administrator/articles')
            ->with('success', trans('admin.createNewArticleSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return Response
     */
    public function showArticle($slug)
    {
        $article = CatalogArticle::findBySlug($slug);
        if(!$article){
            abort(404);
        }
        $local_article_title = "title_" . App::getLocale();
        $title = $article->$local_article_title;

        return view('pages.article', compact(
            'article',
            'title'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param CatalogArticle $article
     * @return Response
     */
    public function edit(CatalogArticle $article)
    {
        $menu = Menu::where('content', '=', $article->id)
            ->first();

        $article->menu_item = isset($menu);

        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CatalogArticle $article
     * @param Request $request
     * @return Response
     */
    public function update(CatalogArticle $article, Request $request)
    {
        $article->title_ru = $request->title_ru;
        $article->text_ru = $request->text_ru;
        $article->save();

        if($request->menu_item == true) {
            $menu = Menu::firstOrCreate(['content' => $article->id]);
            $menu->name = $request->title_ru;
            $menu->type = 'article';
            $menu->save();

        }else{
            Menu::where('content', '=', $article->id)
                ->delete();
        }
        if($request->update){

            return redirect()->back();
        }

        return redirect()->route('administrator.articles.index')
            ->with('success', trans('admin.updateArticleSuccess'));
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        foreach ($request->items as $article) {
            CatalogArticle::where('id', '=', $article['itemId'])
                ->delete();

            Menu::where('content', '=', $article['itemId'])
                ->delete();
        }

    }
}

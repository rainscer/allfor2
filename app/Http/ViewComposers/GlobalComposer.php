<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GlobalComposer
{

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('authUser', Auth::user());
    }
}
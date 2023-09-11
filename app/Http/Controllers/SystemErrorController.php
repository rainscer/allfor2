<?php namespace App\Http\Controllers;

use App\Models\SystemError;

/**
 * Class SystemErrorController
 * @package App\Http\Controllers
 */
class SystemErrorController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function errorsList()
    {
        $systemErrors = SystemError::with('user')
            ->orderBy('created_at', 'DESC')
            ->where('error','not like','404%')
            ->paginate(20);

        return view('admin.systemErrors.list', compact('systemErrors'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showTrace($id)
    {
        $systemError = SystemError::find($id);

        return view('admin.systemErrors.trace', compact('systemError'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        SystemError::truncate();

        return redirect()->back()->with('success','Deleted success');
    }
}

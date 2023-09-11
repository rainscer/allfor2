<?php namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

abstract class BaseController extends Controller
{

    /*
     *
     */
    protected $module_alias;

    /*
     *
     */
    protected $module_name;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $class = $this->getModelClass();
        $records = (new $class)->getModelEntries();
        $title = "All Entries in {$this->module_name}";

        return view("admin.{$this->module_alias}.index", compact(
            'records',
            'title'
        ));
    }

    /**
     * @return string
     */
    abstract public function getModelClass();


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latest()
    {
        $class = $this->getModelClass();
        $records = (new $class)->getModelEntries(24);
        $title = "All Entries in {$this->module_name} for 24 hours";

        return view("admin.{$this->module_alias}.index", compact(
            'records',
            'title'
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active()
    {
        $class = $this->getModelClass();
        $records = (new $class)->getModelEntries(0, true);
        $title = "All Active Entries in {$this->module_name}";

        return view("admin.{$this->module_alias}.index", compact(
            'records',
            'title'
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pending()
    {
        $class = $this->getModelClass();
        $records = (new $class)->getModelEntries(0, false, true);
        $title = "All Pending Entries in {$this->module_name}";

        return view("admin.{$this->module_alias}.index", compact(
            'records',
            'title'
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = "Add New Entry in {$this->module_name}";

        return view("admin.{$this->module_alias}.create", compact(
            'title'
        ));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function store(Request $request)
    {
        $class = $this->getModelClass();

        if(property_exists($class, 'rules')) {
            $this->validate($request, $class::$rules);
        }

        $entry = $class::create($request->all());

        if ($entry) {

            return redirect('administrator/' .$this->module_alias)
                ->with('success_message', trans('success_messages.entry_create'));
        } else {

            return redirect()->back()
                ->with('error_message', trans('error_messages.entry_create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        // Get only the form that matches the specified form id
        $class = $this->getModelClass();
        $entry = $class::findOrFail($id);

        $title = "Edit Entry in {$this->module_name}";

        return view("admin.{$this->module_alias}.edit", compact(
            'title',
            'entry'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        // Get only the form that matches the specified form id
        $class = $this->getModelClass();
        $entry = $class::findOrFail($id);

        $title = "View Entry in {$this->module_name}";

        return view("admin.{$this->module_alias}.view", compact(
            'title',
            'entry'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update($id, Request $request)
    {
        $class = $this->getModelClass();

        if(property_exists($class, 'rules') && method_exists($class, 'getRulesForEdit')) {

            $this->validate($request, $class::getRulesForEdit($id));
        }

        $entry = $class::find($id);

        if($entry){
            $entry->update($request->all());

            if ($entry) {
                return redirect('administrator/' .$this->module_alias)
                    ->with('success_message', trans('success_messages.entry_update'));
            }
        }

        return redirect()
            ->back()
            ->with('error_message', trans('error_messages.entry_update'));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function delete()
    {
        $class = $this->getModelClass();

        $class::whereIn('id',Input::get('list'))
            ->delete();

        return response()->json([
            'status' => 'OK'
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actionOnEntry()
    {
        $class = $this->getModelClass();

        if(Input::has('delete'))
        {
            $records = $class::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->delete();
            }
        }elseif(Input::has('deactivate'))
        {
            $records = $class::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->setDeactive();
            }
        }elseif(Input::has('activate'))
        {
            $records = $class::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->setActive();
            }
        }

        return redirect()
            ->back();
    }
}

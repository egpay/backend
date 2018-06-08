<?php

namespace App\Modules\System;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Requests\BanksFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class BanksController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = Bank::select([
                'banks.id',
                'banks.name_'.$this->systemLang.' as name',
                'banks.name_en',
                'banks.logo',
                'banks.created_at'
            ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.banks.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.banks.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.banks.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Logo'),
                __('Name'),
                __('Created At'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Banks')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Banks');
            }else{
                $this->viewData['pageTitle'] = __('Banks');
            }

            return $this->view('banks.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('News'),
            'url'=> route('system.banks.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Banks'),
        ];

        $this->viewData['pageTitle'] = __('Create Bank');

        return $this->view('banks.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BanksFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('banks/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(Bank::create($theRequest))
            return redirect()
                ->route('system.banks.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.banks.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Bank'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(){
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Banks'),
            'url'=> route('system.banks.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Bank'),
        ];

        $this->viewData['pageTitle'] = __('Edit Bank');
        $this->viewData['result'] = $bank;

        return $this->view('banks.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(BanksFormRequest $request, Bank $bank){

        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('banks/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['logo']);
        }

        if($bank->update($theRequest))
            return redirect()
                ->route('system.banks.edit',$bank->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Bank'));
        else{
            return redirect()
                ->route('system.banks.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Bank'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank,Request $request)
    {
        // Delete Data
        $bank->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Bank has been deleted successfully')];
        }else{
            redirect()
                ->route('system.banks.index')
                ->with('status','success')
                ->with('msg',__('This bank has been deleted'));
        }
    }



}

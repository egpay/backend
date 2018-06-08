<?php

namespace App\Modules\System;

use App\Models\AreaType;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\AreaTypeFormRequest;
use Illuminate\Http\Request;

class AreatypesController extends SystemController
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


    public function index(Request $request){
        $this->viewData['pageTitle'] = __('Area Type');
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Area Type'),
        ];

        $this->viewData['result'] = AreaType::get();
        return $this->view('area-type.index',$this->viewData);
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Area Type'),
            'url'=> url('system/area-type')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Area Type'),
        ];

        $this->viewData['pageTitle'] = __('Create Area Type');

        return $this->view('area-type.create',$this->viewData);
    }


    public function store(AreaTypeFormRequest $request)
    {

        if(AreaType::create($request->all()))
            return redirect()
                ->route('system.area-type.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.area-type.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Area type'));
        }

    }


    public function show(AreaType $area_type)
    {
        return back();
    }


    public function edit(AreaType $area_type)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Area Type'),
            'url'=> url('system/area-type')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Area Type'),
        ];

        $this->viewData['pageTitle'] = __('Edit Area Type');
        $this->viewData['result'] = $area_type;

        return $this->view('area-type.create',$this->viewData);
    }


    public function update(AreaTypeFormRequest $request, AreaType $area_type)
    {
        if($area_type->update($request->all())) {
            return redirect()
                ->route('system.area-type.edit',$area_type->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Area type'));
        }else{
            return redirect()
                ->route('system.area-type.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Area type'));;
        }
    }


    public function destroy(AreaType $area_type){
        // Delete Data
        $area_type->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Area Type has been deleted successfully')];
        }else{
            redirect()
                ->route('area-type.index')
                ->with('status','success')
                ->with('msg',__('This Area has been deleted'));
        }
    }
}

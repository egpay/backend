<?php

namespace App\Modules\System;

use App\Models\Area;
use App\Models\AreaType;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\AreaFormRequest;
use Illuminate\Http\Request;

class AreaController extends SystemController
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
        $this->viewData['pageTitle'] = __('Areas');
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Areas'),
        ];

        $areaData = Area::select([
            'id',
            "name_{$this->systemLang} as name"
        ]);

        $area_type = $request->area_type;
        if($area_type){
            $areaTypeData = AreaType::findOrFail($area_type);
            $this->viewData['area_type'] = $areaTypeData;
            $areaData->where('area_type_id',$area_type);
        }elseif($area_id = $request->area_id){
            $area = Area::findOrFail($area_id);
            $this->viewData['area'] = $area;
            $areaData->where('parent_id',$area_id);
        }

        $this->viewData['result'] = $areaData->get();
        return $this->view('area.index',$this->viewData);
    }

    public function create(Request $request)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Area Type'),
            'url'=> url('system/area')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Area'),
        ];

        if($area_id = $request->area_id){
            $area = Area::findOrFail($area_id);
            $areaType = AreaType::where('id','>',$area->area_type_id)->first();
            if(!$areaType){
                abort(404);
            }

            $this->viewData['area_type'] = $areaType;
            $this->viewData['area'] = $area;
        }else{
            $area_type_id = $request->area_type_id;
            $areaType = AreaType::orderBy('id','ASC')->first();
            if(!$areaType || $area_type_id != $areaType->id){
                abort(404);
            }
            $this->viewData['area_type'] = $areaType;
        }

        $this->viewData['pageTitle'] = __('Create Area');

        return $this->view('area.create',$this->viewData);
    }


    public function store(AreaFormRequest $request)
    {

        if($area_id = $request->area_id){
            $area = Area::findOrFail($area_id);
            $areaType = AreaType::where('id','>',$area->area_type_id)->first();
            if(!$areaType){
                abort(404);
            }
            $request['parent_id']    = $area->id;
            $request['area_type_id'] = $areaType->id;
        }else{
            $area_type_id = $request->area_type_id;
            $areaType = AreaType::orderBy('id','ASC')->first();
            if(!$areaType || $area_type_id != $areaType->id){
                abort(404);
            }
            $this->viewData['area_type_id'] = $areaType->id;
        }

        if(Area::create($request->all()))
            return redirect()
                ->route('system.area.index')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.area.index')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Area'));
        }

    }


    public function show(AreaType $area_type)
    {
        return back();
    }


    public function edit(Area $area)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Area'),
            'url'=> url('system/area')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Area'),
        ];

        $this->viewData['pageTitle'] = __('Edit Area');
        $this->viewData['result'] = $area;

        $areaType = AreaType::where('id','=',$area->area_type_id)->first();
        $this->viewData['area_type'] = $areaType;

        return $this->view('area.create',$this->viewData);
    }


    public function update(AreaFormRequest $request, Area $area)
    {

        if($area->update($request->only(['name_ar','name_en','latitude','longitude']))) {
            return redirect()
                ->route('system.area.edit',$area->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Area type'));
        }else{
            return redirect()
                ->route('system.area.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Area'));
        }
    }


    public function destroy(Area $area){
        // Delete Data
        $area->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Area has been deleted successfully')];
        }else{
            redirect()
                ->route('area-type.index')
                ->with('status','success')
                ->with('msg',__('This Area has been deleted'));
        }
    }
}

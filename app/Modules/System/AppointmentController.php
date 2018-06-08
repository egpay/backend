<?php

namespace App\Modules\System;

use App\Libs\Payments\Validator;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class AppointmentController extends SystemController
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

            $eloquentData = Appointment::select([
                'appointment.id',
                'appointment.model_type',
                'appointment.model_id',
                'appointment.appointment_date_time',
                'appointment.description',
                'appointment.status',
                'appointment.created_at'
            ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'appointment.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('appointment.id','=',$request->id);
            }


            if($request->model_type){
                $eloquentData->where('appointment.model_type','=',$request->model_type);
            }

            if($request->model_id){
                $eloquentData->where('appointment.model_id','=',$request->model_id);
            }

            whereBetween($eloquentData,'appointment.appointment_date_time',$request->appointment_date_time1,$request->appointment_date_time2);

            if($request->status){
                $eloquentData->where('appointment.status','=',$request->status);
            }



            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('model',function($data){
                    return adminDefineUser($data->model_type,$data->model_id,$data->model->firstname.' '.$data->model->lastname);
                })
                ->addColumn('appointment_date_time',function ($data){
                    if(!$data->appointment_date_time){
                        return '--';
                    }else{
                        $explodeData = explode(' ',$data->appointment_date_time);
                        return $explodeData[0].'<br />'.$explodeData[1];
                    }
                })
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('status','{{ucfirst($status)}}')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.appointment.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.appointment.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Created By'),
                __('ADT'),
                __('Description'),
                __('Status'),
                __('Created At'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Appointment')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Appointment');
            }else{
                $this->viewData['pageTitle'] = __('Appointment');
            }

            return $this->view('appointment.index',$this->viewData);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show($ID){
        $data = Appointment::findOrFail($ID);
        $this->viewData['pageTitle'] = __('Appointment Data');
        $this->viewData['result'] = $data;

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Appointment'),
                'url'=> route('system.appointment.index'),
            ],
            [
                'text'=> __('Show Appointment')
            ]
        ];


        return $this->view('appointment.show',$this->viewData);
    }



    public function changeAppointmentDateTime($ID,Request $request){

        $this->validate($request,[
            'date_time'=> 'required|after_or_equal:now|date_format:"Y-m-d H:i:s"'
        ]);

        $data = Appointment::findOrFail($ID);
        $data->update([
            'appointment_date_time'=> $request->date_time
        ]);

        return back()->with('changeAppointmentDateTimeStatus','success')->with('changeAppointmentDateTimeMsg',__('Appointment Date Time has been changed successfully'));
    }

    public function changeStatus($ID,Request $request){
        $data = Appointment::findOrFail($ID);

        $this->validate($request,[
            'status'=> 'required|in:pending,canceled,done,fail',
            'comment'=> 'required'
        ]);

        Auth::user()->appointment_status()->create([
            'appointment_id'=> $ID,
            'status'=> $request->status,
            'comment'=> $request->comment
        ]);
        $data->update(['status'=> $request->status]);

        return back()->with('status','success')->with('msg',__('Status has been changed successfully'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID,Request $request)
    {
        $data = Appointment::findOrFail($ID);

        // Delete Data
        $data->delete();

        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Appointment has been deleted successfully')];
        }else{
            redirect()
                ->route('system.appointment.index')
                ->with('status','success')
                ->with('msg',__('This Appointment has been deleted'));
        }
    }



}

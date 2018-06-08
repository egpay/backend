<?php

namespace App\Modules\Merchant;


use App\Models\Appointment;
use Auth;
use Illuminate\Http\Request;

class AppointmentController extends MerchantController
{

    protected $viewData;


    public function GetAppointment(Request $request){
        $Appointment = Auth::user()->merchant()->appointment();
        if($Appointment->whereIn('status',['pending','canceled'])->count()){
            return [
                'status'    =>      false,
                'title'     =>      __('Fail'),
                'msg'       =>      __('Can not request new Appointment before finishing the previous one'),
            ];
        }


        if($newAppointment = $Appointment->create(['description'=>$request->desc])) {
            return [
                'status' => true,
                'title' => __('Success'),
                'msg' => __('Appointment waiting approval'),
            ];
        } else {
            return [
                'status'    =>      false,
                'title'     =>      __('Fail'),
                'msg'       =>      __('Could not request Appointment at this time'),
            ];
        }
    }




}
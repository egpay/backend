<?php

namespace App\Modules\System;

use App\Http\Requests\StaffTargetFormRequest;
use App\Models\PaymentInvoice;
use App\Models\Staff;
use App\Models\StaffTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use Form;
use DB;

class StaffTargetController extends SystemController
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

            $eloquentData = StaffTarget::join("staff",'staff.id','=','staff_target.staff_id')
                ->select([
                    'staff_target.id',
                    'staff_target.staff_id',
                    'staff_target.year',
                    'staff_target.month',
                    'staff_target.amount',
                    'staff.firstname',
                    'staff.lastname'
                ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'staff_target.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('staff_target.id','=',$request->id);
            }

            if($request->staff_id){
                $eloquentData->where('staff_target.staff_id','=',$request->staff_id);
            }

            if($request->year){
                $eloquentData->where('staff_target.year','=',$request->year);
            }

            if($request->month){
                $eloquentData->where('staff_target.month','=',$request->month);
            }

            whereBetween($eloquentData,'staff_target.amount',$request->amount1,$request->amount2);

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('staff_target.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('staff_id',function($data){
                    return '<a href="'.route('system.staff.show',$data->staff_id).'" target="_blank">'.$data->firstname .' '.$data->lastname.'</a>';
                })
                ->addColumn('year', function($data){
                    return $data->month.'/'.$data->year;
                })
                ->addColumn('amount','{{amount($amount,true)}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.staff-target.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.staff-target.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.staff-target.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Staff'),
                __('Month'),
                __('Amount'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Staff Target')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Staff Target');
            }else{
                $this->viewData['pageTitle'] = __('Staff Target');
            }

            return $this->view('staff-target.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){

        $staffID = $request->id;

        $staffData = Staff::findOrFail($staffID);

        $this->viewData['staff_data'] = $staffData;

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Staff Target'),
            'url'=> route('system.staff-target.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Staff Target'),
        ];

        $this->viewData['pageTitle'] = __('Create Staff Target');

        return $this->view('staff-target.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffTargetFormRequest $request)
    {
        $theRequest = $request->all();

        // Get Supervisor Type
        $supervisor = Staff::findOrFail($theRequest['staff_id']);
        if($supervisor->is_supervisor()){
            $managedStaff = array_column($supervisor->managed_staff->toArray(),'staff_managed_id');

            $theRequest['is_supervisor'] = 'yes';
            $theRequest['managed_staff_ids'] = implode(',',$managedStaff);

            $theRequest['amount'] = StaffTarget::where('year',$request->year)
                ->where('month',$request->month)
                ->whereIn('staff_id',$managedStaff)
                ->selectRaw('SUM(`amount`) as `amount`')
                ->get()
                ->first()['amount'];

        }else{
            $theRequest['is_supervisor'] = 'no';
        }

        $theRequest['sales_commission'] = serialize([
            'payment_sales_target'          => setting('payment_sales_target'),
            'payment_sales_commission_rate' => setting('payment_sales_commission_rate'),
        ]);

        if($insertData = StaffTarget::create($theRequest)){

            return redirect()
                ->route('system.staff-target.show',$insertData->id)
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));

        }else{
            return redirect()
                ->route('system.staff-target.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Add Staff Target'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(StaffTarget $staff_target){

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_target->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Staff Target'),
                'url'=> route('system.staff-target.index'),
            ],
            [
                'text'=> __('#ID:').$staff_target->id,
            ]
        ];


        // Staff Target Calculate
        // -- if Staff is sales
        /*if($staff_target->is_supervisor == 'no'){
            $paymentInvoices = PaymentInvoice::join('wallet_settlement','wallet_settlement.id','=','payment_invoice.wallet_settlement_id')
                // Payment Invoice Where
                ->where('payment_invoice.status','paid')
                ->whereNotNull('payment_invoice.wallet_settlement_id')

                // Wallet Settlement Where
                ->where('wallet_settlement.staff_id',$staff_target->staff_id)
                ->where('wallet_settlement.status','done')
                ->whereRaw("CONCAT(MONTH(`wallet_settlement`.`from_date_time`),'-',YEAR(`wallet_settlement`.`from_date_time`)) = ?",[$staff_target->month.'-'.$staff_target->year])
                ->whereRaw("CONCAT(MONTH(`wallet_settlement`.`to_date_time`),'-',YEAR(`wallet_settlement`.`to_date_time`)) = ?",[$staff_target->month.'-'.$staff_target->year])

                // Get Data
                ->selectRaw('SUM(`payment_invoice`.`total_amount`) as `total`')
                ->selectRaw('COUNT(`payment_invoice`.`id`) as `count`')
                ->selectRaw('GROUP_CONCAT(`payment_invoice`.`id`) as `payment_invoices_id`')
                ->get()
                ->first();
        }else{
            $paymentInvoices = PaymentInvoice::join('wallet_settlement','wallet_settlement.id','=','payment_invoice.wallet_settlement_id')
                // Payment Invoice Where
                ->where('payment_invoice.status','paid')
                ->whereNotNull('payment_invoice.wallet_settlement_id')

                // Wallet Settlement Where
                ->whereIn('wallet_settlement.staff_id',explode(',',$staff_target->managed_staff_ids))
                ->where('wallet_settlement.status','done')
                ->whereRaw("CONCAT(MONTH(`wallet_settlement`.`from_date_time`),'-',YEAR(`wallet_settlement`.`from_date_time`)) = ?",[$staff_target->month.'-'.$staff_target->year])
                ->whereRaw("CONCAT(MONTH(`wallet_settlement`.`to_date_time`),'-',YEAR(`wallet_settlement`.`to_date_time`)) = ?",[$staff_target->month.'-'.$staff_target->year])

                // Get Data
                ->select('wallet_settlement.staff_id')
                ->selectRaw('SUM(`payment_invoice`.`total_amount`) as `total`')
                ->selectRaw('COUNT(`payment_invoice`.`id`) as `count`')
                ->selectRaw('GROUP_CONCAT(`payment_invoice`.`id`) as `payment_invoices_id`')
                ->groupBy('wallet_settlement.staff_id')
                ->get();

            $managedStaffTarget = StaffTarget::whereIn('staff_id',explode(',',$staff_target->managed_staff_ids))
                ->where('year',$staff_target->year)
                ->where('month',$staff_target->month)
                ->with('staff')
                ->get();


            dd($managedStaffTarget->toArray());

            $staffTargetData = [];
            if($managedStaffTarget){

                foreach($managedStaffTarget as $key => $value){

                }

            }






            $this->viewData['managedStaffTarget'] = $managedStaffTarget;
        }

        $this->viewData['paymentInvoices'] = $paymentInvoices;*/


        $this->viewData['pageTitle'] = __('Show Staff Target');
        $this->viewData['result'] = $staff_target;

        return $this->view('staff-target.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffTarget $staff_target)
    {

        if(new \DateTime(date('Y-m').'-01') > new \DateTime(date($staff_target->year.'-'.$staff_target->month).'-01') ){
            return redirect()->route('system.staff-target.show',$staff_target->id);
        }


        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Staff Target'),
            'url'=> route('system.staff-target.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Staff Target'),
        ];

        $this->viewData['pageTitle'] = __('Edit Staff Target');
        $this->viewData['result'] = $staff_target;


        return $this->view('staff-target.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StaffTargetFormRequest $request, StaffTarget $staff_target)
    {
        if(new \DateTime(date('Y-m').'-01') > new \DateTime(date($staff_target->year.'-'.$staff_target->month).'-01') ){
            return redirect()->route('system.staff-target.show',$staff_target->id);
        }

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_target->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $theRequest = $request->only([
            'amount',
            'description'
        ]);

        $supervisor = $staff_target->staff;


        if($supervisor->is_supervisor()){
            $managedStaff = array_column($supervisor->managed_staff->toArray(),'staff_managed_id');

            $theRequest['is_supervisor'] = 'yes';
            $theRequest['managed_staff_ids'] = implode(',',$managedStaff);

            $theRequest['amount'] = StaffTarget::where('year',$request->year)
                ->where('month',$request->month)
                ->whereIn('staff_id',$managedStaff)
                ->selectRaw('SUM(`amount`) as `amount`')
                ->get()
                ->first()['amount'];
        }else{
            $theRequest['is_supervisor'] = 'no';
        }

        $theRequest['sales_commission'] = serialize([
            'payment_sales_target'          => setting('payment_sales_target'),
            'payment_sales_commission_rate' => setting('payment_sales_commission_rate')
        ]);


        if(!$theRequest['amount']){
            return back()->withErrors();
        }


        if($staff_target->update($theRequest)){
            return redirect()
                ->route('system.staff-target.edit',$staff_target->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Staff Target'));
        }else{
            return redirect()
                ->route('system.staff-target.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Staff Target'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffTarget $staff_target,StaffTargetFormRequest $request)
    {

        if(new \DateTime(date('Y-m').'-01') > new \DateTime(date($staff_target->year.'-'.$staff_target->month).'-01') ){
            abort(401, 'Unauthorized.');
        }

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_target->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        // Delete Data
        $staff_target->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Staff Target has been deleted successfully')];
        }else{
            redirect()
                ->route('system.staff-target.index')
                ->with('status','success')
                ->with('msg',__('This Staff Target has been deleted'));
        }
    }

}
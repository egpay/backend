<?php

namespace App\Modules\System;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class TicketsController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['CommentStatus'] = ['open'=>__('Open'),'closed'=>__('Closed'),'done'=>__('Done')];
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Tickets'),
                'url'=> url('system/tickets')
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

            $eloquentData = Ticket::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'tickets.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('tickets.id', '=',$request->id);
            }

            if($request->status){
                $eloquentData->where('tickets.status', '=',$request->status);
            }

            /*
            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }
            */

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('merchant', function($data){
                    if($data->merchant_id)
                        return link_to_route('merchant.merchant.show',$data->merchant_name,['id'=>$data->merchant_id]);
                    else
                        return '--';
                })
                ->addColumn('invoice', function($data){
                    if($data->invoiceable_type=='App\Models\Order')
                        return link_to_route('merchant.order.show',__('Order').'#'.$data->invoiceable_id,['id'=>$data->invoiceable_id]);
                    else
                        return link_to_route('payment.invoice.show',__('Invoice').'#'.$data->invoiceable_id,['id'=>$data->invoiceable_id]);

                })
                ->addColumn('subject', function($data){
                    return $data->subject;
                })
                ->addColumn('forward', function($data){
                    if($data->to_id) {
                        if ($data->to_type == 'App\Models\Staff')
                            return link_to_route('system.staff.show',__('Staff').': '.$data->forwardTo->Fullname, ['id' => $data->to_id]);
                        else
                            return link_to_route('system.permission-group.show',__('Dep').': '.$data->forwardTo->name, ['id' => $data->to_id]);
                    } else {
                        return '--';
                    }
                })
                ->addColumn('status', function($data){
                    switch($data->ticket_status){
                        case 'open':
                            return '<a href="'.route('system.tickets.show',['id'=>$data->id]).'"><span class="btn btn-outline-danger">'.__('Open').'</a></span>';
                        break;
                        case 'closed':
                            return '<a href="'.route('system.tickets.show',['id'=>$data->id]).'"><span class="btn btn-outline-warning">'.__('Closed').'</a></span>';
                        break;
                        case 'done':
                            return '<a href="'.route('system.tickets.show',['id'=>$data->id]).'"><span class="btn btn-outline-success">'.__('Done').'</a></span>';
                        break;
                    }
                    return $data;
                })
                ->addColumn('created_by', function($data){
                    return $data->createdBy->Fullname;
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.tickets.show',$data->id)."\">".__('View')."</a></li>
                                <!--<li class=\"dropdown-item\"><a href=\"".route('system.tickets.edit',$data->id)."\">".__('Edit')."</a></li>-->
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.tickets.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = ['ID',__('Merchant'),__('Invoice'),__('subject'),__('To'),__('Status'),__('Created By'),__('Action')];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Tickets');
            }else{
                $this->viewData['pageTitle'] = __('Tickets');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Tickets'),
            ];


            return $this->view('ticket.index',$this->viewData);
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
            'text'=> __('Merchant Ticket'),
            'url'=> url('system/merchant/tickets')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Tickets'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Ticket');


        return $this->view('ticket.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $RequestedData = $request->only(['merchant_id','invoiceable_id','subject','details','to_id_group','to_id']);
        $this->validate($request,[
            'merchant_id'       =>      'nullable|exists:merchants,id',
            'invoiceable_id'    =>      'nullable|exists:payment_invoice,id',
            'subject'           =>      'required',
            'details'           =>      'required',
        ]);

        $RequestedData['invoiceable_type'] = 'App\Models\PaymentInvoice';
        $RequestedData['created_by_staff_id'] = Auth::id();
        $RequestedData['status'] = 'open';

        /*
         * Set the forward to ticket
         */
        if(!$RequestedData['to_id']) {
            if($RequestedData['to_id_group']) {
                $RequestedData['to_id'] = $RequestedData['to_id_group'];
                $RequestedData['to_type'] = 'App\Models\PermissionGroup';
            } else {
                $RequestedData['to_type'] = null;
                $RequestedData['to_id'] = null;
            }
        } else {
            $RequestedData['to_type'] = 'App\Models\Staff';
        }


        if($ticket = Ticket::create($RequestedData)){
            $ticket->AllStatus()->create([
                'status'          =>      'open',
                'staff_id'        =>      Auth::id(),

            ]);
            return redirect()
                ->route('system.tickets.create')
                ->with('status', 'success')
                ->with('msg', __('Ticket has been added successfully'));
        } else {
            return redirect()
                ->route('system.tickets.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Ticket'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Tickets'),
                'url'=> route('system.tickets.index'),
            ],
            [
                'text'=>  $ticket->id,
            ]
        ];


        $this->viewData['pageTitle'] = $ticket->subject;
        $this->viewData['result'] = $ticket;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('ticket.show',$this->viewData);

    }

    public function comment(Request $request,Ticket $ticket){
        $RequestedData = $request->only(['comment']);
        $this->validate($request,['comment'=>'required']);

        $RequestedData['staff_id'] = Auth::id();

        if($comment = $ticket->comments()->create($RequestedData)) {
            return redirect()->route('system.tickets.show',$ticket->id)
                ->with('status','success')
                ->with('msg',__('Ticket comment has been added successfully'));
        }else{
            return redirect()->route('system.tickets.show',$ticket->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Ticket comment'));
        }
    }

    public function changeStatus(Request $request,Ticket $ticket){

        $RequestedData = $request->only(['status']);
        $this->validate($request,['status'=>'required']);
        $RequestedData['staff_id'] = Auth::id();

        if($comment = $ticket->AllStatus()->create($RequestedData)) {
            $ticket->update(['status'=>$RequestedData['status']]);
            return redirect()->route('system.tickets.show',$ticket->id)
                ->with('status','success')
                ->with('msg',__('Ticket comment has been added successfully'));
        }else{
            return redirect()->route('system.tickets.show',$ticket->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Ticket comment'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket){

        return redirect()->route('system.tickets.index');

        $this->viewData['breadcrumb'][] = [
            'text'=> __('System Tickets'),
            'url'=> url('system/tickets')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit System Ticket'),
        ];
        $this->viewData['pageTitle'] = __('Edit Ticket');

        if($ticket->to_type == 'App\Models\PermissionGroup'){
            $ticket->to_id_group = $ticket->to_id;
            unset($ticket->to_id);
            $ticket->to_id = null;
        }
        $ticket->invoiceable;
        //dd($ticket);
        $this->viewData['result'] = $ticket;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('ticket.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Ticket $ticket)
    {
        redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket,Request $request){
        // Delete Data
        $ticket->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Ticket has been deleted successfully')];
        }else{
            redirect()
                ->route('system.tickets.index')
                ->with('status','success')
                ->with('msg',__('Ticket has been deleted'));
        }
    }


}

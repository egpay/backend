<?php

namespace App\Modules\Merchant;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Models\UserAction;

class MerchantAdvertisementController extends MerchantController
{
    protected $viewData;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = Advertisement::select([
                'advertisements.id',
                'advertisements.image',
                "advertisements.name",
                'advertisements.type',
                'advertisements.staff_id',
                'advertisements.from_date',
                'advertisements.to_date',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name")
            ])
                ->join('staff','staff.id','=','advertisements.staff_id');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'advertisements.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('advertisements.id', '=',$request->id);
            }

            if($request->name){
                $eloquentData->where('advertisements.name', '=',$request->name);
            }

            if($request->route){
                $eloquentData->whereRaw("FIND_IN_SET(?,advertisements.route)",[$request->route]);
            }

            if($request->route_id){
                $eloquentData->where('advertisements.route_id',$request->route_id);
            }

            if($request->status){
                $eloquentData->where('advertisements.status',$request->status);
            }

            if($request->type){
                $eloquentData->where('advertisements.type',$request->type);
            }

            whereBetween($eloquentData,'advertisements.total_amount',$request->total_amount1,$request->total_amount2);


            if($request->merchant_id){
                $eloquentData->where('advertisements.merchant_id',$request->merchant_id);
            }

            if($request->staff_id){
                $eloquentData->where('advertisements.staff_id', '=',$request->staff_id);
            }

            whereBetween($eloquentData,'advertisements.from_date',$request->from_date1,$request->from_date2);
            whereBetween($eloquentData,'advertisements.to_date',$request->to_date1,$request->to_date2);


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('image',function($data){
                    if(!$data->image) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->image,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('type','{{ucfirst($type)}}')
                ->addColumn('from_date','{{$from_date}}')
                ->addColumn('to_date','{{$to_date}}')
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.advertisement.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.advertisement.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.advertisement.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Image'),
                __('Name'),
                __('Type'),
                __('From Date'),
                __('To Date'),
                __('Created By'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Advertisements')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Advertisements');
            }else{
                $this->viewData['pageTitle'] = __('Advertisements');
            }


            return $this->view('advertisement.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->viewData['pageTitle'] = __('Create Advertisement');

        return $this->view('advertisement.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertisementFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('advertisement/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] =1;// Auth::id();

        if(Advertisement::create($theRequest))
            return redirect()
                ->route('system.advertisement.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.advertisement.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Advertisement'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(Advertisement $advertisement){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Advertisement'),
            'url'=> route('system.advertisement.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $advertisement->name,
        ];

        $this->viewData['pageTitle'] = $advertisement->name;
        $this->viewData['result'] = $advertisement;

        return $this->view('advertisement.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertisement $advertisement)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Advertisement'),
            'url'=> route('system.advertisement.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Advertisement'),
        ];

        $this->viewData['pageTitle'] = __('Edit Advertisement');
        $this->viewData['result'] = $advertisement;

        return $this->view('advertisement.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertisementFormRequest $request, Advertisement $advertisement)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('advertisement/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['image']);
        }

        if($advertisement->update($theRequest))
            return redirect()
                ->route('system.advertisement.edit',$advertisement->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Advertisement'));
        else{
            return redirect()
                ->route('system.advertisement.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Advertisement'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        // Delete Data
        $advertisement->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Advertisement has been deleted successfully')];
        }else{
            redirect()
                ->route('system.advertisement.index')
                ->with('status','success')
                ->with('msg',__('This Advertisement has been deleted'));
        }
    }



}

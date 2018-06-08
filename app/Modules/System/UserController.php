<?php

namespace App\Modules\System;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Config;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Gate;
use Form;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UserFormRequest;

class UserController extends SystemController
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

            $eloquentData = User::select(['id','firstname','lastname','email','mobile','status']);


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->user_name){
                $eloquentData->where(\DB::raw('CONCAT(`firstname`," ",`lastname`)'), 'LIKE',"%".$request->user_name."%");
            }

            if($request->email){
                $eloquentData->where('email','LIKE','%'.$request->email.'%');
            }

            if($request->mobile){
                $eloquentData->where('mobile','LIKE','%'.$request->mobile.'%');
            }

            if($request->national_id){
                $eloquentData->where('national_id','LIKE','%'.$request->national_id.'%');
            }

            whereBetween($eloquentData,'birthdate',$request->birthdate1,$request->birthdate2);

            if($request->national_id){
                $eloquentData->where('national_id','LIKE','%'.$request->national_id.'%');
            }

            if($request->is_parent){
                if($request->is_parent == 1){
                    $eloquentData->whereNull('parent_id');
                }elseif($request->parent_id == 2){
                    $eloquentData->whereNotNull('parent_id');
                }
            }

            if($request->status){
                $eloquentData->where('status','=',$request->status);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('image',function($data){
                    if(!$data->image) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->image,70,70)).'" />';
                })
                ->addColumn('firstname', function($data){
                    return $data->firstname.' '.$data->lastname;
                })
                ->addColumn('email','<a href="mailto:{{$email}}">{{$email}}</a>')
                ->addColumn('mobile','<a href="tel:{{$mobile}}">{{$mobile}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.users.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.users.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.users.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID','Image','Name','E-mail','Mobile','Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Users')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Users');
            }else{
                $this->viewData['pageTitle'] = __('Users');
            }



            return $this->view('users.index',$this->viewData);
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
            'text'=> __('Users'),
            'url'=> route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create User'),
        ];

        $this->viewData['pageTitle'] = __('Create User');

        $parentID = request('parent_id') ?? old('parent_id');
        if($parentID){
            $parentOf = User::findOrFail($parentID);
            $this->viewData['parentOf'] = $parentOf;
        }

        return $this->view('users.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('users/'.date('y').'/'.date('m'));
        }

        $theRequest['password'] = bcrypt($theRequest['password']);

        if(!$request->parent_id){
            $theRequest['parent_id'] = null;
        }

        if(User::create($theRequest))
            return redirect()
                ->route('system.users.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.users.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add User'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Users'),
                'url'=> route('system.users.index'),
            ],
            [
                'text'=> $user->firstname.' '.$user->lastname,
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Users');

        $this->viewData['result'] = $user;
        return $this->view('users.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Users'),
            'url'=> route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit User'),
        ];

        $this->viewData['pageTitle'] = __('Edit User');
        $this->viewData['result'] = $user;

        return $this->view('users.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $user)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('users/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['image']);
        }

        if($request->password){
            $theRequest['password'] = bcrypt($theRequest['password']);
        }else{
            unset($theRequest['password']);
        }

        if(!$request->parent_id){
            $theRequest['parent_id'] = null;
        }

        if($user->update($theRequest))
            return redirect()
                ->route('system.users.edit',$user->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant product Category'));
        else{
            return redirect()
                ->route('system.users.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit User'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete Data
        $user->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('User has been deleted successfully')];
        }else{
            redirect()
                ->route('system.users.index')
                ->with('status','success')
                ->with('msg',__('This User has been deleted'));
        }
    }


    public function ajax(Request $request){
        $type = $request->type;
        switch ($type){
            case 'users':
                $name = $request->search;

                $data = User::whereNull('parent_id')
                        ->where(function($query){
                           // $query->where()
                        });

                break;
        }
    }


}

<?php

namespace App\Modules\System;

use App\Http\Requests\LoyaltyProgramIgnoreFormRequest;
use App\Models\LoyaltyProgramIgnore;
use App\Models\LoyaltyPrograms;
use Illuminate\Http\Request;
use Auth;


class LoyaltyProgramIgnoreController extends SystemController{

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
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $id = $request->id;
        $loyaltyProgram = LoyaltyPrograms::findOrFail($id);

        $this->viewData['loyaltyProgram'] = $loyaltyProgram;

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Loyalty Programs'),
            'url' => route('system.loyalty-programs.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $loyaltyProgram->{'name_'.$this->systemLang},
            'url' => route('system.loyalty-programs.show',$loyaltyProgram->id)
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Ignore Data'),
        ];


        $this->viewData['pageTitle'] = __('Ignore Data From').' '.$loyaltyProgram->{'name_'.$this->systemLang};
        return $this->view('loyalty-program-ignore.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoyaltyProgramIgnoreFormRequest $request){
        $theRequest = $request->toArray();
        $theRequest['staff_id'] = Auth::id();
        if(LoyaltyProgramIgnore::create($theRequest))
            return redirect()
                ->route('system.loyalty-programs.show',$request->loyalty_program_id)
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.loyalty-program-ignore.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Loyalty Program Ignore Data'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(LoyaltyProgramIgnore $loyaltyProgramIgnore){
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(LoyaltyProgramIgnore $loyaltyProgramIgnore)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Loyalty Programs'),
            'url' => route('system.loyalty-programs.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $loyaltyProgramIgnore->loyaltyProgram->{'name_'.$this->systemLang},
            'url' => route('system.loyalty-programs.show',$loyaltyProgramIgnore->loyaltyProgram->id)
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Ignore Data'),
        ];


        $this->viewData['pageTitle'] = __('Edit Loyalty Programs Ignore Data');
        $this->viewData['result'] = $loyaltyProgramIgnore;

        return $this->view('loyalty-program-ignore.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(LoyaltyProgramIgnoreFormRequest $request,LoyaltyProgramIgnore $loyaltyProgramIgnore)
    {
        $newRequestData = $request->only([
            'ignoremodel_id',
            'ignoremodel_type',
            'description_ar',
            'description_en'
        ]);

        if($loyaltyProgramIgnore->update($newRequestData))
            return redirect()
                ->route('system.loyalty-program-ignore.edit',$loyaltyProgramIgnore->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Loyalty Program Ignore Data'));
        else{
            return redirect()
                ->route('system.loyalty-program-ignore.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Loyalty Program Ignore Data'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoyaltyProgramIgnore $loyaltyProgramIgnore,Request $request)
    {
        // Delete Data
        $loyaltyProgramIgnore->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Loyalty Program Ignore has been deleted successfully')];
        }else{
            redirect()
                ->route('system.loyalty-program-ignore.index')
                ->with('status','success')
                ->with('msg',__('This Loyalty Program Ignore has been deleted'));
        }
    }

}
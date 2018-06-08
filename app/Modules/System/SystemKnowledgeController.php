<?php

namespace App\Modules\System;

use App\Models\SystemKnowledge;
use Illuminate\Http\Request;
use App\Http\Requests\SystemKnowledgeFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class SystemKnowledgeController extends SystemController
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

            $eloquentData = SystemKnowledge::select([
                'id',
                "name_{$this->systemLang} as name"
            ]);

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

            if($request->name){
                orWhereByLang($eloquentData,'name',$request->name);
            }

            if($request->content){
                orWhereByLang($eloquentData,'content',$request->content);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.system-knowledge.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.system-knowledge.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('System Knowledge')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted System Knowledge');
            }else{
                $this->viewData['pageTitle'] = __('System Knowledge');
            }

            return $this->view('system-knowledge.index',$this->viewData);
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
            'text'=> __('System Knowledge'),
            'url'=> route('system.system-knowledge.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create System Knowledge'),
        ];

        $this->viewData['pageTitle'] = __('Create System Knowledge');


        return $this->view('system-knowledge.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemKnowledgeFormRequest $request)
    {
        $theRequest = $request->all();
        $theRequest['staff_id'] = Auth::id();

        if($data = SystemKnowledge::create($theRequest)){
            $data->addToIndex();
            return redirect()
                ->route('system.system-knowledge.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        }else{
            return redirect()
                ->route('system.system-knowledge.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add System Knowledge'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(SystemKnowledge $system_knowledge){
        return $system_knowledge->{'content_'.$this->systemLang};
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemKnowledge $system_knowledge)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('System Knowledge'),
            'url'=> route('system.system-knowledge.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit System Knowledge'),
        ];

        $this->viewData['pageTitle'] = __('Edit System Knowledge');
        $this->viewData['result'] = $system_knowledge;

        return $this->view('system-knowledge.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(SystemKnowledgeFormRequest $request, SystemKnowledge $system_knowledge)
    {
        $theRequest = $request->all();

        if($system_knowledge->update($theRequest)){
            $system_knowledge->reindex();
            return redirect()
                ->route('system.system-knowledge.edit',$system_knowledge->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit System Knowledge'));
        }else{
            return redirect()
                ->route('system.system-knowledge.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit System Knowledge'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemKnowledge $system_knowledge)
    {
        // Delete Data
        $system_knowledge->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('System Knowledge has been deleted successfully')];
        }else{
            redirect()
                ->route('system.system-knowledge.index')
                ->with('status','success')
                ->with('msg',__('This System Knowledge has been deleted'));
        }
    }




    // Elasticsearch

    public function search(Request $request){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('System Knowledge'),
        ];

        $this->viewData['pageTitle'] = __('System Knowledge');


        if($q = $request->q){
            $page = $request->page ?? 1;
            $number_per_page = 10;
            $result = SystemKnowledge::complexSearch(array(
                'type'=> 'system_knowledge',
                'body' => array(
                    'query' => array(
                        'match' => array(
                            '_all'=> [
                                'query' => $q ,
                                'fuzziness' => "2",
                                "operator" => "OR"
                            ]
                        )
                    ),
//                    'highlight'=> [
//                        'pre_tags' => "<em>",
//                        'post_tags' => "</em>",
//                        'fields' => (object)Array('name_en' => new \stdClass),
//                        'require_field_match' => false
//                    ],
                    'from' => ($page-1) * $number_per_page,
                    'size' => $number_per_page
                )
            ))->paginate($number_per_page);
            $this->viewData['result'] = $result->toArray();
        }


        return $this->view('system-knowledge.search',$this->viewData);
    }



}

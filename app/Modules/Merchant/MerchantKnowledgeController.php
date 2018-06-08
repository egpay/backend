<?php

namespace App\Modules\Merchant;

use App\Models\MerchantKnowledge;
use Illuminate\Http\Request;
use App\Http\Requests\MerchantKnowledgeFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class MerchantKnowledgeController extends MerchantController
{
    protected $viewData;

    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = MerchantKnowledge::select([
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

            if($request->mcontent){
                orWhereByLang($eloquentData,'content',$request->mcontent);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.merchant-knowledge.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.merchant-knowledge.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Action')];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Knowledge');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Knowledge');
            }

            return $this->view('merchant-knowledge.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['pageTitle'] = __('Create Merchant Knowledge');

        return $this->view('merchant-knowledge.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantKnowledgeFormRequest $request)
    {
        $theRequest = $request->all();
        $theRequest['merchant_staff_id'] = Auth::id();

        if($data = MerchantKnowledge::create($theRequest)){
            $data->addToIndex();
            return redirect()
                ->route('panel.merchant.merchant-knowledge.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        }else{
            return redirect()
                ->route('panel.merchant.merchant-knowledge.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Knowledge'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(MerchantKnowledge $merchant_knowledge){
        return $merchant_knowledge->{'content_'.$this->systemLang};
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantKnowledge $merchant_knowledge)
    {

        $this->viewData['pageTitle'] = __('Edit Merchant Knowledge');
        $this->viewData['result'] = $merchant_knowledge;

        return $this->view('merchant-knowledge.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantKnowledgeFormRequest $request, MerchantKnowledge $merchant_knowledge)
    {
        $theRequest = $request->all();

        if($merchant_knowledge->update($theRequest)){
            $merchant_knowledge->reindex();
            return redirect()
                ->route('panel.merchant.merchant-knowledge.edit',$merchant_knowledge->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant Knowledge'));
        }else{
            return redirect()
                ->route('panel.merchant.merchant-knowledge.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Knowledge'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantKnowledge $merchant_knowledge,Request $request)
    {
        // Delete Data
        $merchant_knowledge->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant Knowledge has been deleted successfully')];
        }else{
            redirect()
                ->route('panel.merchant.merchant-knowledge.index')
                ->with('status','success')
                ->with('msg',__('This Merchant Knowledge has been deleted'));
        }
    }




    // Elasticsearch

    public function search(Request $request){
        $this->viewData['pageTitle'] = __('Merchant Knowledge');


        if($q = $request->q){
            $page = $request->page ? $request->page :  1;
            $number_per_page = 10;
            $result = MerchantKnowledge::complexSearch(array(
                'type'=> 'merchant_knowledge',
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


        return $this->view('merchant-knowledge.search',$this->viewData);
    }



}

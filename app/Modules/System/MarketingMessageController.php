<?php

namespace App\Modules\System;

use App\Models\AreaType;
use App\Models\MarketingMessage;
use App\Models\MerchantCategory;
use Illuminate\Http\Request;
use App\Http\Requests\MarketingMessageFormRequest;
use Symfony\Component\Console\Tests\Descriptor\MarkdownDescriptorTest;
use Yajra\Datatables\Facades\Datatables;
use Auth;
use App\Libs\DataAnalysis;
use App\Models\UserAction;
class MarketingMessageController extends SystemController
{

    public function __construct(){
        $a = DataAnalysis::users(['merchant_id'=>'1']);
//        print_r($a);


        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];


        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        $this->viewData['merchant_categories'] = MerchantCategory::get(['id','name_'.$this->systemLang.' as name']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        return;
        if($request->isDataTable){

            $eloquentData = News::select([
                'news.id',
                'news.image',
                "news.name_{$this->systemLang} as name",
                'news.staff_id',
                'news.status',
                'news.news_category_id',
                'news.created_at',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
                \DB::Raw("news_categories.name_{$this->systemLang} as news_category_name")

            ])
                ->join('news_categories','news_categories.id','=','news.news_category_id')
                ->join('staff','staff.id','=','news.staff_id');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'news.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('news.id', '=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'news.name',$request->name);
            }

            if($request->content){
                orWhereByLang($eloquentData,'news.content',$request->content);
            }

            if($request->news_category_id){
                $eloquentData->where('news.news_category_id',$request->news_category_id);
            }

            if($request->staff_id){
                $eloquentData->where('news.staff_id', '=',$request->staff_id);
            }

            if($request->status){
                $eloquentData->where('news.status','=',$request->status);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('image',function($data){
                    if(!$data->image) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->image,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('news_category_name','{{$news_category_name}}')
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.news.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.news.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.news.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Image'),__('Name'),__('Category'),__('Creatore'),__('Created At'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('News')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Users');
            }else{
                $this->viewData['pageTitle'] = __('News');
            }

            // --- Controller Data
            $this->viewData['categories'] = [__('Select Category')];
            $this->viewData['categories'] = array_merge($this->viewData['categories'],array_column(NewsCategory::get(['id',"name_{$this->systemLang} as name"])->toArray(),'name','id'));
            // --- Controller Data

            return $this->view('news.index',$this->viewData);
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
            'text'=> __('News'),
            'url'=> route('system.marketing-message.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Marketing Messages'),
        ];

        $this->viewData['pageTitle'] = __('Create Marketing Messages');

        return $this->view('marketing-message.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarketingMessageFormRequest $request)
    {
        // @TODO : Merchant Filter
        $theRequest = [];
        $requestData = $request->only([
            'title',
            'message_type',
            'sms_content_ar',
            'sms_content_en',
            'email_name_ar',
            'email_content_ar',
            'email_name_en',
            'email_content_en',
            'notification_name_ar',
            'notification_content_ar',
            'url_ar',
            'notification_name_en',
            'notification_content_en',
            'url_en',
            'send_to',
            'area_id',
            'send_at',
            'user_filter_data',
            'marketing_filter_data'
        ]);


        $theRequest['message_type'] = $requestData['message_type'];
        $theRequest['title'] = $requestData['title'];
        $theRequest['send_to'] = $requestData['send_to'];

        if($request->message_type == 'sms'){
            $theRequest['content_ar'] = $requestData['sms_content_ar'];
            $theRequest['content_en'] = $requestData['sms_content_en'];
        }elseif($request->message_type == 'email'){
            $theRequest['name_ar']    = $requestData['email_name_ar'];
            $theRequest['name_en']    = $requestData['email_name_en'];

            $theRequest['content_ar'] = $requestData['email_content_ar'];
            $theRequest['content_en'] = $requestData['email_content_en'];
        }else{
            $theRequest['name_ar']    = $requestData['notification_name_ar'];
            $theRequest['name_en']    = $requestData['notification_name_en'];

            $theRequest['content_ar'] = $requestData['notification_content_ar'];
            $theRequest['content_en'] = $requestData['notification_content_en'];

            $theRequest['url_ar'] = $requestData['url_ar'];
            $theRequest['url_en'] = $requestData['url_en'];
            if($request->file('image')) {
                $theRequest['image'] = $request->image->store('marketing-message/'.date('y').'/'.date('m'));
            }

        }

        if($request->send_to == 'user'){
            $theRequest['filter_data'] = serialize($requestData['user_filter_data']);
        }elseif($request->send_to == 'marketing_message_data'){
            $theRequest['filter_data'] = $requestData['marketing_filter_data'];
        }else{
            // @TODO : Merchant Filter
        }

        if($request->message_type == 'notification' && $request->file('image')) {
            $theRequest['image'] = $request->image->store('marketing-message/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(MarketingMessage::create($theRequest))
            return redirect()
                ->route('system.marketing-message.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.marketing-message.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Marketing Messages'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(){
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketingMessage $marketing_messages)
    {
        // -- Can't Update when status in-progress
        if($marketing_message->status != 'request'){
            redirect()
                ->route('system.marketing-message.index')
                ->with('status','danger')
                ->with('msg',__('Can\'t update this Marketing Message'));
        }
        // -- Can't Update when status in-progress

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('News'),
            'url'=> route('system.marketing-message.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Marketing Messages'),
        ];

        $this->viewData['pageTitle'] = __('Edit Marketing Messages');
        $this->viewData['result'] = $marketing_messages;

        return $this->view('marketing-message.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MarketingMessageFormRequest $request,MarketingMessage $marketing_message)
    {

        // -- Can't Update when status in-progress
        if($marketing_message->status != 'request'){
            redirect()
                ->route('system.marketing-message.index')
                ->with('status','danger')
                ->with('msg',__('Can\'t update this Marketing Message'));
        }
        // -- Can't Update when status in-progress

        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('marketing-message/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['image']);
        }

        if($marketing_message->update($theRequest))
            return redirect()
                ->route('system.marketing-message.edit',$marketing_message->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Marketing Message'));
        else{
            return redirect()
                ->route('system.marketing-message.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Marketing Message'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketingMessage $marketing_message)
    {
        // Delete Data
        $marketing_message->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Marketing Message has been deleted successfully')];
        }else{
            redirect()
                ->route('system.marketing-message.index')
                ->with('status','success')
                ->with('msg',__('This Marketing Message has been deleted'));
        }
    }



}

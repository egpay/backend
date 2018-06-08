<?php

namespace App\Modules\System;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Requests\NewsFormRequest;
use Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\Datatables\Facades\Datatables;
use App\Mail\SenderMail;

class NewsController extends SystemController
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
            'url'=> route('system.news.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create News'),
        ];

        $this->viewData['pageTitle'] = __('Create News');

        // --- Controller Data
        $this->viewData['categories'] = [__('Select Category')];
        $this->viewData['categories'] = array_merge($this->viewData['categories'],array_column(NewsCategory::get(['id',"name_{$this->systemLang} as name"])->toArray(),'name','id'));
        // --- Controller Data

        return $this->view('news.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('news/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(News::create($theRequest))
            return redirect()
                ->route('system.news.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.news.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add News'));
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
    public function edit(News $news)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('News'),
            'url'=> route('system.news.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit News'),
        ];

        $this->viewData['pageTitle'] = __('Edit News');
        $this->viewData['result'] = $news;
        // --- Controller Data
        $this->viewData['categories'] = [__('Select Category')];
        $this->viewData['categories'] = array_merge($this->viewData['categories'],array_column(NewsCategory::get(['id',"name_{$this->systemLang} as name"])->toArray(),'name','id'));
        // --- Controller Data

        return $this->view('news.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(NewsFormRequest $request, News $news)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('news/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['image']);
        }

        if($news->update($theRequest))
            return redirect()
                ->route('system.news.edit',$news->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit News'));
        else{
            return redirect()
                ->route('system.news.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit News'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        // Delete Data
        $news->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('News has been deleted successfully')];
        }else{
            redirect()
                ->route('system.news.index')
                ->with('status','success')
                ->with('msg',__('This News has been deleted'));
        }
    }



}

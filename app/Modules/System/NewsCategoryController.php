<?php

namespace App\Modules\System;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Requests\NewsCategoryFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class NewsCategoryController extends SystemController
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

            $eloquentData = NewsCategory::select([
                'news_categories.id',
                'news_categories.name_'.$this->systemLang.' as name',
                'news_categories.descriptin_'.$this->systemLang.' as descriptin',
                'news_categories.icon',
                'news_categories.type',
                'news_categories.staff_id',
                'news_categories.status',
                'news_categories.status',
                \DB::Raw("(SELECT COUNT(*) FROM `news` WHERE `news_category_id` = news_categories.id) as `news_count`"),
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),

            ])
                ->join('staff','staff.id','=','news_categories.staff_id');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('type',function($data){
                    return __(ucfirst($data->type));
                })
                ->addColumn('name','<a target="_blank" href="{{route(\'system.news.index\',[\'news_category_id\'=>$id])}}">{{$name}}</a>')
                ->addColumn('descriptin','{{$descriptin}}')
                ->addColumn('news_count','{{number_format($news_count)}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.news-category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.news-category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.news-category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
                __('Icon'),
                __('Type'),
                __('Name'),
                __('Descriptin'),
                __('Num. News'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('News Categories')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted News Categories');
            }else{
                $this->viewData['pageTitle'] = __('News Categories');
            }

            return $this->view('news-category.index',$this->viewData);
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
            'url'=> route('system.news-category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create News Category'),
        ];

        $this->viewData['pageTitle'] = __('Create Category');

        return $this->view('news-category.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsCategoryFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('news-category/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(NewsCategory::create($theRequest))
            return redirect()
                ->route('system.news-category.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.news-category.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add News Category'));
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
    public function edit(NewsCategory $news_category)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('News Category'),
            'url'=> route('system.news-category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit News Category'),
        ];

        $this->viewData['pageTitle'] = __('Edit News Category');
        $this->viewData['result'] = $news_category;

        return $this->view('news-category.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(NewsCategoryFormRequest $request, NewsCategory $news_category)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->image->store('news-category/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['icon']);
        }

        if($news_category->update($theRequest))
            return redirect()
                ->route('system.news-category.edit',$news_category->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit News Category'));
        else{
            return redirect()
                ->route('system.news-category.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit News Category'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsCategory $news_category)
    {
        // Delete Data
        $news_category->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('News Category has been deleted successfully')];
        }else{
            redirect()
                ->route('system.news-category.index')
                ->with('status','success')
                ->with('msg',__('This News Category has been deleted'));
        }
    }



}

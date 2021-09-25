<?php

namespace App\Http\Controllers;

use App\Phone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'phones_index_visible_columns', ['name', 'status','representative']);
        $filters = getFilters($request, 'phones_index_filtered');
        $search = getSearch($request, 'phones_index_search');
        $pagination = getPagination($request, 'phones_index_pagination');
        $model = Phone::select(DB::raw('phones.id, phones.content, phones.source,phones.dnd,phones.country_code, GROUP_CONCAT(tags.content) tags'))
        ->join('taggables',function($join){
            $join->on('taggables.taggable_id','=','phones.id');
            $join->where('taggables.taggable_type','=','App\Phone');
        })
        ->join('tags','tags.id','=','taggables.tag_id')->groupBy('phones.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'tags' && $key != 'tags_filter_mode'){
                    $chunks = explode(" ",$val);
                    $model->where(function ($query) use ($chunks,$key){
                        foreach ($chunks as $it) {
                            if(strlen($it) > 0)
                            $query->where('phones.'.$key,'like','%'.$it.'%');
                        }
                    });
                }
                elseif($key == 'tags'){
                    $model = Phone::select(DB::raw('phones.id,phones.content,phones.source,phones.dnd,phones.country_code,a.tags'))
                    ->join('taggables',function($join){
                        $join->on('taggables.taggable_id','=','phones.id');
                        $join->where('taggables.taggable_type','=','App\Phone');
                    })
                    ->join('tags','tags.id','=','taggables.tag_id')
                    ->join(DB::raw('(SELECT phones.id id,GROUP_CONCAT(tags.content)tags FROM phones JOIN taggables ON (taggables.taggable_id = phones.id AND taggables.taggable_type = "App\\\\Phone") JOIN tags ON tags.id = taggables.tag_id GROUP BY phones.id) AS a'),'a.id','=','phones.id')
                    ->whereIn('tags.content',$val);

                    if(array_key_exists('tags_filter_mode',$filters) && $filters['tags_filter_mode'] == 'With All Tags'){
                        $model->groupBy('phones.id')->havingRaw('count(*)='.count($val));
                    }
                    else{
                        $model->distinct();
                    }
                }
            }
        }
        if($search){
            $model->where(function ($query) use ($search){
                $query->where('phones.content','like','%'.$search.'%');
            });
        }
        return response()->json([
            'link_key' => 'content',
            'visible_columns' => $visibleColumns,
            'model' => $model->paginate($pagination['rpp'],['*'],'page',$pagination['page']),
            'sortby' => $pagination['sortBy'],
            'descending' => $pagination['desc'] == 'DESC' ? true : false,
            'filtered' => $filters,
            'search' => $search,
          ]);
    }

    public function columns()
    {
        $columns = [
            [
                'name' => 'country_code',
                'label' => 'Code',
                'field' => 'country_code',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'content',
                'label' => 'Phone No.',
                'field' => 'content',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'source',
                'label' => 'Source',
                'field' => 'source',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'dnd',
                'label' => 'DND',
                'field' => 'dnd',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'tags',
                'label' => 'Tags',
                'field' => 'tags',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
        ];
        return $columns;
    }

    public function filterables()
    {
        $allAttributes = [
            [
                'field_type' => 'Selection',
                'name' => 'Tags',
                'slug' => 'tags',
                'searcheable' => true,
                'search_link' => 'tag_search',
                'options' => [],
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Tags Filter Mode',
                'slug' => 'tags_filter_mode',
                'searcheable' => false,
                'options' => ['With Any Tags','With All Tags'],
                'multiple' => false,
                'value' => []
            ],
            [
                'field_type' => 'String',
                'name' => 'Source',
                'slug' => 'source'
            ],
        ];
        return $allAttributes;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => [
                'unique:phones,content',
                function($attribute,$value,$fail) use($request){
                    if($request->country_code == '+91' && strlen($value) != 10){
                        $fail('Phone should have exactly 10 digits');
                    }
                }]
        ]);
        $model = Phone::create($request->only('content','source','country_code'));
        $tag_ids = [];
        foreach($request->tags as $tag){
            $tag_ids[] = \App\Tag::firstOrCreate([
                'content' => $tag
            ])->id;
        }
        $model->tags()->sync($tag_ids);
        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return \App\Phone::with('tags','addresses.phones','addresses.addressable')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function edit(Phone $phone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Phone $phone)
    {
        $request->validate([
            'content' => [
                'unique:phones,content,'.$phone->id,
                function($attribute,$value,$fail) use($request){
                    if($request->country_code == '+91' && strlen($value) != 10){
                        $fail('Phone should have exactly 10 digits');
                    }
                }]
        ]);
        $phone->update($request->only('content','source','country_code'));
        $tag_ids = [];
        foreach($request->tags as $tag){
            $tag_ids[] = \App\Tag::firstOrCreate([
                'content' => $tag
            ])->id;
        }
        $phone->tags()->sync($tag_ids);
        return response()->json(['message'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Phone $phone)
    {
        //
    }
}

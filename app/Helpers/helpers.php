<?php
use Illuminate\Http\Request;

function getVisibleColumns(Request $request, $PREFERENCE_SLUG, $default) {
    $visibleColumns = $default;
    if ($request->visible && count(json_decode($request->visible,TRUE)) > 0 && $request->visible != 'undefined') {
        $visibleColumns = json_decode($request->visible,TRUE);
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => $request->visible
            ]
        );
    }
    else {
        $pageIndexVisibleColumnModel = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        $visibleColumnsModel = \App\UserPreferenceValue::where([
                ['user_id','=',\Auth::user()->id],
                ['preference_id','=',$pageIndexVisibleColumnModel->id]
            ])->first();
        if ($visibleColumnsModel && count(json_decode($visibleColumnsModel->value)) > 0 && $visibleColumnsModel->value != 'undefined') {
            $visibleColumns = json_decode($visibleColumnsModel->value);
        }
    }
    return $visibleColumns;
}

function getFilters(Request $request, $PREFERENCE_SLUG) {
    $filters = [];
    if ($request->filter && count(json_decode($request->filter,TRUE)) > 0 && $request->filter != 'undefined') {
        $filters = json_decode($request->filter,TRUE);
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => $request->filter
            ]
        );
    } else if ($request->filter_deleted) {
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => []
            ]
        );
        return $filters;
    }
    else {
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        $filtersModel = \App\UserPreferenceValue::where([
                ['user_id','=',\Auth::user()->id],
                ['preference_id','=',$preference->id]
            ])->first();
        // dd($preference->id);
        if ($filtersModel && $filtersModel->value && count(json_decode($filtersModel->value,TRUE)) > 0 && $filtersModel->value != 'undefined') {
            $filters = json_decode($filtersModel->value,TRUE);
        }
    }
    return $filters;
}

function getSearch(Request $request, $PREFERENCE_SLUG) {
    $search = NULL;
    if ($request->search && $request->search != 'undefined') {
        $search = $request->search;
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => $request->search
            ]
        );
    } else if ($request->clear_search) {
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => ''
            ]
        );
        return $search;
    }else {
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        $searchModel = \App\UserPreferenceValue::where([
                ['user_id','=',\Auth::user()->id],
                ['preference_id','=',$preference->id]
            ])->first();
        // dd($preference->id);
        if ($searchModel && $searchModel->value != 'undefined') {
            $search = $searchModel->value;
        }
    }
    return $search;
}

function getPagination(Request $request, $PREFERENCE_SLUG) {
    $pagination = [
        'sortBy' => NULL,
        'rpp' => 10,
        'desc' => 'ASC',
        'page' => 1
    ];
    if (
        ($request->sortby && $request->sortby != 'null') ||
        ($request->rpp && $request->rpp != 'null' && (int)$request->rpp != 10) || 
        ($request->page && $request->page != 'null')
    ) {
        $pagination = [
            'sortBy' => ($request->sortby && $request->sortby != 'null') ? $request->sortby : NULL,
            'rpp' => ($request->rpp && $request->rpp != 'null') ? $request->rpp : 10,
            'desc' => ($request->sortby && $request->sortby != 'null') ? ($request->desc == 'true' ? 'DESC' : 'ASC') : 'ASC',
            'page' => ($request->page && $request->page != 'null') ? $request->page : 1
        ];
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        \App\UserPreferenceValue::updateOrCreate(
            [
            'user_id' => \Auth::user()->id, 'preference_id' => $preference->id
            ],
            [
            'value' => json_encode($pagination)
            ]
        );

    } else {
        $preference = \App\Preference::where('slug',$PREFERENCE_SLUG)->first();
        $paginationModel = \App\UserPreferenceValue::where([
                ['user_id','=',\Auth::user()->id],
                ['preference_id','=',$preference->id]
            ])->first();
        // dd($preference->id);
        if ($paginationModel && $paginationModel->value != 'undefined') {
            $pagination = json_decode($paginationModel->value,TRUE);
        }
    }
    return $pagination;
}

function generateSerial($table, $prefix, $column = 'serial_no') {
    $today_date = \Carbon\Carbon::now()->format('ymd');
    $nextDay = \Carbon\Carbon::today('Asia/Kolkata')->addHours(24)->toDateTimeString();
    $today = \Carbon\Carbon::today('Asia/Kolkata')->toDateTimeString();
    $todayCount = \Illuminate\Support\Facades\DB::table($table)->whereBetween('created_at',[$today,$nextDay])->count();
    $serial_no = $prefix.$today_date.str_pad($todayCount, 4,0,STR_PAD_LEFT);
    $sn_val_arr['serial_no'] = $serial_no;
    $count = $todayCount;
    while(\Illuminate\Support\Facades\Validator::make($sn_val_arr,['serial_no'=>'unique:'.$table.','.$column])->fails()){
        $serial_no = substr($serial_no, 0, -4);
        $count = $count + 1;
        $serial_no = $serial_no.str_pad($count, 4,0,STR_PAD_LEFT);
        $sn_val_arr['serial_no'] = $serial_no;
    }
    return $serial_no;
}
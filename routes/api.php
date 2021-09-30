<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::post('/dentodeal','DentodealOrderController@store');

Route::middleware(['auth:sanctum'])->post('logout',function(Request $request) {
    $request->user()->currentAccessToken()->delete();
});
Route::middleware(['auth:sanctum','checkuserisactive'])->get('/user', function (Request $request) {
    $user = \Auth::user();
    $permissions = $user->getAllPermissions();
    $roles = $user->getRoleNames();
    return response()->json([
        'model' => $user,
        'permissions' => $permissions,
        'roles' => $roles
    ]);
});
Route::middleware(['auth:sanctum','checkuserisactive'])->group(function(){
    Route::get('analytics/', function(Request $request) {
        // dd($request->from);
        $from = \Carbon\Carbon::createFromFormat('d/m/Y', $request->from);
        $to = \Carbon\Carbon::createFromFormat('d/m/Y', $request->to);
        $model = \App\SaleOrder::whereBetween('invoiced_at',[$from, $to])->orderBy('created_at');
        if($request->has('source')) {
            $model->where('source',$request->source);
        }
        if($request->has('representative')) {
            $model->where('representative_id',$request->representative);
        }
        $data = [
            'count' => $model->count(),
            'total' => $model->sum('total'),
            'saleOrders' => $model->select('serial_no','total')->get()
        ];
        return $data;
    });
    Route::get('sales/today',function(){
        $nextDay = \Carbon\Carbon::today('Asia/Kolkata')->addHours(24)->toDateTimeString();
        $today = \Carbon\Carbon::today('Asia/Kolkata')->toDateTimeString();
        //return $today->addHours(24)->toDateTimeString();
        return [
            'total' => \App\SaleOrder::whereBetween('invoiced_at',[$today,$nextDay])->sum('total'),
            'otc' => \App\SaleOrder::whereBetween('invoiced_at',[$today,$nextDay])->where('otc','=',1)->sum('total'),
            'non_otc' => \App\SaleOrder::whereBetween('invoiced_at',[$today,$nextDay])->where('otc','=',0)->sum('total'),
            'cash' => \App\Transaction::where('payment_via','cash')->where('standalone',0)->whereBetween('created_at',[$today,$nextDay])->sum('amount')
        ];
    });
    Route::get('sales/count','SaleOrderController@count');
    Route::get('shipments/count','ShipmentController@count');
    Route::get('countries', function () {
        return \App\Country::select('name')->orderBy('name')->get()->pluck('name');
    });
    Route::get('phonecodes', function () {
        $countries = \App\Country::orderBy('name')->get();
        return $countries->map(function($item){
            return [
                'label' => '('.$item->phonecode.')'.$item->name,
                'value' => $item->phonecode
            ];
        });
    });
    Route::get('states/{country?}', function ($country = NULL) {
        if($country){
            $country_model = \App\Country::where('name',$country)->first();
            return \App\State::select('name')->where('country_id',$country_model->id)->orderBy('name')->get()->pluck('name');
        }
        else
            return \App\State::select('id','name','country_id')->orderBy('name')->get()->pluck('name');
    });
    Route::get('districts/{state?}', function ($state = NULL) {
        if($state){
            $state_model = \App\State::where('name',$state)->first();
            return \App\District::select('name')->where('state_id',$state_model->id)->orderBy('name')->get()->pluck('name');
        }
        else
            return \App\District::select('id','name','state_id')->orderBy('name')->get()->pluck('name');
    });
    Route::get('all-permissions',function(){
        return Permission::all();
    });
    Route::get('notification_types',function(){
        return \App\NotificationType::all();
    });
    Route::get('representatives',function() {
        return App\User::representatives();
        //return \App\User::role('Sales')->get();
    });
    Route::get('users/has_permission','UserController@hasPermission');
    Route::get('gst_options',function() {
        return config('app.gst_options');
    });

    Route::post('img_upload',function(Request $request){
        //dd($request->toArray());
        $now = \Carbon\Carbon::now();

        $path = $request->file('file')->storeAs(
            $request->folder.'/'.$now->year.'/'.$now->month,$now->timestamp.'.'.$request->file('file')->extension(),
            'public'
        );
        $img = Image::make('storage/'.$path);
        $thumbnail = [200,200];
        $img->resize($thumbnail[0],$thumbnail[1], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thubnail_path = 'storage/'.$request->folder.'/'.$now->year.'/'.$now->month.'/'.$now->timestamp.'-'.$thumbnail[0].'x'.$thumbnail[1].'.'.$request->file('file')->extension();
        $img->save($thubnail_path, 100);
        $uri = 'storage/'.$path;
        return response()->json([
            'url'=>asset($uri),
            'uri' => $uri,
            'thumbnail_uri' => $thubnail_path,
            'thumbnail_url' => asset($thubnail_path) 
        ]);
    });
    Route::post('img_delete',function(Request $request){
        $data = json_decode($request->data,TRUE);
        $path = substr($data['uri'],strpos($data['uri'],'storage')+8);
        $thumbnail_path = substr($data['thumbnail_uri'],strpos($data['thumbnail_uri'],'storage')+8);
        Storage::disk('public')->delete([$path,$thumbnail_path]);
        return response()->json([
            'message'=> 'success' 
        ]);
    });

    Route::post('user/fcm_token', function(Request $request) {
        \App\UserFcmToken::firstOrCreate([
            'token' => $request->token,
            'user_id' => $request->user()->id
        ]);
    });
    Route::get('config/{slug}',function($slug) {
        return \App\Config::where('label',$slug)->first();
    });
    Route::post('export','ExportController@index');
    Route::resource('users', 'UserController');
    Route::resource('user_roles', 'UserRoleController');
    Route::resource('attributes','AttributeController');
    Route::resource('product_attribute_sets','ProductAttributeSetController');
    Route::post('preferences','PreferenceController@store');
    Route::get('preferences','PreferenceController@index');
    //Route::get('products/stock/{id}','ProductController@getStock');

    Route::get('products/request_approval/{id}','ProductController@requestApproval');
    Route::get('products/approve/{id}','ProductController@approve');
    Route::get('products/activate/{id}','ProductController@activate');
    Route::get('products/revert/{id}','ProductController@revert');
    Route::get('products/sales/{id}','ProductController@getSales');
    Route::get('products/basic/{id}/{warehouse_id}','ProductController@getBasic');
    Route::get('products/min_margin/{id}','ProductController@getMinMargin');
    Route::post('products/min_margin/{id}','ProductController@updateMinMargin');
    Route::get('products/stock/{id}/{warehouse_id?}','ProductController@getStock');
    Route::get('products/search','ProductController@search');
    Route::get('products/columns','ProductController@getColumns');
    Route::get('products/filterables','ProductController@getFilterables');
    Route::post('generate_sku/{id?}','ProductController@getSku');
    Route::post('products/import','ProductController@import');
    // Route::get('products/export','ProductController@export');
    Route::get('products/cost/{id}','ProductController@getCost');
    Route::get('products/purchases/{id}','ProductController@getPurchases');
    Route::get('products/pricelists/{id}','ProductController@getPricelists');
    Route::post('products/pricelists/{id}','ProductController@updatePricelists');
    Route::apiResource('products','ProductController');

    Route::get('product_bundles/delete_item/{id}','ProductBundleController@deleteItem');
    Route::get('product_bundles/items/{id}','ProductBundleController@items');
    Route::get('product_bundles/search','ProductBundleController@search');
    Route::get('product_bundles/columns','ProductBundleController@getColumns');
    Route::get('product_bundles/filterables','ProductBundleController@getFilterables');
    Route::apiResource('product_bundles','ProductBundleController');

    Route::apiResource('product_types','ProductTypeController');
    Route::apiResource('product_departments','ProductDepartmentController');
    Route::get('product_categories/search','ProductCategoryController@search');
    Route::apiResource('product_categories','ProductCategoryController');
    Route::get('product_sub_categories/search','ProductSubCategoryController@search');
    Route::apiResource('product_sub_categories','ProductSubCategoryController');
    Route::get('product_brands/search','ProductBrandController@search');
    Route::apiResource('product_brands','ProductBrandController');
    
    Route::get('customers/sale_orders/unsettled/{id}','CustomerController@getUnsettledOrders');
    Route::get('customers/balance/{id}','CustomerController@getBalance');
    Route::get('customers/convert/{id}','CustomerController@convert');
    Route::post('customers/status/{id}','CustomerController@updateStatus');
    Route::post('customers/remark/{id}','CustomerController@updateRemark');
    Route::get('customers/types','CustomerController@getTypes');
    Route::get('customers/ledger/{id}','CustomerController@getLedger');
    Route::get('customers/sales/{id}','CustomerController@getSales');
    Route::get('customers/request_approval/{id}','CustomerController@requestApproval');
    Route::get('customers/approve/{id}','CustomerController@approve');
    Route::get('customers/activate/{id}','CustomerController@activate');
    Route::get('customers/revert/{id}','CustomerController@revert');
    Route::get('customers/disable/{id}','CustomerController@disable');
    Route::get('customers_search','CustomerController@search');
    Route::get('customer_addresses/{id?}','CustomerController@getAddresses');
    Route::get('customers/columns','CustomerController@getColumns');
    Route::get('customers/filterables','CustomerController@getFilterables');
    Route::post('customers/import','CustomerController@import');
    // Route::post('customers/export','CustomerController@export');
    Route::post('customers/add_shipping/{id}','CustomerController@addShipping');
    Route::apiResource('customers','CustomerController');
    
    Route::get('logistics',function(){
        return \App\LogisticPartner::select('name')->get()->pluck('name');
    });
    Route::apiResource('logistic_partners','LogisticController');
    Route::apiResource('pricelists','PricelistController');
    Route::apiResource('warehouses','WarehouseController');

    
    Route::get('sale_order_revisits/columns','SaleOrderRevisitController@getColumns');
    Route::get('sale_order_revisits/filterables','SaleOrderRevisitController@getFilterables');
    Route::get('sale_order_revisits','SaleOrderRevisitController@index');

    Route::post('sale_orders/packaging/{id}','SaleOrderController@packaging');
    Route::get('sale_orders/delete_item/{id}','SaleOrderController@deleteItem');
    Route::get('sale_orders/request_confirmation/{id}','SaleOrderController@requestConfirmation');
    Route::get('sale_orders/confirm/{id}','SaleOrderController@confirm');
    Route::get('sale_orders/download/{id}','SaleOrderController@download');
    Route::post('sale_orders/export/{id}','SaleOrderController@export');
    Route::get('sale_orders/send_to_accounts/{id}','SaleOrderController@sendToAccounts');
    Route::get('sale_orders/request_approval/{id}','SaleOrderController@requestApproval');
    Route::post('sale_orders/invoice/{id}','SaleOrderController@invoice');
    Route::post('sale_orders/approve/{id}','SaleOrderController@approve');
    Route::post('sale_orders/register_payment/{id}','SaleOrderController@registerPayment');
    Route::get('sale_orders/reject/{id}','SaleOrderController@reject');
    Route::get('sale_orders/returns/{id}','SaleOrderController@getSaleReturnItems');
    Route::get('sale_orders/ship/{id}','SaleOrderController@ship');
    Route::get('sale_orders/cancel/{id}','SaleOrderController@cancelOrder');
    Route::get('sale_orders/revert/{id}','SaleOrderController@revert');
    Route::get('sale_orders/complete/{id}','SaleOrderController@complete');
    Route::get('sale_orders/complete/{id}','SaleOrderController@complete');
    Route::get('sale_orders/columns','SaleOrderController@getColumns');
    Route::get('sale_orders/filterables','SaleOrderController@getFilterables');
    Route::apiResource('sale_orders','SaleOrderController');

    Route::get('packaging/{so_id}/download/{id}','PackagingBoxController@download');
    Route::get('packaging/{so_id}','PackagingBoxController@index');
    Route::get('packaging/{so_id}/items','PackagingBoxController@itemsIndex');
    Route::post('packaging/{so_id}','PackagingBoxController@store');
    Route::put('packaging/{so_id}/{id}','PackagingBoxController@update');
    Route::get('packaging/{so_id}/{id}','PackagingBoxController@show');
    Route::delete('packaging/{so_id}/{id}','PackagingBoxController@destroy');

    Route::get('shipments/sms/{id}','ShipmentController@sendSms');
    Route::get('shipments/label/{id}','ShipmentController@label');
    Route::get('shipments/columns','ShipmentController@getColumns');
    Route::get('shipments/filterables','ShipmentController@getFilterables');
    Route::put('shipments/complete/{id}','ShipmentController@complete');
    Route::apiResource('shipments','ShipmentController');

    Route::get('suppliers/columns','SupplierController@getColumns');
    Route::get('suppliers/filterables','SupplierController@getFilterables');
    Route::get('suppliers_search','SupplierController@search');
    Route::get('suppliers/request_activation/{id}','SupplierController@requestActivation');
    Route::get('suppliers/activate/{id}','SupplierController@activate');
    Route::get('suppliers/revert/{id}','SupplierController@revert');
    Route::apiResource('suppliers','SupplierController');

    Route::get('purchases/returns/{id}','PurchaseController@getReturnItems');
    Route::get('purchases/revert/{id}','PurchaseController@revert');
    Route::get('purchases/send_admin/{id}','PurchaseController@sendAdmin');
    Route::post('purchases/approve/{id}','PurchaseController@approve');
    Route::get('purchases/activate/{id}','PurchaseController@activate');
    Route::get('purchases/columns','PurchaseController@getColumns');
    Route::get('purchases/filterables','PurchaseController@getFilterables');
    Route::apiResource('purchases','PurchaseController');

    Route::get('phones/columns','PhoneController@columns');
    Route::get('phones/filterables','PhoneController@filterables');
    Route::apiResource('phones','PhoneController');

    Route::get('emails/columns','EmailController@columns');
    Route::get('emails/filterables','EmailController@filterables');
    Route::apiResource('emails','EmailController');

    Route::get('quotations/{id}/delete_document/{doc_id}','QuotationController@deleteDocument');
    Route::post('quotations/{id}/documents','QuotationController@addDocuments');
    Route::get('quotations/{id}/delete_payment/{payment_id}','QuotationController@deletePayment');
    Route::post('quotations/{id}/add_payment','QuotationController@addPayment');
    Route::post('quotations/partial_convert/{id}','QuotationController@partialConvert');
    Route::get('quotations/convert/{id}','QuotationController@convert');
    Route::post('quotations/export/{id}','QuotationController@export');
    Route::get('quotations/columns','QuotationController@getColumns');
    Route::get('quotations/filterables','QuotationController@getFilterables');
    Route::get('quotations/delete_item/{id}','QuotationController@deleteItem');
    Route::apiResource('quotations','QuotationController');

    Route::apiResource('quotation_templates','QuotationTemplateController');

    Route::get('leads/columns','LeadController@getColumns');
    Route::get('leads/filterables','LeadController@getFilterables');
    Route::apiResource('leads','LeadController');

    Route::post('sale_returns/activate/{id}','SaleReturnController@activate');
    Route::get('sale_returns/columns','SaleReturnController@getColumns');
    Route::get('sale_returns/filterables','SaleReturnController@getFilterables');
    Route::apiResource('sale_returns','SaleReturnController');

    Route::post('purchase_returns/activate/{id}','PurchaseReturnController@activate');
    Route::get('purchase_returns/columns','PurchaseReturnController@getColumns');
    Route::get('purchase_returns/filterables','PurchaseReturnController@getFilterables');
    Route::apiResource('purchase_returns','PurchaseReturnController');

    Route::get('goods_receive_notes/columns','GoodsReceiveNoteController@getColumns');
    Route::get('goods_receive_notes/filterables','GoodsReceiveNoteController@getFilterables');
    Route::get('goods_receive_notes/activate/{id}','GoodsReceiveNoteController@activate');
    Route::get('goods_receive_notes/revert/{id}','GoodsReceiveNoteController@revert');
    Route::apiResource('goods_receive_notes','GoodsReceiveNoteController');

    Route::get('stock_entries/revert/{id}','StockEntryController@revert');
    Route::get('stock_entries/sendtoaccounts/{id}','StockEntryController@sendtoaccounts');
    Route::get('stock_entries/activate/{id}','StockEntryController@activate');
    Route::get('stock_entries/delete_item/{id}','StockEntryController@deleteItem');
    Route::apiResource('stock_entries','StockEntryController');
});

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
     protected $table = 'products';
     protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'sync_stock_dentodeal' => 'boolean',
        'dentodeal_enabled' => 'boolean',
        'dentodeal_bundled' => 'boolean'
    ];

    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }

    public function type()
    {
        return $this->belongsTo('App\ProductType','type_id');
    }

    public function department()
    {
        return $this->belongsTo('App\ProductDepartment','department_id');
    }

    public function category()
    {
        return $this->belongsTo('App\ProductCategory','category_id');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\ProductSubCategory','sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\ProductBrand','brand_id');
    }

    public function getCost()
    {
        return \App\PurchaseItem::join('purchases','purchases.id','=','purchase_items.purchase_id')->select('purchase_items.cost','purchase_items.landing')
        ->where('purchase_items.product_id',$this->id)->whereIn('purchases.status',['Admin Approved','Active'])->orderBy('purchases.id','desc')->first();
    }

    public function purchase_items()
    {
        return $this->hasMany('App\PurchaseItem','product_id')->orderBy('purchase_id','desc');
    }

    public function sale_items()
    {
        return $this->hasMany('App\SaleOrderItem','product_id')->orderBy('sale_order_id','desc');
    }

    public function sale_return_items()
    {
        return $this->hasMany('App\SaleReturnItem','product_id');
    }

    public function grn_items()
    {
        return $this->hasMany('App\GoodsReceiveNoteItem','product_id');
    }

    public function stock_entry_items()
    {
        return $this->hasMany('App\StockEntryItem','product_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\ProductStock','product_id');
    }

    public function boxes() {
        return $this->belongsToMany('App\PackagingBox', 'packaging_box_item', 'product_id', 'box_id');
    }

    public function default_supplier() {
        return $this->belongsTo(Supplier::class, 'default_supplier_id');
    }

    public function availableStock($warehouse_id)
    {
        return $this->stocks()->where([
            ['reservable_id','=',0],
            ['warehouse_id','=',$warehouse_id]
        ])->sum('qty');
    }
}

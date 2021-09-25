<?php

use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $name = \App\Attribute::create([
        'slug' => 'name',
        'type' => 'varchar',
        'name' => 'Name',
        'entities' => ['App\Product','App\Customer'],
        'sort_order' => 0,
        'is_required' => true,
        'is_system' => true,
        'is_unique' => true,
        'field_type' => 'String',
      ]);
      $alias = \App\Attribute::create([
        'slug' => 'alias',
        'type' => 'varchar',
        'name' => 'Alias',
        'entities' => ['App\Product','App\Customer'],
        'sort_order' => 15,
        'is_required' => false,
        'is_system' => true,
        'is_unique' => false,
        'field_type' => 'String',
      ]);
      $sku = \App\Attribute::create([
        'slug' => 'sku',
        'type' => 'varchar',
        'name' => 'SKU',
        'entities' => ['App\Product'],
        'sort_order' => 1,
        'is_required' => true,
        'is_system' => true,
        'is_unique' => true,
        'field_type' => 'String',
      ]);
      $enabled = \App\Attribute::create([
        'slug' => 'enabled',
        'type' => 'boolean',
        'name' => 'Enabled',
        'entities' => ['App\Product'],
        'sort_order' => 2,
        'is_required' => true,
        'is_system' => true,
        'field_type' => 'Boolean',
      ]);
      $hsn = \App\Attribute::create([
        'slug' => 'hsn',
        'type' => 'varchar',
        'name' => 'HSN Code',
        'entities' => ['App\Product'],
        'sort_order' => 3,
        'is_required' => false,
        'is_system' => true,
        'field_type' => 'String',
      ]);
      $reorder_code = \App\Attribute::create([
        'slug' => 'reorder_code',
        'type' => 'varchar',
        'name' => 'Reorder Code',
        'entities' => ['App\Product'],
        'sort_order' => 4,
        'is_required' => false,
        'is_system' => true,
        'field_type' => 'String',
      ]);
      $weight = \App\Attribute::create([
        'slug' => 'weight',
        'type' => 'decimal',
        'name' => 'Weight(g)',
        'entities' => ['App\Product'],
        'sort_order' => 5,
        'is_required' => false,
        'is_system' => true,
        'field_type' => 'Decimal',
        'description' => 'Enter weight in grams'
      ]);
      $mrp = \App\Attribute::create([
        'slug' => 'mrp',
        'type' => 'decimal',
        'name' => 'MRP',
        'entities' => ['App\Product'],
        'sort_order' => 6,
        'is_system' => true,
        'field_type' => 'Decimal',
      ]);
      $gst = \App\Attribute::create([
        'slug' => 'gst',
        'type' => 'varchar',
        'name' => 'GST',
        'is_required' => true,
        'entities' => ['App\Product'],
        'sort_order' => 7,
        'is_system' => true,
        'field_type' => 'Selection',
      ]);
      $gsp_customer = \App\Attribute::create([
        'slug' => 'gsp_customer',
        'type' => 'decimal',
        'name' => 'GSP Customer',
        'entities' => ['App\Product'],
        'sort_order' => 8,
        'is_system' => true,
        'description' => 'General Selling Price Customer',
        'field_type' => 'Decimal',
      ]);
      $gsp_dealer = \App\Attribute::create([
        'slug' => 'gsp_dealer',
        'type' => 'decimal',
        'name' => 'GSP Dealer',
        'entities' => ['App\Product'],
        'sort_order' => 9,
        'is_system' => true,
        'description' => 'General Selling Price Dealer',
        'field_type' => 'Decimal',
      ]);
      $product_type = \App\Attribute::create([
        'slug' => 'product_type',
        'type' => 'varchar',
        'name' => 'Type',
        'entities' => ['App\Product'],
        'is_required' => true,
        'sort_order' => 10,
        'is_system' => true,
        'field_type' => 'Selection',
        'is_collection' => true,
      ]);
      $department = \App\Attribute::create([
        'slug' => 'department',
        'type' => 'varchar',
        'name' => 'Department',
        'entities' => ['App\Product'],
        'is_required' => true,
        'sort_order' => 11,
        'is_system' => true,
        'field_type' => 'Selection',
        'is_collection' => true,
      ]);
      $category = \App\Attribute::create([
        'slug' => 'category',
        'type' => 'varchar',
        'name' => 'Category',
        'entities' => ['App\Product'],
        'is_required' => true,
        'sort_order' => 12,
        'is_system' => true,
        'field_type' => 'Selection',
        'is_collection' => true,
      ]);
      $sub_category = \App\Attribute::create([
        'slug' => 'sub_category',
        'type' => 'varchar',
        'name' => 'Sub Category',
        'entities' => ['App\Product'],
        'is_required' => true,
        'sort_order' => 13,
        'is_system' => true,
        'field_type' => 'Selection',
        'is_collection' => true,
      ]);
      $brand = \App\Attribute::create([
        'slug' => 'brand',
        'type' => 'varchar',
        'name' => 'Brand',
        'entities' => ['App\Product'],
        'is_required' => true,
        'sort_order' => 14,
        'is_system' => true,
        'field_type' => 'Selection',
        'is_collection' => true,
      ]);
      $expirable = \App\Attribute::create([
        'slug' => 'expirable',
        'type' => 'boolean',
        'name' => 'Expirable ?',
        'entities' => ['App\Product'],
        'sort_order' => 16,
        'is_required' => true,
        'is_system' => true,
        'field_type' => 'Boolean',
      ]);
      $set = \App\ProductAttributeSet::create([
          'name' => 'Default',
          'is_system' => true
      ]);
      $set->attributes()->attach([
          $enabled->id => ['sort_order' => 0, 'group' => 'Basic'],
          $name->id => ['sort_order' => 1, 'group' => 'Basic'],
          $alias->id => ['sort_order' => 2, 'group' => 'Basic'],
          $sku->id => ['sort_order' => 3, 'group' => 'Basic'],
          $hsn->id => ['sort_order' => 4, 'group' => 'Basic'],
          $reorder_code->id => ['sort_order' => 5, 'group' => 'Basic'],
          $weight->id => ['sort_order' => 6, 'group' => 'Basic'],
          $expirable->id => ['sort_order' => 7, 'group' => 'Basic'],
          $mrp->id => ['sort_order' => 8, 'group' => 'Price'],
          $gst->id => ['sort_order' => 9, 'group' => 'Price'],
          $gsp_customer->id => ['sort_order' => 10, 'group' => 'Price'],
          $gsp_dealer->id => ['sort_order' => 11, 'group' => 'Price'],
          $product_type->id => ['sort_order' => 12, 'group' => 'Categorization'],
          $department->id => ['sort_order' => 13, 'group' => 'Categorization'],
          $category->id => ['sort_order' => 14, 'group' => 'Categorization'],
          $sub_category->id => ['sort_order' => 15, 'group' => 'Categorization'],
          $brand->id => ['sort_order' => 16, 'group' => 'Categorization'],
      ]);
    }
}

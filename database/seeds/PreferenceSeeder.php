<?php

use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Preference::firstOrCreate([
          'label' => 'Users Index Visible Columns',
          'slug' => 'users_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Users Index Pagination',
          'slug' => 'users_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Users Roles Index Visible Columns',
          'slug' => 'user_roles_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'User Roles Index Pagination',
          'slug' => 'user_roles_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Products Index Visible Columns',
          'slug' => 'products_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Products Index Pagination',
          'slug' => 'products_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Products Index Search',
          'slug' => 'products_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Products Index Auto Search',
          'slug' => 'products_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Products Index Filtered',
          'slug' => 'products_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Customers Index Visible Columns',
          'slug' => 'customers_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Customers Index Pagination',
          'slug' => 'customers_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Customers Index Search',
          'slug' => 'customers_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Customers Index Auto Search',
          'slug' => 'customers_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Customers Index Filtered',
          'slug' => 'customers_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Leads Index Visible Columns',
          'slug' => 'leads_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Leads Index Pagination',
          'slug' => 'leads_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Leads Index Search',
          'slug' => 'leads_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Leads Index Auto Search',
          'slug' => 'leads_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Leads Index Filtered',
          'slug' => 'leads_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders  Index Visible Columns',
          'slug' => 'sale_orders_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders  Index Pagination',
          'slug' => 'sale_orders_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders  Index Search',
          'slug' => 'sale_orders_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders  Index Auto Search',
          'slug' => 'sale_orders_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Index Filtered',
          'slug' => 'sale_orders_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Shipments Index Visible Columns',
          'slug' => 'shipments_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Shipments Index Pagination',
          'slug' => 'shipments_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Shipments Index Search',
          'slug' => 'shipments_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Shipments Index Auto Search',
          'slug' => 'shipments_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Shipments Index Filtered',
          'slug' => 'shipments_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Revisit Index Visible Columns',
          'slug' => 'sale_orders_revisit_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Revisit Index Pagination',
          'slug' => 'sale_orders_revisit_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Revisit Index Search',
          'slug' => 'sale_orders_revisit_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Revisit Index Auto Search',
          'slug' => 'sale_orders_revisit_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Orders Revisit Index Filtered',
          'slug' => 'sale_orders_revisit_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Quotations Index Visible Columns',
          'slug' => 'quotations_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Quotations Index Pagination',
          'slug' => 'quotations_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Quotations Index Search',
          'slug' => 'quotations_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Quotations Index Auto Search',
          'slug' => 'quotations_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Quotations Index Filtered',
          'slug' => 'quotations_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Suppliers Index Visible Columns',
          'slug' => 'suppliers_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Suppliers Index Pagination',
          'slug' => 'suppliers_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Suppliers Index Search',
          'slug' => 'suppliers_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Suppliers Index Auto Search',
          'slug' => 'suppliers_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Suppliers Index Filtered',
          'slug' => 'suppliers_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Purchases Index Visible Columns',
          'slug' => 'purchases_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchases Index Pagination',
          'slug' => 'purchases_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchases Index Search',
          'slug' => 'purchases_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchases Index Auto Search',
          'slug' => 'purchases_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchases Index Filtered',
          'slug' => 'purchases_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Pricelists Index Visible Columns',
          'slug' => 'pricelists_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Pricelists Index Pagination',
          'slug' => 'pricelists_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
          
        \App\Preference::firstOrCreate([
          'label' => 'Warehouses Index Visible Columns',
          'slug' => 'warehouses_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Warehouses Index Pagination',
          'slug' => 'warehouses_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Phones Index Visible Columns',
          'slug' => 'phones_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Phones Index Pagination',
          'slug' => 'phones_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Phones Index Search',
          'slug' => 'phones_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Phones Index Auto Search',
          'slug' => 'phones_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Phones Index Filtered',
          'slug' => 'phones_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Emails Index Visible Columns',
          'slug' => 'emails_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Emails Index Pagination',
          'slug' => 'emails_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Emails Index Search',
          'slug' => 'emails_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Emails Index Auto Search',
          'slug' => 'emails_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Emails Index Filtered',
          'slug' => 'emails_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
          
        \App\Preference::firstOrCreate([
          'label' => 'Product Types Index Visible Columns',
          'slug' => 'product_types_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Types Index Pagination',
          'slug' => 'product_types_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Product Departments Index Visible Columns',
          'slug' => 'product_departments_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Departments Index Pagination',
          'slug' => 'product_departments_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Product Categories Index Visible Columns',
          'slug' => 'product_categories_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Categories Index Pagination',
          'slug' => 'product_categories_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Product Sub Categories Index Visible Columns',
          'slug' => 'product_sub_categories_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Sub Categories Index Pagination',
          'slug' => 'product_sub_categories_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Product Brands Index Visible Columns',
          'slug' => 'product_brands_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Brands Index Pagination',
          'slug' => 'product_brands_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Logistic Partners Index Visible Columns',
          'slug' => 'logistic_partners_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Logistic Partners Index Pagination',
          'slug' => 'logistic_partners_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Returns  Index Visible Columns',
          'slug' => 'sale_returns_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Returns  Index Pagination',
          'slug' => 'sale_returns_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Returns  Index Search',
          'slug' => 'sale_returns_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Returns  Index Auto Search',
          'slug' => 'sale_returns_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Sale Returns Index Filtered',
          'slug' => 'sale_returns_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Goods Receive Notes Index Filtered',
          'slug' => 'goods_receive_notes_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Goods Receive Notes Index Search',
          'slug' => 'goods_receive_notes_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Goods Receive Notes Index Pagination',
          'slug' => 'goods_receive_notes_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Goods Receive Notes Index Auto Search',
          'slug' => 'goods_receive_notes_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Dentodeal Orders Index Visible Columns',
          'slug' => 'dentodeal_orders_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Dentodeal Orders Index Pagination',
          'slug' => 'dentodeal_orders_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'SDentodeal Orders Index Search',
          'slug' => 'dentodeal_orders_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Dentodeal Orders Index Auto Search',
          'slug' => 'dentodeal_orders_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'SDentodeal Orders Index Filtered',
          'slug' => 'dentodeal_orders_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Product Bundles Index Visible Columns',
          'slug' => 'product_bundles_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Bundles Index Pagination',
          'slug' => 'product_bundles_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Bundles Index Search',
          'slug' => 'product_bundles_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Bundles Index Auto Search',
          'slug' => 'product_bundles_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Product Bundles Index Filtered',
          'slug' => 'product_bundles_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);

        \App\Preference::firstOrCreate([
          'label' => 'Purchase Returns  Index Visible Columns',
          'slug' => 'purchase_returns_index_visible_columns',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchase Returns  Index Pagination',
          'slug' => 'purchase_returns_index_pagination',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchase Returns  Index Search',
          'slug' => 'purchase_returns_index_search',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchase Returns  Index Auto Search',
          'slug' => 'purchase_returns_index_autosearch',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
        \App\Preference::firstOrCreate([
          'label' => 'Purchase Returns Index Filtered',
          'slug' => 'purchase_returns_index_filtered',
          'visible_on_frontend' => false,
          'type' => 'page_preference',
        ]);
    }
}

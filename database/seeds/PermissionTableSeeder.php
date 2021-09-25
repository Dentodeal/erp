<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::firstOrCreate(['name'=>'create_user','guard_name'=>'api','model'=>'User','model_slug'=>'user']);
        Permission::firstOrCreate(['name'=>'update_user','guard_name'=>'api','model'=>'User','model_slug'=>'user']);
        Permission::firstOrCreate(['name'=>'delete_user','guard_name'=>'api','model'=>'User','model_slug'=>'user']);
        Permission::firstOrCreate(['name'=>'view_user','guard_name'=>'api','model'=>'User','model_slug'=>'user']);
        Permission::firstOrCreate(['name'=>'viewAny_user','guard_name'=>'api','model'=>'User','model_slug'=>'user']);

        Permission::firstOrCreate(['name'=>'create_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'update_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'delete_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'view_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'viewAny_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'approve_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'activate_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'revert_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);
        Permission::firstOrCreate(['name'=>'export_product','guard_name'=>'api','model'=>'Product','model_slug'=>'product']);

        Permission::firstOrCreate(['name'=>'create_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'update_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'delete_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'view_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'viewAny_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'approve_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'activate_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'revert_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);
        Permission::firstOrCreate(['name'=>'export_customer','guard_name'=>'api','model'=>'Customer','model_slug'=>'customer']);

        Permission::firstOrCreate(['name'=>'create_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);
        Permission::firstOrCreate(['name'=>'update_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);
        Permission::firstOrCreate(['name'=>'delete_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);
        Permission::firstOrCreate(['name'=>'view_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);
        Permission::firstOrCreate(['name'=>'viewAny_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);
        Permission::firstOrCreate(['name'=>'export_lead','guard_name'=>'api','model'=>'Lead','model_slug'=>'lead']);

        Permission::firstOrCreate(['name'=>'create_warehouse','guard_name'=>'api','model'=>'Warehouse','model_slug'=>'warehouse']);
        Permission::firstOrCreate(['name'=>'update_warehouse','guard_name'=>'api','model'=>'Warehouse','model_slug'=>'warehouse']);
        Permission::firstOrCreate(['name'=>'delete_warehouse','guard_name'=>'api','model'=>'Warehouse','model_slug'=>'warehouse']);
        Permission::firstOrCreate(['name'=>'view_warehouse','guard_name'=>'api','model'=>'Warehouse','model_slug'=>'warehouse']);
        Permission::firstOrCreate(['name'=>'viewAny_warehouse','guard_name'=>'api','model'=>'Warehouse','model_slug'=>'warehouse']);

        Permission::firstOrCreate(['name'=>'create_pricelist','guard_name'=>'api','model'=>'Pricelist','model_slug'=>'pricelist']);
        Permission::firstOrCreate(['name'=>'update_pricelist','guard_name'=>'api','model'=>'Pricelist','model_slug'=>'pricelist']);
        Permission::firstOrCreate(['name'=>'delete_pricelist','guard_name'=>'api','model'=>'Pricelist','model_slug'=>'pricelist']);
        Permission::firstOrCreate(['name'=>'view_pricelist','guard_name'=>'api','model'=>'Pricelist','model_slug'=>'pricelist']);
        Permission::firstOrCreate(['name'=>'viewAny_pricelist','guard_name'=>'api','model'=>'Pricelist','model_slug'=>'pricelist']);

        Permission::firstOrCreate(['name'=>'create_employee','guard_name'=>'api','model'=>'Employee','model_slug'=>'employee']);
        Permission::firstOrCreate(['name'=>'update_employee','guard_name'=>'api','model'=>'Employee','model_slug'=>'employee']);
        Permission::firstOrCreate(['name'=>'delete_employee','guard_name'=>'api','model'=>'Employee','model_slug'=>'employee']);
        Permission::firstOrCreate(['name'=>'view_employee','guard_name'=>'api','model'=>'Employee','model_slug'=>'employee']);
        Permission::firstOrCreate(['name'=>'viewAny_employee','guard_name'=>'api','model'=>'Employee','model_slug'=>'employee']);

        Permission::firstOrCreate(['name'=>'cancel_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'create_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'update_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'delete_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'view_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'viewAny_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'send_to_accounts_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'invoice_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'approve_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'reject_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'request_approval_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'ship_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'revert_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'export_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);

        Permission::firstOrCreate(['name'=>'create_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);
        Permission::firstOrCreate(['name'=>'update_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);
        Permission::firstOrCreate(['name'=>'delete_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);
        Permission::firstOrCreate(['name'=>'view_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);
        Permission::firstOrCreate(['name'=>'viewAny_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);
        Permission::firstOrCreate(['name'=>'export_quotation','guard_name'=>'api','model'=>'Quotation','model_slug'=>'quotation']);

        Permission::firstOrCreate(['name'=>'create_user_role','guard_name'=>'api','model'=>'User Role','model_slug'=>'user_role']);
        Permission::firstOrCreate(['name'=>'update_user_role','guard_name'=>'api','model'=>'User Role','model_slug'=>'user_role']);
        Permission::firstOrCreate(['name'=>'delete_user_role','guard_name'=>'api','model'=>'User Role','model_slug'=>'user_role']);
        Permission::firstOrCreate(['name'=>'view_user_role','guard_name'=>'api','model'=>'User Role','model_slug'=>'user_role']);
        Permission::firstOrCreate(['name'=>'viewAny_user_role','guard_name'=>'api','model'=>'User Role','model_slug'=>'user_role']);

        Permission::firstOrCreate(['name'=>'create_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'update_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'delete_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'view_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'viewAny_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'approve_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'activate_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);
        Permission::firstOrCreate(['name'=>'revert_purchase','guard_name'=>'api','model'=>'Purchase','model_slug'=>'purchase']);

        Permission::firstOrCreate(['name'=>'create_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'update_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'delete_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'view_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'viewAny_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'approve_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);
        Permission::firstOrCreate(['name'=>'tally_supplier','guard_name'=>'api','model'=>'Supplier','model_slug'=>'supplier']);

        Permission::firstOrCreate(['name'=>'view_product_pricing','guard_name'=>'api','model'=>'Special Permission','model_slug'=>'special_permission']);
        Permission::firstOrCreate(['name'=>'access_inventory','guard_name'=>'api','model'=>'Special Permission','model_slug'=>'special_permission']);

        Permission::firstOrCreate(['name'=>'create_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'update_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'delete_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'view_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'viewAny_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'import_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);
        Permission::firstOrCreate(['name'=>'export_phone','guard_name'=>'api','model'=>'Phone','model_slug'=>'phone']);

        Permission::firstOrCreate(['name'=>'create_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'update_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'delete_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'view_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'viewAny_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'import_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);
        Permission::firstOrCreate(['name'=>'export_email','guard_name'=>'api','model'=>'Email','model_slug'=>'email']);

        Permission::firstOrCreate(['name'=>'create_logistic_partner','guard_name'=>'api','model'=>'Logistic Partner','model_slug'=>'logistic_partner']);
        Permission::firstOrCreate(['name'=>'update_logistic_partner','guard_name'=>'api','model'=>'Logistic Partner','model_slug'=>'logistic_partner']);
        Permission::firstOrCreate(['name'=>'view_logistic_partner','guard_name'=>'api','model'=>'Logistic Partner','model_slug'=>'logistic_partner']);
        Permission::firstOrCreate(['name'=>'viewAny_logistic_partner','guard_name'=>'api','model'=>'Logistic Partner','model_slug'=>'logistic_partner']);
        Permission::firstOrCreate(['name'=>'delete_logistic_partner','guard_name'=>'api','model'=>'Logistic Partner','model_slug'=>'logistic_partner']);

        Permission::firstOrCreate(['name'=>'update_shipment','guard_name'=>'api','model'=>'Shipment','model_slug'=>'shipment']);
        Permission::firstOrCreate(['name'=>'view_shipment','guard_name'=>'api','model'=>'Shipment','model_slug'=>'shipment']);
        Permission::firstOrCreate(['name'=>'viewAny_shipment','guard_name'=>'api','model'=>'Shipment','model_slug'=>'shipment']);
        Permission::firstOrCreate(['name'=>'confirm_sale_order','guard_name'=>'api','model'=>'Sale Order','model_slug'=>'sale_order']);
        Permission::firstOrCreate(['name'=>'create_sale_return','guard_name'=>'api','model'=>'Sale Return','model_slug'=>'sale_return']);
        Permission::firstOrCreate(['name'=>'edit_sale_return','guard_name'=>'api','model'=>'Sale Return','model_slug'=>'sale_return']);
        Permission::firstOrCreate(['name'=>'delete_sale_return','guard_name'=>'api','model'=>'Sale Return','model_slug'=>'sale_return']);
        Permission::firstOrCreate(['name'=>'view_sale_return','guard_name'=>'api','model'=>'Sale Return','model_slug'=>'sale_return']);

        Permission::firstOrCreate(['name'=>'create_purchase_return','guard_name'=>'api','model'=>'Purchase Return','model_slug'=>'purchase_return']);
    }
    
}

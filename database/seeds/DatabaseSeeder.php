<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        /*
        $this->call(UsersTableSeeder::class);
        $this->call(PreferenceSeeder::class);
        //$this->call(ProductAttributeSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(PricelistSeeder::class);
        
        DB::unprepared(Storage::get('districts.sql'));
        $this->command->info('Districts table seeded!');
        DB::unprepared(Storage::get('states.sql'));
        $this->command->info('States table seeded!');
        DB::unprepared(Storage::get('countries.sql'));
        $this->command->info('Coutries table seeded!');
        
        //DB::unprepared(Storage::get('attribute_options.sql'));
        //$this->command->info('Attribute Options table seeded!');
        DB::unprepared(Storage::get('addresses.sql'));
        $this->command->info('Addresses table seeded!');
        
        DB::unprepared(Storage::get('attribute_boolean_values.sql'));
        $this->command->info('Attribute Boolean Values table seeded!');
        DB::unprepared(Storage::get('attribute_decimal_values.sql'));
        $this->command->info('Attribute Decimal Values table seeded!');
        DB::unprepared(Storage::get('attribute_varchar_values.sql'));
        $this->command->info('Attribute Varchar Values table seeded!');
        
        
        DB::unprepared(Storage::get('customer_representative.sql'));
        $this->command->info('Customer Representative table seeded!');
        DB::unprepared(Storage::get('customers.sql'));
        $this->command->info('Customers table seeded!');
        DB::unprepared(Storage::get('emailables.sql'));
        $this->command->info('Emailables table seeded!');
        DB::unprepared(Storage::get('emails.sql'));
        $this->command->info('Emails table seeded!');
        DB::unprepared(Storage::get('phoneables.sql'));
        $this->command->info('Phoneables table seeded!');
        DB::unprepared(Storage::get('phones.sql'));
        $this->command->info('Phones table seeded!');
    
        DB::unprepared(Storage::get('product_cost.sql'));
        $this->command->info('Product Cost table seeded!');
    

        DB::unprepared(Storage::get('products.sql'));
        $this->command->info('Products table seeded!');
        
        DB::unprepared(Storage::get('taxonomy.sql'));
        $this->command->info('Taxonomy tables seeded!');

        DB::unprepared(Storage::get('purchases.sql'));
        $this->command->info('Purchases table seeded!');
        DB::unprepared(Storage::get('purchase_items.sql'));
        $this->command->info('Purchase Items table seeded!');
        DB::unprepared(Storage::get('suppliers.sql'));
        $this->command->info('Suppliers table seeded!');
        DB::unprepared(Storage::get('taggables.sql'));
        $this->command->info('Taggables table seeded!');
        DB::unprepared(Storage::get('tags.sql'));
        $this->command->info('Tags table seeded!');
        
        DB::unprepared(Storage::get('users.sql'));
        $this->command->info('Users table seeded!');
        
        DB::unprepared(Storage::get('sale_orders.sql'));
        $this->command->info('Sale Orders table seeded!');
        DB::unprepared(Storage::get('sale_order_items.sql'));
        $this->command->info('Sale Order Items table seeded!');
        */

    }
}

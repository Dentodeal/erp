<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class migrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ==================== Products ===============================

        $this->info('Processing Products');
        DB::connection('olddb')->table('products')->whereNull('dentodeal_bundle_qty')->update(['dentodeal_bundle_qty' => 0]);
        $oldProducts = DB::connection('olddb')->table('products')->get();
        $bar = $this->output->createProgressBar(count($oldProducts));
        foreach($oldProducts as $product) {
            \App\Product::firstOrCreate((array)$product);
            $bar->advance();
        }
        $bar->finish();
        $this->line("\n".'Processing Products: Done'."\n");

        // ==================== Customers ===============================

         $this->info('Processing Customers');
         $oldCustomers = DB::connection('olddb')->table('customers')->get();
         $bar = $this->output->createProgressBar(count($oldCustomers));
         foreach($oldCustomers as $customer) {
             \App\Customer::firstOrCreate((array)$customer);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Customers: Done'."\n");

         // ==================== Addresses ===============================

         $this->info('Processing Addresses');
         $oldAddresses = DB::connection('olddb')->table('addresses')->get();
         $bar = $this->output->createProgressBar(count($oldAddresses));
         foreach($oldAddresses as $address) {
             \App\Address::firstOrCreate((array)$address);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Addresses: Done'."\n");

        // ==================== Countries ===============================

         $this->info('Processing Countries');
         $oldCountries = DB::connection('olddb')->table('countries')->get();
         $bar = $this->output->createProgressBar(count($oldCountries));
         foreach($oldCountries as $country) {
             \App\Country::firstOrCreate((array)$country);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Countries: Done'."\n");

         // ==================== States ===============================

         $this->info('Processing States');
         $oldModels = DB::connection('olddb')->table('states')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             \App\State::firstOrCreate((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing States: Done'."\n");

         // ==================== Districts ===============================

         $this->info('Processing States');
         $oldModels = DB::connection('olddb')->table('districts')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             \App\District::firstOrCreate((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Districts: Done'."\n");

         // ==================== Customer Relations ===============================

         $this->info('Processing Customer Relations');
         $oldModels = DB::connection('olddb')->table('customer_relations')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             DB::table('customer_relations')->insertOrIgnore((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Customer Relations: Done'."\n");

         // ==================== Customer Representative ===============================

         $this->info('Processing Customer Representative');
         $oldModels = DB::connection('olddb')->table('customer_representative')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             DB::table('customer_representative')->insertOrIgnore((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Customer Representative: Done'."\n");

         // ==================== Emailables ===============================

         $this->info('Processing Emailables');
         $oldModels = DB::connection('olddb')->table('emailables')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             DB::table('emailables')->insertOrIgnore((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Emailables: Done'."\n");

         // ==================== Emails ===============================

         $this->info('Processing Emailables');
         $oldModels = DB::connection('olddb')->table('emails')->get();
         $bar = $this->output->createProgressBar(count($oldModels));
         foreach($oldModels as $model) {
             DB::table('emails')->insertOrIgnore((array)$model);
             $bar->advance();
         }
         $bar->finish();
         $this->line("\n".'Processing Emails: Done'."\n");

        // ==================== Images ===============================

        $this->info('Processing Images');
        $oldModels = DB::connection('olddb')->table('images')->get();
        $bar = $this->output->createProgressBar(count($oldModels));
        foreach($oldModels as $model) {
            DB::table('images')->insertOrIgnore((array)$model);
            $bar->advance();
        }
        $bar->finish();
        $this->line("\n".'Processing Images: Done'."\n");

        // ==================== Logistic Partners ===============================

        $this->info('Processing Images');
        $oldModels = DB::connection('olddb')->table('logistic_partners')->get();
        $bar = $this->output->createProgressBar(count($oldModels));
        foreach($oldModels as $model) {
            DB::table('logistic_partners')->insertOrIgnore((array)$model);
            $bar->advance();
        }
        $bar->finish();
        $this->line("\n".'Processing Logistic Partners: Done'."\n");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class StockFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:fix';

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
        $products = \App\Product::where('expirable',0)->get();
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();
        foreach($products as $product) {
            $initialStock = (int)$product->stock_entry_items()->sum('qty');
            $sold = (int)$product->sale_items()->wherehas('sale_order', function(Builder $query) {
                $query->where('status','<>','Cancelled')->where('created_at','>','2021-05-25 00:00:00');
            })->sum('qty');
            $returned = (int)$product->sale_return_items()->whereHas('sale_return', function(Builder $query) {
                $query->where('status','<>','Draft')->where('created_at','>','2021-05-25 00:00:00');
            })->sum('qty');
            $purchased = (int)$product->grn_items()->whereHas('grn', function(Builder $query) {
                $query->where('status','Active')->where('created_at','>','2021-05-25 00:00:00');
            })->sum('qty');
            $availableStock = (int)$product->availableStock(1);
            $expectedStock = (int)$initialStock + $purchased + $returned - $sold;
            if($expectedStock !== $availableStock) {
                \App\ProductStock::where([
                    ['product_id','=',$product->id],
                    ['reservable_id','=',0],
                    ['warehouse_id','=',1]
                ])->update([
                    'qty' => $expectedStock
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}

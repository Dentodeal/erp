<?php

namespace App\Jobs;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class updateDentodealStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $qty;
    protected $sku;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($qty,$sku)
    {
        $this->qty = $qty;
        $this->sku = $sku;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->qty !== NULL && $this->sku !== NULL) {
            $payload = [
                'qty' => $this->qty <= 0 ? 0 : (int)$this->qty,
                'is_in_stock' => $this->qty <= 0 ? false : true
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer lqh33et799lap0goy4vkzo2gmbflg75z',
                'Content-Type' => 'application/json',
                'Accept' => '*/*'
            ])->put('https://dentodeal.com/rest/V1/products/'.$this->sku.'/stockItems/1',[
                'stock_item' => $payload
            ]);

            if($response->successful()){
                Log::info('Dentodeal Stock Updated for SKU: '.$this->sku.', Qty: '.$this->qty);
            }
            if($response->clientError() || $response->serverError()) {
                Log::error('Error occured while updating dentodeal Stock for SKU: '.$this->sku.', Qty: '.$this->qty, $response->body());
            }
        }
    }
}

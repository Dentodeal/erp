<?php

use Illuminate\Database\Seeder;
use \App\NotificationType;
class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationType::firstOrCreate([
            'name' => 'Sale Order Draft Alert',
            'slug' => 'sale_order_draft_alert',
            'description' => 'Generated when sale orders in Draft status gets untouched after a pre-defined number of days'
        ]);

        NotificationType::firstOrCreate([
            'name' => 'Sale Order Payment Overdue',
            'slug' => 'sale_order_payment_overdue',
            'description' => 'Generated when unsettled sale orders\' payment not settled after a pre-defined number of days'
        ]);

        NotificationType::firstOrCreate([
            'name' => 'Product Reorder Point Reached',
            'slug' => 'product_reorder_point_reached',
            'description' => 'Generated when a product\'s reorder point is reached'
        ]);
    }
}

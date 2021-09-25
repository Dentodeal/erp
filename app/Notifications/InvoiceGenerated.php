<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \App\SaleOrder;

class InvoiceGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $saleOrder;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SaleOrder $saleOrder)
    {
        $this->saleOrder = $saleOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'action' => 'invoiced',
            'message' => 'Invoice Generated',
            'sale_order_id' => $this->saleOrder->id
        ];
    }
}

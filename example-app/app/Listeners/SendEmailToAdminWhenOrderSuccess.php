<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use App\Mail\OrderAdminEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToAdminWhenOrderSuccess
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderSuccessEvent $event): void
    {
        $order = $event->order;

        Mail::to('huyduocphamm@gmail.com')->send(new OrderAdminEmail($order));
    }
}

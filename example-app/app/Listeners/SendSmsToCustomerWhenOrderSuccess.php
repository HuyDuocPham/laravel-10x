<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;

class SendSmsToCustomerWhenOrderSuccess
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

        $receiverNumber = '+84352405575';
        $client = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $client->messages->create($receiverNumber, [
            'from' => env('TWILIO_PHONE_NUMBER'),
            'body' => 'Your order is success'
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use App\Http\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsToCustomerWhenOrderSuccess
{
    private $smsService;
    /**
     * Create the event listener.
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderSuccessEvent $event): void
    {
        $this->smsService->send('+84366321516', 'Your order is success');
    }
}

<?php

namespace App\Listeners;

use App\Models\BalanceHistory;
use App\Events\BalanceHistoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BalanceHistoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BalanceHistoryEvent  $event
     * @return void
     */
    public function handle(BalanceHistoryEvent $event)
    {
        $balance = new BalanceHistory();
        $balance->type = $event->data['type'];
        $balance->balance = $event->data['price'];
        $balance->user_id = auth()->user()->id;

        $balance->save() === false;
    }
}

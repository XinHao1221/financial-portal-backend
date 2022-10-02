<?php

namespace App\Listeners;

use App\Models\Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SeedDefaultAccountData
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Default Accounts
        $accounts = [
            'Bank',
            'Cash'
        ];

        $user = $event->user;

        $accountData = [];

        foreach($accounts as $account)
        {
            array_push($accountData, [
                'name' => $account,
                'user_id' => $user->id
            ]);
        }

        Account::insert($accountData);
    }
}

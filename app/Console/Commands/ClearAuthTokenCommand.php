<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearAuthTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear Scantum token that didn't use within 1 month";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Substract 30 days from now
        $minDate = Carbon::now('UTC')->subDays(10);

        // Remove token which didn't use within 30 days
        DB::table('personal_access_tokens')
            ->where([['last_used_at', '<', $minDate]])
            ->orWhere([['created_at', '<', $minDate], ['last_used_at', null]])
            ->delete();
    }
}

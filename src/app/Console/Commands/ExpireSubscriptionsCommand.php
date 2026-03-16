<?php

namespace App\Console\Commands;

use App\Models\Business;
use Illuminate\Console\Command;

class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Set business status to pending when they have no active subscription (e.g. subscription ended).';

    public function handle(): int
    {
        $businesses = Business::where('status', 'published')->get();
        $count = 0;

        foreach ($businesses as $business) {
            if (! $business->hasActiveSubscription()) {
                $business->update(['status' => 'pending']);
                $count++;
                $this->info("Business \"{$business->name}\" (id: {$business->id}) set to pending – no active subscription.");
            }
        }

        if ($count === 0) {
            $this->info('No businesses needed updating.');
        } else {
            $this->info("Updated {$count} business(es) to pending.");
        }

        return self::SUCCESS;
    }
}

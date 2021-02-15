<?php

declare(strict_types=1);

namespace Tipoff\Payments\Commands;

use Illuminate\Console\Command;

class PaymentsCommand extends Command
{
    public $signature = 'payments';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}

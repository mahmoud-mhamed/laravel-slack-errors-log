<?php

namespace Mahmoudmhamed\LaravelSlackErrorsLog\Commands;

use Illuminate\Console\Command;

class LaravelSlackErrorsLogCommand extends Command
{
    public $signature = 'laravel-slack-errors-log';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

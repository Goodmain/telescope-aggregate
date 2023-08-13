<?php

namespace Goodmain\TelescopeStatistics\Console\Commands;

use Goodmain\TelescopeStatistics\Collectors\Collector;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class TelescopeAggregate extends Command
{
    protected $signature = 'telescope:aggregate {period : hour, day, week, month or year}';

    protected $description = 'Aggregate telescope data by period';

    public function handle(): void
    {
        $period = $this->argument('period');

        if (!in_array($period, Collector::PERIODS)) {
            $this->error('Incorrect period value.');
            return;
        }

        $to = Carbon::now()
            ->startOfMinute()
            ->startOfHour();
        $from = Carbon::now()
            ->startOfMinute()
            ->startOfHour()
            ->sub("1 {$period}");

        $collectors = config('telescope-statistics.collectors');

        ProgressBar::setFormatDefinition(
            'custom',
            " %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%"
        );

        $bar = $this->output->createProgressBar(count($collectors));
        $bar->setFormat('custom');
        $bar->minSecondsBetweenRedraws(0.01);
        $bar->setMessage('Starts collecting data');
        $bar->start();

        foreach ($collectors as $collector => $options) {
            $bar->setMessage("Collecting data with {$collector} for {$period}.");

            (new $collector($this, [
                ...$options,
                'from' => $from,
                'to' => $to,
                'period' => $period
            ]))->collect();

            $bar->advance();
        }
        $bar->setMessage('Collecting data is finished');
        $bar->finish();
        $this->newLine();
    }
}
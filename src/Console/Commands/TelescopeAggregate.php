<?php

namespace Goodmain\TelescopeAggregate\Console\Commands;

use Goodmain\TelescopeAggregate\Collectors\Collector;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class TelescopeAggregate extends Command
{
    protected $signature = 'telescope:aggregate {period : hour, day, week, month or year}';

    protected $description = 'Aggregate telescope data by the period';

    protected Progressbar $bar;

    public function handle(): void
    {
        $period = $this->argument('period');

        if (!in_array($period, Collector::PERIODS)) {
            $this->error(
                "Incorrect value `{$period}` for the period. Please use on of the following: "
                . implode(', ', Collector::PERIODS)
            );
            return;
        }

        $to = Carbon::now()
            ->startOfMinute()
            ->startOfHour();
        $from = Carbon::now()
            ->startOfMinute()
            ->startOfHour()
            ->sub("1 {$period}");

        $collectors = config('telescope-aggregate.collectors');
        $this->prepareProgressBar(count($collectors));

        $this->bar->setMessage('Starts collecting data');
        $this->bar->start();

        foreach ($collectors as $collector => $options) {
            $this->bar->setMessage("Collecting data with {$collector} for {$period}");

            (new $collector($this, [
                ...$options,
                'from' => $from,
                'to' => $to,
                'period' => $period
            ]))->collect();

            $this->bar->advance();
        }
        $this->bar->setMessage('Collecting data is finished');
        $this->bar->finish();
        $this->newLine();
    }

    protected function prepareProgressBar(int $steps): void
    {
        ProgressBar::setFormatDefinition(
            'custom',
            " %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%"
        );

        $this->bar = $this->output->createProgressBar($steps);
        $this->bar->setFormat('custom');
        $this->bar->minSecondsBetweenRedraws(0.01);
    }
}
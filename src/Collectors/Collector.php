<?php

namespace Goodmain\TelescopeStatistics\Collectors;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

abstract class Collector
{
    public const PERIOD_HOUR = 'hour';
    public const PERIOD_DAY = 'day';
    public const PERIOD_WEEK = 'week';
    public const PERIOD_MONTH = 'month';
    public const PERIOD_YEAR = 'year';

    public const PERIODS = [
        self::PERIOD_HOUR,
        self::PERIOD_DAY,
        self::PERIOD_WEEK,
        self::PERIOD_MONTH,
        self::PERIOD_YEAR,
    ];

    public const SOURCE_TELESCOPE = 'telescope';
    public const SOURCE_STATISTICS = 'statistics';

    public const SOURCES = [
        self::SOURCE_TELESCOPE,
        self::SOURCE_STATISTICS,
    ];

    /**
     * The configured collector options.
     *
     * @var array
     */
    public array $options = [];

    /**
     * Start date of the aggregated period.
     *
     * @var Carbon
     */
    public Carbon $from;

    /**
     * End date of the aggregated period.
     *
     * @var Carbon
     */
    public Carbon $to;

    /**
     * Type of the period.
     *
     * @var string
     */
    public string $periodType;

    /**
     * Type of the collector.
     *
     * @var string
     */
    public string $type;

    /**
     * Command which called the collector.
     *
     * @var Command
     */
    public Command $command;


    /**
     * Create a new collector instance.
     *
     * @param  Command  $command
     * @param  array  $options
     * @return void
     */
    public function __construct(Command $command, array $options = [])
    {
        $this->options = $options;
        $this->command = $command;

        $this->from = Arr::get($options, 'from');
        $this->to = Arr::get($options, 'to');
        $this->periodType = Arr::get($options, 'period');
    }

    /**
     * Collect aggregated data.
     *
     * @return void
     */
    public function collect(): void
    {
        if (!$this->options['enabled']) {
            //$this->command->warn('Switched off. Skip!');

            return;
        }

        $source = config('telescope-statistics.sources.' . $this->periodType);

        if ($source === self::SOURCE_TELESCOPE) {
            $this->collectFromTelescope();
        } elseif ($source === self::SOURCE_STATISTICS) {
            $this->collectFromStatistics();
        }
    }

    /**
     * Collect aggregated data from telescope.
     *
     * @return void
     */
    abstract public function collectFromTelescope(): void;

    /**
     * Collect aggregated data from statistics.
     *
     * @return void
     */
    abstract public function collectFromStatistics(): void;

    protected function buildTelescopeQuery(string $columns): Builder
    {
        return DB::table('telescope_entries')
            ->selectRaw($columns)
            ->where('type', $this->type)
            ->where('created_at', '>=', $this->from)
            ->where('created_at', '<', $this->to);
    }

    protected function buildStatisticsQuery(string $columns): Builder
    {
        return DB::table('telescope_statistics')
            ->selectRaw($columns)
            ->where('type', $this->type)
            ->where('period_type', $this->getPreviousPeriodType())
            ->where('period', '>=', $this->from)
            ->where('period', '<', $this->to);
    }

    protected function save(array $content): void
    {
        $data = [
            'type' => $this->type,
            'period_type' => $this->periodType,
            'period' => $this->from->{'startOf' . $this->periodType}(),
            'content' => json_encode($content)
        ];

        if (DB::table('telescope_statistics')->insert($data)) {
            //$this->command->line('Done.');
        }
    }

    protected function getPreviousPeriodType(): string
    {
        $index = array_search($this->periodType, self::PERIODS);

        return $index
            ? self::PERIODS[$index - 1]
            : $this->periodType;
    }
}

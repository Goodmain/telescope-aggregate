<?php

namespace Goodmain\TelescopeAggregate\Collectors;

use Illuminate\Support\Collection;
use Laravel\Telescope\EntryType;

class QueryCollector extends Collector
{
    public string $type = EntryType::QUERY;

    public function collectFromTelescope(): void
    {
        $data = $this->buildTelescopeQuery(
            'COUNT(*) as "count",
            SUM((content->>\'time\') :: DECIMAL) as "time",
            SUM(CASE WHEN (content->>\'slow\') = \'true\' THEN 1 ELSE 0 END) as "slow_count",
            SUM(CASE WHEN (content->>\'slow\') = \'true\' THEN (content->>\'time\') :: DECIMAL ELSE 0 END) as "slow_time"'
        )->get();

        $this->saveData($data);
    }

    public function collectFromAggregate(): void
    {
        $data = $this->buildAggregateQuery(
            'COUNT(*) as "count",
            SUM((content->>\'time\') :: DECIMAL) as "time",
            SUM((content->>\'slow_count\') :: DECIMAL) as "slow_count",
            SUM((content->>\'slow_time\') :: DECIMAL) as "slow_time"'
        )->get();

        $this->saveData($data);
    }

    protected function saveData(Collection $data): void
    {
        $this->save([
            'count' => $data['count'] ?? 0,
            'time' => $data['time'] ?? 0,
            'slow_count' => $data['slow_count'] ?? 0,
            'slow_time' => $data['slow_time'] ?? 0,
        ]);
    }
}

<?php

namespace Goodmain\TelescopeStatistics\Collectors;

use Illuminate\Support\Collection;
use Laravel\Telescope\EntryType;

class RequestCollector extends Collector
{
    public string $type = EntryType::REQUEST;

    public function collectFromTelescope(): void
    {
        $data = $this->buildTelescopeQuery(
            'COUNT(*) as "count",
            SUM((content->>\'memory\') :: DECIMAL) as "memory",
            SUM((content->>\'duration\') :: DECIMAL) as "duration"',
        )->get();

        $this->saveData($data);
    }

    public function collectFromStatistics(): void
    {
        $data = $this->buildStatisticsQuery(
            'COUNT(*) as "count",
            SUM((content->>\'memory\') :: DECIMAL) as "memory",
            SUM((content->>\'duration\') :: DECIMAL) as "duration"'
        )->get();

        $this->saveData($data);
    }

    protected function saveData(Collection $data): void
    {
        $this->save([
            'count' => $data['count'] ?? 0,
            'memory' => $data['memory'] ?? 0,
            'duration' => $data['duration'] ?? 0,
        ]);
    }
}

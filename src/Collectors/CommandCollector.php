<?php

namespace Goodmain\TelescopeAggregate\Collectors;

use Illuminate\Support\Collection;
use Laravel\Telescope\EntryType;

class CommandCollector extends Collector
{
    public string $type = EntryType::COMMAND;

    public function collectFromTelescope(): void
    {
        $data = $this->buildTelescopeQuery(
            'COUNT(*) as "count",
            SUM(CASE WHEN ((content->>\'exit_code\') :: DECIMAL) <> 0 THEN 1 ELSE 0 END) as "error_count"',
        )->get();

        $this->saveData($data);
    }

    public function collectFromAggregate(): void
    {
        $data = $this->buildAggregateQuery(
            'COUNT(*) as "count",
            SUM(content->>\'error_count\') as "error_count"',
        )->get();

        $this->saveData($data);
    }

    protected function saveData(Collection $data): void
    {
        $this->save([
            'count' => $data['count'] ?? 0,
            'error_count' => $data['error_count'] ?? 0,
        ]);
    }
}

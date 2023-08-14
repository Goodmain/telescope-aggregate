<?php

namespace Goodmain\TelescopeAggregate\Collectors;

use Illuminate\Support\Collection;
use Laravel\Telescope\EntryType;

class ExceptionCollector extends Collector
{
    public string $type = EntryType::EXCEPTION;

    public function collectFromTelescope(): void
    {
        $data = $this->buildTelescopeQuery(
            'COUNT(*) as "count",
            SUM((content->>\'occurrences\') :: DECIMAL) as "occurrences"'
        )->get();

        $this->saveData($data);
    }

    public function collectFromAggregate(): void
    {
        $data = $this->buildAggregateQuery(
            'COUNT(*) as "count",
            SUM((content->>\'occurrences\') :: DECIMAL) as "occurrences"'
        )->get();

        $this->saveData($data);
    }

    protected function saveData(Collection $data): void
    {
        $this->save([
            'count' => $data['count'] ?? 0,
        ]);
    }
}

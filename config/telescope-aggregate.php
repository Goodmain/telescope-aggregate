<?php

use Goodmain\TelescopeAggregate\Collectors\Collector;

return [
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'pgsql'),
        ],
    ],

    'collectors' => [
        Goodmain\TelescopeAggregate\Collectors\QueryCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_QUERY', true),
        ],
        Goodmain\TelescopeAggregate\Collectors\CommandCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_COMMAND', true),
        ],
        Goodmain\TelescopeAggregate\Collectors\RequestCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_REQUEST', true),
        ],
        Goodmain\TelescopeAggregate\Collectors\ExceptionCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_EXCEPTION', true),
        ]
    ],
    'sources' => [
        Collector::PERIOD_HOUR => Collector::SOURCE_TELESCOPE,
        Collector::PERIOD_DAY => Collector::SOURCE_TELESCOPE,
        Collector::PERIOD_WEEK => Collector::SOURCE_AGGREGATE,
        Collector::PERIOD_MONTH => Collector::SOURCE_AGGREGATE,
        Collector::PERIOD_YEAR => Collector::SOURCE_AGGREGATE,
    ]
];

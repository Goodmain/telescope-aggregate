<?php

use App\Support\Collectors\Collector;

return [
    'collectors' => [
        App\Support\Collectors\QueryCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_QUERY', true),
        ],
        App\Support\Collectors\CommandCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_COMMAND', false),
        ],
        App\Support\Collectors\RequestCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_REQUEST', false),
        ],
        App\Support\Collectors\ExceptionCollector::class => [
            'enabled' => env('TELESCOPE_COLLECT_EXCEPTION', false),
        ]
    ],
    'sources' => [
        Collector::PERIOD_HOUR => Collector::SOURCE_TELESCOPE,
        Collector::PERIOD_DAY => Collector::SOURCE_TELESCOPE,
        Collector::PERIOD_WEEK => Collector::SOURCE_STATISTICS,
        Collector::PERIOD_MONTH => Collector::SOURCE_STATISTICS,
        Collector::PERIOD_YEAR => Collector::SOURCE_STATISTICS,
    ]
];

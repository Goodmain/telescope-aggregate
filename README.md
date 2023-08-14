# Telescope Aggregate Laravel Plugin

Collect data by any period from Laravel Telescope application.

### Installation

1. Install the package via composer:
 ```bash
    composer require goodmain/telescope-aggregate
```
2. Publish the config file:
```bash
    php artisan vendor:publish
```

3. Create the database table:
```bash
    php artisan migrate
```

### Usage

1. Turn on/off necessary collectors in the `telescope-aggregate.php` config file.

2. If you want automatically collect data, add the following code to the `schedule` method of your `app/Console/Kernel.php` file:

```php
    protected function schedule(Schedule $schedule)
    {
        ...

        $schedule->command('telescope:aggregate', ['hour'])->hourly();
        $schedule->command('telescope:aggregate', ['day'])->daily();
        $schedule->command('telescope:aggregate', ['week'])->weekly();
        $schedule->command('telescope:aggregate', ['month'])->monthly();
        $schedule->command('telescope:aggregate', ['year'])->yearly();
    }
```
3. If you want to collect data manually, run the following command:
```bash
    php artisan telescope:aggregate {period}
```
where `{period}` is one of the following: `hour`, `day`, `week`, `month`, `year`.

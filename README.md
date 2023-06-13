# This is my package laravel-slack-errors-log


This package can quickly send alerts to Slack. You can use this to notify yourself of any error.

## Installation

You can install the package via composer:

```bash
composer require mahmoud-mhamed/laravel-slack-errors-log
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-slack-errors-log-config"
```

This is the contents of the published config file:

```php
return [
    'log_error_in_local' => env('SLACK_LOG_ERROR_IN_LOCAL', false),
    'log_header' => env('SLACK_LOG_HEADER', true),
    'header_title' => null, // null = 🚨 env('APP_NAME') Exception Occurred!
    'log_content' => env('SLACK_LOG_CONTENT', true),
    'content' => null, //null = send error message
    'log_url' => env('SLACK_LOG_URL', true),
    'log_auth' => env('SLACK_LOG_AUTH', true),
    'log_trace' => env('SLACK_LOG_TRACE', false),
    'append_message' => null,//string data append to slack log message
];
```


## Usage
in .env file add
LOG_SLACK_WEBHOOK_URL="https://hooks.slack.com/services/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"

in App\Exceptions\Handler.php in register function add
```php
use Mahmoudmhamed\LaravelSlackErrorsLog\LaravelSlackErrorsLog;

$this->reportable(function (Throwable $e) {
     LaravelSlackErrorsLog::sendSlackError($e);
});
```

## Credits

- [mahmoud-mhamed](https://github.com/mahmoud-mhamed)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

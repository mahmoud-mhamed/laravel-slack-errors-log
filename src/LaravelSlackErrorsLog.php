<?php

namespace Mahmoudmhamed\LaravelSlackErrorsLog;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LaravelSlackErrorsLog
{
    public static function sendSlackError($exception,$append_in_run_time_message=null): void
    {
        if (App::isLocal() && ! config('slack-errors-log.log_error_in_local')) {
            return;
        }
        try {
            Log::channel('slack')->error(self::getErrorHeader().self::getErrorContent($exception).self::getUrlData().self::appendMessage().self::appendInRunTimeMessage($append_in_run_time_message).self::getAuthData().self::getTraceBlock($exception));
        } catch (\Throwable  $e) {
            Log::error($e);
        }
    }

    private static function getAuthData(): ?string
    {
        if (config('slack-errors-log.log_auth')) {
            $guards=array_keys(config('auth.guards'));
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    $user = Auth::guard($guard)->user();
                    $user_id = data_get($user, 'id');
                    $user_name = data_get($user, 'name');
                    $user_email = data_get($user, 'email');

                    return self::getLineString() . "
👹Auth Data
Name: $user_name
Id: $user_id
Guard: $guard
📧 Email: $user_email";
                }
            }
        }

        return null;
    }

    private static function getUrlData(): ?string
    {
        if (config('slack-errors-log.log_url')) {
            $request = request();
            $url = url();

            return self::getLineString()."
URL: {$request->url()}
IP: {$request->ip()}
Previous Url: {$url->previous()}";
        }

        return null;
    }

    private static function appendMessage(): ?string
    {
        $append_message = config('slack-errors-log.append_message');
        if ($append_message) {
            return self::getLineString()."
$append_message";
        }

        return null;
    }
    private static function appendInRunTimeMessage($append_message=null): ?string
    {
        if ($append_message) {
            return self::getLineString()."
$append_message";
        }

        return null;
    }

    private static function getTraceBlock($exception): ?string
    {
        if (! config('slack-errors-log.log_trace')) {
            return null;
        }
        $trace_string = method_exists($exception, 'getTraceAsString') ? mb_substr($exception->getTraceAsString(), 0, 1000) : '';
        $error_trace = "
📌Trace
$trace_string";

        return self::getLineString().$error_trace;
    }

    private static function getErrorHeader(): ?string
    {
        if (! config('slack-errors-log.log_header')) {
            return null;
        }
        if (config('slack-errors-log.header_title')) {
            return config('slack-errors-log.header_title');
        }

        return '🚨 '.env('APP_NAME').' Exception Occurred!';
    }

    private static function getErrorContent($exception): ?string
    {
        if (! config('slack-errors-log.log_content')) {
            return null;
        }
        if (config('slack-errors-log.content')) {
            $message = config('slack-errors-log.content');
        } else {
            $el_message = method_exists($exception, 'getMessage') ? $exception->getMessage() : '';
            $el_file = method_exists($exception, 'getFile') ? $exception->getFile() : '';
            $el_line = method_exists($exception, 'getLine') ? $exception->getLine() : '';
            $message = "
💩{$el_message}
📂{$el_file} line {$el_line}";
        }

        return self::getLineString().$message;

    }

    private static function getLineString(): string
    {
        return '
───────────── ';
    }
}

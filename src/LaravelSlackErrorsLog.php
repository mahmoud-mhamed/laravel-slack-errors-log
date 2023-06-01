<?php

namespace Mahmoudmhamed\LaravelSlackErrorsLog;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LaravelSlackErrorsLog
{
    public static function sendSlackError(Throwable $exception): void
    {
        if (App::isLocal() && ! config('slack-errors-log.log_error_in_local')) {
            return;
        }
        try {
            Log::channel('slack')->error(self::getErrorHeader().self::getErrorContent($exception).self::getUrlData().self::getAuthData().self::getTraceBlock($exception));
        } catch (\Throwable  $e) {
            Log::error($e);
        }
    }

    private static function getAuthData(): ?string
    {
        if (config('slack-errors-log.log_auth') && Auth::check()) {
            $user = Auth::user();
            $user_id = data_get($user, 'id');
            $user_name = data_get($user, 'name');
            $user_email = data_get($user, 'email');

            return self::getLineString()."
👹Auth Name: $user_name
Auth Id: $user_id
📧 Auth Email: $user_email";
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

    private static function getTraceBlock(Throwable $exception): ?string
    {
        if (! config('slack-errors-log.log_trace')) {
            return null;
        }
        $trace_string = mb_substr($exception->getTraceAsString(), 0, 1000);
        $error_trace = "
📌Trace : $trace_string";

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
            $message = "
💩{$exception->getMessage()}
📂{$exception->getFile()} line {$exception->getLine()}";
        }

        return self::getLineString().$message;

    }

    private static function getLineString(): string
    {
        return '
───────────── ';
    }
}

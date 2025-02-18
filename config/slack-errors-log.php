<?php

return [
    'log_error_in_local' => env('SLACK_LOG_ERROR_IN_LOCAL', false),
    'header_title' => null, // null = 🚨 env('APP_NAME') Exception Occurred!
    'log_content' => env('SLACK_LOG_CONTENT', true),
    'content' => null, // null = send error message
    'log_trace' => env('SLACK_LOG_TRACE', false),
    'append_message' => null, // string data append to slack log message
];

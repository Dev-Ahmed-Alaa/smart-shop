<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'openai/gpt-oss-20b'),
        'temperature' => env('GROQ_TEMPERATURE', 1),
        'max_completion_tokens' => env('GROQ_MAX_COMPLETION_TOKENS', 8192),
        'top_p' => env('GROQ_TOP_P', 1),
        'reasoning_effort' => env('GROQ_REASONING_EFFORT', 'medium'),
        'fallback_models' => [
            'openai/gpt-oss-20b',
            'openai/gpt-oss-120b',
            'openai/gpt-oss-safeguard-20b',
            'groq/compound',
            'groq/compound-mini',
            'llama-3.1-8b-instant',
            'llama-3.3-70b-versatile',
            'meta-llama/llama-4-maverick-17b-12',
            'meta-llama/llama-4-scout-17b-16e-i',
            'moonshotai/kimi-k2-instruct-0905',
        ],
    ],

];

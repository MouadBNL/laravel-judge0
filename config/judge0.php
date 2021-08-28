<?php

use Mouadbnl\Judge0\Services\Judge0InstanceAPIService;
use Mouadbnl\Judge0\Services\Judge0RapidapiService;

return [

    'default' => 'instance',

    'drivers' => [

        'rapidapi' => [

            'class' => Judge0RapidapiService::class,

            'base_uri' => env('JUDGE0_RAPIDAPI_BASE_URI', 'https://judge0-ce.p.rapidapi.com'),

            'headers' => [
                'x-rapidapi-host' => env('JUDGE0_RAPIDAPI_HOST', 'judge0-ce.p.rapidapi.com'),
                'x-rapidapi-key' => env('JUDGE0_RAPIDAPI_KEY', null)
            ],

            'endpoints' => [
                'authenticate' => [
                    'method' => 'GET',
                    'uri' => '/rapidapi'
                ],


                'postSubmission' => [
                    'method' => 'POST',
                    'uri' => '/submissions'
                ],
                'getSubmission' => [
                    'method' => 'GET',
                    'uri' => '/submissions/{token}'
                ],


                'postBatchedSubmissions' => [
                    'method' => 'POST',
                    'uri' => '/submissions/batch'
                ],
                'getBatchedSubmissions' => [
                    'method' => 'GET',
                    'uri' => '/submissions/batch/{tokens}'
                ],


                'getLanguages' => [
                    'method' => 'GET',
                    'uri' => '/languages'
                ],
                'getLanguages' => [
                    'method' => 'GET',
                    'uri' => '/languages/{id}'
                ],

                'getAbout' => [
                    'method' => 'GET',
                    'uri' => '/about'
                ],
                'getStatuses' => [
                    'method' => 'GET',
                    'uri' => '/statuses'
                ],
                'getConfig' => [
                    'method' => 'GET',
                    'uri' => '/config_info'
                ]
            ]
        ],

        'instance' => [

            'class' => Judge0InstanceAPIService::class,

            'base_uri' => env('JUDGE0_BASE_URI', 'localhost:2358'),

            'headers' => [
                'X-Auth-Token' => env('JUDGE0_KEY', null)
            ],

            'endpoints' => [
                'authenticate' => [
                    'method' => 'POST',
                    'uri' => '/authenticate'
                ],


                'postSubmission' => [
                    'method' => 'POST',
                    'uri' => '/submissions'
                ],
                'getSubmission' => [
                    'method' => 'GET',
                    'uri' => '/submissions/{token}'
                ],
                'deleteSubmission' => [
                    'method' => 'DELETE',
                    'uri' => '/submissions/{token}'
                ],


                'postBatchedSubmissions' => [
                    'method' => 'POST',
                    'uri' => '/submissions/batch'
                ],
                'getBatchedSubmissions' => [
                    'method' => 'GET',
                    'uri' => '/submissions/batch/{tokens}'
                ],
                'deleteBatchedSubmissions' => [
                    'method' => 'DELETE',
                    'uri' => '/submissions/batch/{tokens}'
                ],


                'getLanguages' => [
                    'method' => 'GET',
                    'uri' => '/languages'
                ],
                'getAllLanguages' => [
                    'method' => 'GET',
                    'uri' => '/languages/all'
                ],
                'getLanguages' => [
                    'method' => 'GET',
                    'uri' => '/languages/{id}'
                ],

                'getAbout' => [
                    'method' => 'GET',
                    'uri' => '/about'
                ],
                'getStatuses' => [
                    'method' => 'GET',
                    'uri' => '/statuses'
                ],
                'getConfig' => [
                    'method' => 'GET',
                    'uri' => '/config_info'
                ],
                'getStatistics' => [
                    'method' => 'GET',
                    'uri' => '/statistics'
                ],
                'getWorkers' => [
                    'method' => 'GET',
                    'uri' => '/workers'
                ],
                'getVersion' => [
                    'method' => 'GET',
                    'uri' => '/version'
                ],
                'getIsolate' => [
                    'method' => 'GET',
                    'uri' => '/isolate'
                ],
                'getLicense' => [
                    'method' => 'GET',
                    'uri' => '/license'
                ]
            ]
        ],

    ],

    'submission_config' => [
        'cpu_time_limit' => 2,
        'cpu_extra_time' => 1,
        'wall_time_limit' => 10,
        'memory_limit' => 256000,
        'stack_limit' => 64000,
        'max_processes_and_or_threads' => 120,
        'enable_per_process_and_thread_time_limit' => false,
        'enable_per_process_and_thread_memory_limit' => false,
        'max_file_size' => 4096,
        'redirect_stderr_to_stdout' => false,
        'enable_network' => false,
        'number_of_runs' => 1,
        // will send a PUT request with the submission in the body
        'callback_url' => null,
        'compiler_options' => null,
        'command_line_arguments' => null,
        //Additional files that should be available alongside the source code in base64.
        'additional_files' => null,
    ]

];
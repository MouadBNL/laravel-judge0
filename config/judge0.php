<?php

use Mouadbnl\Judge0\Services\Judge0InstanceAPIService;
use Mouadbnl\Judge0\Services\Judge0RapidapiService;

return [

    /**
     * This allows to choose which service/driver to use as the Judge0 by default.
     * The drivers load Base URI, API key, and headers needed to send a request.
     * You can find the provided drivers in the drivers key in this config.
     * You can also add you own drivers.
     */
    'default' => 'instance',

    /**
     * Define wheter to allow rejudging og a submission after is has been judged
     */
    'resubmit_judged_submission' => false,

    /**
     * If you disable resubmitting, this allows to choose wheter to throw an exception, or just return the judged submission
     */
    'throw_error_on_resubmit' => true,

    /**
     * Allows you to lock any changes in the submission after it has been judged
     * the last changes made to a submission are done in the submit method
     */
    'lock_submisson_after_judging' => false,

    /**
     * The Guzzle package throws an error if a request fails.
     * this allows you to choose wheter to throw the Guzzle client exception or return a formated response
     */
    'exception_on_failed_requests' => true,

    /**
     * Drivers used to communicate with the API service.
     * Notice that each driver is slightly different, so each one must be associated to a class.
     * Those classes imlements the Judge0Interface
     */
    'drivers' => [

        'rapidapi' => [

            'class' => Judge0RapidapiService::class,

            'base_uri' => env('JUDGE0_RAPIDAPI_BASE_URI', 'https://judge0-ce.p.rapidapi.com'),

            'timeout' => 30.0,

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
                'getLanguage' => [
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

            'timeout' => 30.0,

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
                'getLanguage' => [
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

    /**
     * This defines the default configuration to be sent with a submission
     * more details here https://github.com/MouadBNL/laravel-judge0/blob/main/docs/submission_config.md
     */
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
    ],

    /**
     * This defines the default parameters to be sent with a submission as a url query
     * more details here https://github.com/MouadBNL/laravel-judge0/blob/main/docs/submission_config.md
     */
    'submission_params' => [
        'base64' => true,
        'wait' => true,
        'fields' => '*'
    ],

    /**
     * Define the table names to be used. so it does not collide with any tables you may have made.
     */
    'table_names' => [
        'submissions' => 'submissions',
        'statuses'    => 'judge0_statuses',
        'languages'   => 'judge0_languages'
    ]

];
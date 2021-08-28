<?php
return [

    'default' => '',

    'drivers' => [

        'rapidapi' => [

            'base_uri' => env('JUDGE0_RAPIDAPI_BASE_URI', 'https://judge0-ce.p.rapidapi.com'),

            'headers' => [
                'x-rapidapi-host' => env('JUDGE0_RAPIDAPI_HOST', 'judge0-ce.p.rapidapi.com'),
                'x-rapidapi-key' => env('JUDGE0_RAPIDAPI_KEY', null)
            ],

            'endpoints' => [
                'authenticate' => [
                    'method' => 'GET',
                    'uri','/rapidapi'
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

            'base_uri' => env('JUDGE0_BASE_URI', 'localhost:2358'),

            'headers' => [
                'X-Auth-Token' => env('JUDGE0_KEY', null)
            ],

            'endpoints' => [
                'authenticate' => [
                    'method' => 'POST',
                    'uri','/authenticate'
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

    ]

];
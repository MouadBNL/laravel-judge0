<?php

namespace Mouadbnl\Judge0\Interfaces;

use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\SubmissionParams;

interface Judge0Interface
{

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | The following methodes test if the API secret key is provided as well as 
    | testing if the connection is well established with the Judge0 instance.
    |
    */

    public function authenticate();

    /*
    |--------------------------------------------------------------------------
    | Handling submissions
    |--------------------------------------------------------------------------
    |
    | The following methodes interact with everything related to submmisons
    | provided by the Judge0 API.
    |
    */

    public function postSubmission(
        string  $source_code,
        int     $language_id,
        ?string $stdin = null,
        ?string $expected_output = null,
        SubmissionConfig $config,
        SubmissionParams $params	
    );

    public function getSubmission(string $token, SubmissionParams $params);

    public function deleteSubmission(string $token);

    public function postBatchedSubmissions(
        Array $submissions,
        string  $source_code,
        int     $language_id,
        ?string $stdin = null,
        ?string $expected_output = null,
        SubmissionConfig $config,
        SubmissionParams $params	
    );

    public function getBatchedSubmissions(Array $tokens, SubmissionParams $params);

    public function deleteBatchedSubmissions(Array $tokens);


    /*
    |--------------------------------------------------------------------------
    | Getting languages
    |--------------------------------------------------------------------------
    |
    | The following methodes interact with everything related to languages
    | provided by the Judge0 API.
    |
    */

    public function getLanguages();

    public function getAllLanguages();

    public function getLanguage(int $id);


    /*
    |--------------------------------------------------------------------------
    | Getting Additional informations
    |--------------------------------------------------------------------------
    |
    | The following methodes interact with everything related to statistics,
    | configuration, and information about the instance of Judge0 API.
    |
    */

    public function getAbout();

    public function getStatuses();

    public function getConfig();

    public function getStatistics();

    public function getWorkers();

    public function getVersion();

    public function getIsolate();

    public function getLicense();
}

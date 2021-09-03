<?php

namespace Mouadbnl\Judge0\Interfaces;

use Mouadbnl\Judge0\Models\Submission;
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

    /**
     * @param Submission $submission the submission to send to the Judge0
     * @param array $options Additional options to send with the request
     */
    function postSubmission(Submission $submission, array $options = []);

    /**
     * @param string $token The token of the submmsion to retrieve
     */
    public function getSubmission(string $token);

    // public function getBatchedSubmissions(Array $tokens, SubmissionParams $params);

    // public function deleteBatchedSubmissions(Array $tokens);


    /*
    |--------------------------------------------------------------------------
    | Getting languages
    |--------------------------------------------------------------------------
    |
    | The following methodes interact with everything related to languages
    | provided by the Judge0 API.
    |
    */

    /**
     * Getting allowed languages from Judge0 API
     */
    public function getLanguages();

    /**
     * Getting all language available in Judge0
     */
    public function getAllLanguages();

    /**
     * Getting a language from judge API
     * @param id language id
     */
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

    // public function getAbout();

    public function getStatuses();

    public function getStatistics();
    
    public function getWorkers();
    
    // public function getConfig();

    // public function getVersion();

    // public function getIsolate();

    // public function getLicense();
}

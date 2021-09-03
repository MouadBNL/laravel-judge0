<?php

namespace Mouadbnl\Judge0\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Mouadbnl\Judge0\Interfaces\Judge0Interface;
use Mouadbnl\Judge0\Models\Submission;

class Judge0InstanceAPIService implements Judge0Interface
{
    protected Client $client;
    protected array $endpoints;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('judge0.drivers.instance.base_uri'),
            'timeout' => 5.0,
            'headers' => config('judge0.drivers.instance.headers')
        ]);
        $this->endpoints = config('judge0.drivers.instance.endpoints');
    }

    /*
    |--------------------------------------------------------------------------
    | judge0 Authentication
    |--------------------------------------------------------------------------
    */

    function authenticate()
    {
        $endpoint = $this->endpoints['authenticate'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /*
    |--------------------------------------------------------------------------
    | judge0 Submissions
    |--------------------------------------------------------------------------
    | Managing everuthing related so submissions
    */

    /**
     * @param Submission $submission the submission to send to the Judge0
     * @param array $options Additional options to send with the request
     */
    function postSubmission(Submission $submission, array $options = [])
    {
        $endpoint = $this->endpoints['postSubmission'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri'] . $submission->getParamsUrl(), [
            'json' => array_merge(
                $submission->getAllAttributes(),
                $options
            )
        ]);
    }

    /**
     * @param string $token The token of the submmsion to retrieve
     */
    public function getSubmission(string $token)
    {
        $endpoint = $this->endpoints['getSubmission'];
        $uri = str_replace("{token}", $token, $endpoint['uri']);
        return $this->sendRequest($endpoint['method'], $uri);
    }

    /*
    |--------------------------------------------------------------------------
    | languages endpoints
    |--------------------------------------------------------------------------
    */

    /**
     * Getting allowed languages from Judge0 API
     */
    public function getLanguages()
    {
        $endpoint = $this->endpoints['getLanguages'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /**
     * Getting all language available in Judge0
     */
    public function getAllLanguages()
    {
        $endpoint = $this->endpoints['getAllLanguages'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /**
     * Getting a language from judge API
     * @param id language id
     */
    public function getLanguage(int $id)
    {
        $endpoint = $this->endpoints['getLanguage'];
        $uri = str_replace("{id}", $id, $endpoint['uri']);
        return $this->sendRequest($endpoint['method'], $uri);
    }

    /*
    |--------------------------------------------------------------------------
    | judge0 Statuses
    |--------------------------------------------------------------------------
    */

    public function getStatuses()
    {
        $endpoint = $this->endpoints['getStatuses'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /*
    |--------------------------------------------------------------------------
    | judge0 Statistics
    |--------------------------------------------------------------------------
    */

    public function getStatistics()
    {
        $endpoint = $this->endpoints['getStatistics'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /*
    |--------------------------------------------------------------------------
    | judge0 Workers
    |--------------------------------------------------------------------------
    */

    public function getWorkers()
    {
        $endpoint = $this->endpoints['getWorkers'];
        return $this->sendRequest($endpoint['method'], $endpoint['uri']);
    }

    /*
    |--------------------------------------------------------------------------
    | internal functions
    |--------------------------------------------------------------------------
    */
    
    /**
     * Formating the response returned by the Guzzle Client
     * @param Response $res the response returned by the Gizzle client
     */
    protected function formatResponse(Response $res)
    {
        return [
            'code' => $res->getStatusCode(),
            'content' => (array)json_decode($res->getBody()->getContents()),
        ];
    }

    /**
     * In Case judge0 counld not process the request, it will return a response 
     * with an Http error code, and it is cached bu Guzzle client and it 
     * throw is as an exception, here for format the exception to 
     * return an appropriate response
     * @param ClientException $e
     * @return Array $response with the Http error code and the exception message
     */
    protected function formatClientException(ClientException $e){
        return [
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ];
    }

    /**
     * Easily send a request to a uri with the guzzle client and get a formated response
     * @param string $method GET | POST | PUT | PATCH | DELETE
     * @param string $uri The endpoint to which the request will be sent on top
     *               of the the base_uri of the Guzzle client
     * @param array $options Otions like the body and headers .. that will be send 
     *              with the request (https://docs.guzzlephp.org/en/stable/request-options.html)
     * @return array $res if the request was a success this willr eturn an array with
     *               the response code and its content
     * @return array $res if the request was unsuccessful, it will return and array with
     *               the response error code and message
     * @return ClientException if the request was unsuccessful and config allows it to
     *                          throw the error
     */
    public function sendRequest(string $method, string $uri, array $options = [])
    {
        try {
            return $this->formatResponse(
                $this->client->request($method, $uri, $options)
            );
        } catch (ClientException $e) {
            if(config('judge0.exception_on_failed_requests')){
                throw $e;
            }
            return $this->formatClientException($e);
        }
    }
}
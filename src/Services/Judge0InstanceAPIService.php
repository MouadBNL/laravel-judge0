<?php

namespace Mouadbnl\Judge0\Services;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\SubmissionParams;

class Judge0InstanceAPIService
{
    protected Client $client;
    protected array $endpoints;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('judge0.config.base_uri'),
            'timeout' => 5.0,
            'headers' => config('judge0.config.headers')
        ]);
        $this->endpoints = config('judge0.config.endpoints');
    }

    function authenticate()
    {
        $endpoint = $this->endpoints['authenticate'];
        try {
            $res = $this->client->request($endpoint['method'], $endpoint['uri']);
            return $this->formatResponse($res);
        } catch (ClientException $e) {
            return $this->formatClientException($e);
        }
    }

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

    public function getSubmission(string $token)
    {
        $endpoint = $this->endpoints['getSubmission'];
        $uri = str_replace("{token}", $token, $endpoint['uri']);
        return $this->sendRequest($endpoint['method'], $uri);
    }

    protected function formatResponse(Response $res)
    {
        return [
            'code' => $res->getStatusCode(),
            'content' => (array)json_decode($res->getBody()->getContents()),
        ];
    }

    protected function formatClientException(ClientException $e){
        return [
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ];
    }

    protected function sendRequest(string $method, string $uri, array $options = [])
    {
        try {
            return $this->formatResponse(
                $this->client->request($method, $uri, $options)
            );
        } catch (ClientException $e) {
            throw $e;
            // return $this->formatClientException($e);
        }
    }
}
<?php

namespace Mouadbnl\Judge0\Services;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
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

    function postSubmission(
        string  $source_code,
        int     $language_id,
        ?string $stdin = null,
        ?string $expected_output = null,
        ?SubmissionConfig $config = null,
        ?SubmissionParams $params = null	
    )
    {
        $config = ($config instanceof SubmissionConfig) ? $config : SubmissionConfig::init();
        $params = ($params instanceof SubmissionParams) ? $params : SubmissionParams::init();
        $endpoint = $this->endpoints['postSubmission'];

        $submission = Judge0Submission::validate([
            'source_code' => $source_code,
            'language_id' => $language_id,
            'stdin'       => $stdin,
            'expected_output' => $expected_output
        ]);

        if($params->base64) $submission = Judge0Submission::formatToBase64($submission);
        dump($params->getUrl());

        return $this->sendRequest($endpoint['method'], $endpoint['uri'] . $params->getUrl(), [
            'json' => array_merge(
                $submission,
                $config->getConfig()
            )
        ]);
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

    protected function sendRequest(string $method, string $uri, array $options)
    {
        try {
            $res = $this->client->request($method, $uri, $options);
            return $this->formatResponse($res);
        } catch (ClientException $e) {
            return $this->formatClientException($e);
        }
    }
}
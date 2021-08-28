<?php

namespace Mouadbnl\Judge0\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

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
        $authenticate = $this->endpoints['authenticate'];
        try {
            $res = $this->client->request($authenticate['method'], $authenticate['uri']);
            return $this->formatResponse($res);
        } catch (ClientException $e) {
            return $this->formatClientException($e);
        }
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
}
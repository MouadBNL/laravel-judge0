<?php

namespace Mouadbnl\Judge0\Services;

use InvalidArgumentException;

class SubmissionParams
{
    protected array $paramsItems = [
        'base64' => ['bool'],
        'wait' => ['bool'],
        'feilds' => ['string']
    ];

    protected array $params;

    public function __construct(array $params = [])
    {
        // TODO add validation here
        foreach ($this->paramsItems as $key => $types) {
            $this->params[$key] = $params[$key] ?? config('judge0.submission_params.' . $key);
        }
    }
    
    // Adding a static constructor
    public static function init(array $config = []): self
    {
        return new self($config);
    }

    public function set(string $key, string $value)
    {
        if(! isset($this->params[$key])){
            throw new InvalidArgumentException("Error property '" . $key . "' not found");
        }
        
        $this->params[$key] = $value;
        return $this;
    }

    public function getParams(?string $key = null)
    {
        return $key ? $this->params[$key] : $this->params;
    }

    public function getUrl()
    {
        $params = [
            'base64_encoded' => ($this->params['base64'] ? 'true' : 'false'),
            'wait' => ($this->params['wait'] ? 'true' : 'false'),
            'feilds' => (isset($this->params['fields']) && $this->params['fields'] !='*') ? '&feilds=' . $this->params['fields'] : ''
        ];
        return '?' . implode('&', array_map(
            function($k, $v){
                return $v . '=' . $k;
            },
            $params,
            array_keys($params)
        ));
    }
}
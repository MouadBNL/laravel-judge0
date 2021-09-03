<?php

namespace Mouadbnl\Judge0\Services;

use InvalidArgumentException;

class SubmissionParams
{
    /**
     * @property array $paramKeys the keys availabe to get|change
     */
    protected array $paramsKeys = [
        'base64' => ['boolean'],
        'wait' => ['boolean'],
        'fields' => ['string']
    ];

    /**
     * @property array $params the params keys and values
     */
    protected array $params = [
        'base64' => true,
        'wait' => true,
        'fields' => '*'
    ];

    /**
     * @param array $params overriding the default params
     */
    public function __construct(array $params = [])
    {
        foreach ($this->paramsKeys as $key => $types) {
            $value = $params[$key] ?? config('judge0.submission_params.' . $key);
            $this->valdiateKeyValue($key, $value);
            $this->params[$key] = $value;
        }
    }
    
    /**
     * @param array $params  overriding the default params
     */
    public static function init(array $params = []): self
    {
        return new self($params);
    }

    /**
     * Changing something in the default params
     * @param string $key key of the params to set
     * @param any $value the value of the new params
     * @return self
     */
    public function set(string $key, $value)
    {
        $this->valdiateKeyValue($key, $value);
        
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @param string $key if null will return the whole params,
     *              else return the value of the key in the params
     * @return array $params if the key is null
     * @return string $value of the params key 
     */
    public function getParams(?string $key = null)
    {
        return $key ? $this->params[$key] : $this->params;
    }

    /**
     * Get the formated url query
     * ?base64_encode=true&wait=true&fields=*
     * @return string $getparamsUrl
     */
    public function getUrl()
    {
        $params = [
            'base64_encoded' => ($this->params['base64'] ? 'true' : 'false'),
            'wait' => ($this->params['wait'] ? 'true' : 'false'),
            'fields' => (isset($this->params['fields']) && $this->params['fields'] !='*') ? '&fields=' . $this->params['fields'] : ''
        ];
        return '?' . implode('&', array_map(
            function($k, $v){
                return $v . '=' . $k;
            },
            $params,
            array_keys($params)
        ));
    }

    protected function valdiateKeyValue(string $key, $value)
    {
        if(! array_key_exists($key, $this->paramsKeys))
        {
            throw new InvalidArgumentException("SubmissionParams does not contain ". $key .".");
        }

        $types = $this->paramsKeys[$key];
        $type = strtolower(gettype($value));
        if(! in_array($type, $types)){
            throw new InvalidArgumentException("Invalid type, " . $key . " must be of type ". implode(', ', $types) .".");
        }
    }
}
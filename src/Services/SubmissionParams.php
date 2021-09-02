<?php

namespace Mouadbnl\Judge0\Services;

use InvalidArgumentException;

class SubmissionParams
{
    /**
     * @property array $paramKeys the keys availabe to get|change
     */
    protected array $paramsKeys = [
        'base64' => ['bool'],
        'wait' => ['bool'],
        'feilds' => ['string']
    ];

    /**
     * @property array $params the params keys and values
     */
    protected array $params;

    /**
     * @param array $params overriding the default params
     */
    public function __construct(array $params = [])
    {
        // TODO add validation here
        foreach ($this->paramsKeys as $key => $types) {
            $this->params[$key] = $params[$key] ?? config('judge0.submission_params.' . $key);
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
     * @param string $value the value of the new params
     * @return self
     */
    public function set(string $key, string $value)
    {
        if(! isset($this->params[$key])){
            throw new InvalidArgumentException("Error property '" . $key . "' not found");
        }
        
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
     * Get the formated get url
     * ?base64_encode=true&wait=true&fields=*
     * @return string $getparamsUrl
     */
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
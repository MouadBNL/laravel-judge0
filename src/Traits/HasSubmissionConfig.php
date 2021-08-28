<?php

namespace Mouadbnl\Judge0\Traits;

use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionConfig;

trait HasSubmissionConfig
{
    protected SubmissionConfig $config;

    /**
     * Allow for both syntaxes
     * setConfig('abc', 'efg');
     * setConfig([
     *  'abc' => 'efg',
     *  'uvw' => 'xyz'
     * ]);
     */
    public function setConfig($key, $value = null)
    {
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $this->config->set($k, $v);
            }
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $this->config->set($key, $value);
        return $this;
    }
}

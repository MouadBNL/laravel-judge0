<?php

namespace Mouadbnl\Judge0\Traits;

use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionParams;

trait HasSubmissionParams
{
    protected SubmissionParams $params;
    
    /**
     * Allow for both syntaxes
     * setParams('abc', 'efg');
     * setParams([
     *  'abc' => 'efg',
     *  'uvw' => 'xyz'
     * ]);
     */
    public function setParams($key, $value = null)
    {
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $this->params->set($k, $v);
            }
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $this->params->set($key, $value);
        return $this;
    }
}

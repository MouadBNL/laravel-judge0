<?php

namespace Mouadbnl\Judge0\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\SubmissionParams;
use Mouadbnl\Judge0\Traits\HasSubmissionConfig;
use Mouadbnl\Judge0\Traits\HasSubmissionParams;

class Submission extends Model
{
    use HasSubmissionConfig;
    use HasSubmissionParams;
    protected $attributes = [
        'config' => null,
        'params' => null
    ];
    
    protected $guarded = ['id'];


    public function __construct(array $attributes = [])
    {
        $this->attributes['config'] = json_encode(SubmissionConfig::init()->getConfig());
        $this->attributes['params'] = json_encode(SubmissionParams::init()->getParams());

        parent::__construct($attributes);
    }

    public function getTable()
    {
        return config('judge0.table_names.submissions', parent::getTable());
    }

    public function getConfigAttribute()
    {
        return  json_decode($this->getAttributes()['config'], true);
    }

    public function setConfigAttribute($config)
    {
        if(! $config instanceof SubmissionConfig){
            throw new Exception("The config attribute must be an instance of " . SubmissionConfig::class . ".");
        }

        $this->attributes['config'] = json_encode($config->getConfig());
    }

    public function getParamsAttribute()
    {
        return  json_decode($this->getAttributes()['params'], true);
    }

    public function setParamsAttribute($params)
    {
        if(! $params instanceof SubmissionParams){
            throw new Exception("The params attribute must be an instance of " . SubmissionParams::class . ".");
        }

        $this->attributes['params'] = json_encode($params->getParams());
    }

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
        $config = SubmissionConfig::init($this->getConfigAttribute());
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $config->set($k, $v);
            }
            $this->update([
                'config' => $config
            ]);
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $config->set($key, $value);
        $this->update([
            'config' => $config
        ]);
        return $this;
    }

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
        $params = SubmissionParams::init($this->getParamsAttribute());
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $params->set($k, $v);
            }
            $this->update([
                'params' => $params
            ]);
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $params->set($key, $value);
        $this->update([
            'params' => $params
        ]);
        return $this;
    }
}
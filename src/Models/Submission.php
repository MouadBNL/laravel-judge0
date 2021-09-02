<?php

namespace Mouadbnl\Judge0\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Services\SubmissionConfig;
use Mouadbnl\Judge0\Services\SubmissionParams;
use phpDocumentor\Reflection\Types\Boolean;

class Submission extends Model
{
    protected $attributes = [
        'config' => null,
        'params' => null,
    ];
    
    protected $guarded = ['id'];

    protected $requestBase64Attributes = [
        'source_code', 'stdin', 'expected_output'
    ];

    protected $responseBase64Attributes = [
        'stdout', 'stderr', 'compile_output'
    ];


    public function __construct(array $attributes = [])
    {
        $this->config = SubmissionConfig::init();
        $this->params = SubmissionParams::init();
        $this->status = (object)['id' => 0, 'description' => 'Waiting'];

        parent::__construct($attributes);
    }

    public function getTable()
    {
        return config('judge0.table_names.submissions', parent::getTable());
    }

    public function submit()
    {
        if(! $this->canBeRejudged()) return $this;
        $res = Judge0::postSubmission($this);

        if($res['code'] != 201){
            throw new Exception("The judge had an error processeing your request");
        }

        
        if($this->getParams('base64'))
        {
            foreach ($this->responseBase64Attributes as $attr)
            {
                $res['content'][$attr] = base64_decode($res['content'][$attr]);
            }
        }
        
        $content = $res['content'];
        $this->update([
            'token'         => $content['token'],
            'status'        => $content['status'],
            'stdout'        => $content['stdout'],
            'stderr'        => $content['stderr'],
            'time'          => $content['time'],
            'memory'        => $content['memory'],
            'compile_output' => $content['compile_output'],
            'response'      => $res,
            'judged'        => true,
        ]);

        return $this;
    }

    public function setTimeLimit(float $seconds)
    {
        $this->setConfig('cpu_time_limit', $seconds);
        return $this;
    }

    public function setTimeLimitInMilliseconds(float $milliseconds)
    {
        $this->setConfig('cpu_time_limit', $milliseconds * 0.001);
        return $this;
    }

    public function setDefaultTimeLimit()
    {
        $this->setTimeLimit(config('judge0.submission_config.cpu_time_limit'));
        return $this;
    }

    public function setMemoryLimit(float $kilobyte)
    {
        $this->setConfig('memory_limit', $kilobyte);
        return $this;
    }

    public function setMemoryLimitInMegabytes(float $megabytes)
    {
        $this->setConfig('memory_limit', $megabytes * 1024);
        return $this;
    }

    public function setDefaultMemoryLimit()
    {
        $this->setMemoryLimit(config('judge0.submission_config.memory_limit'));
        return $this;
    }

    public function setStdin(?string $input)
    {
        $this->update(['stdin' => $input]);
        return $this;
    }

    public function setInput(?string $input)
    {
        $this->setStdin($input);
        return $this;
    }

    public function setExpectedOutput(?string $output)
    {
        $this->update(['expected_output' => $output]);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Managing config and params
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    public function getConfig(string $key)
    {
        return $this->config->getConfig($key);
    }

    public function getConfigAttribute()
    {
        return SubmissionConfig::init(
            json_decode($this->getAttributes()['config'], true)
        );
    }

    public function setConfigAttribute($config)
    {
        if(! $config instanceof SubmissionConfig){
            throw new Exception("The config attribute must be an instance of " . SubmissionConfig::class . ".");
        }

        $this->attributes['config'] = json_encode($config->getConfig());
    }

    public function getParams(string $key)
    {
        return $this->params->getParams($key);
    }

    public function getParamsAttribute()
    {
        return  SubmissionParams::init(json_decode($this->getAttributes()['params'], true));
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
        $config = $this->getConfigAttribute();
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
        $params = $this->getParamsAttribute();
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
    /*
    |--------------------------------------------------------------------------
    | 
    |--------------------------------------------------------------------------
    */

    public function getAllAttributes()
    {
        $attrs = array_merge(
            $this->attributes,
            $this->config->getConfig(),
            $this->params->getParams()
        );

        if($this->getParams('base64')){
            foreach ($this->requestBase64Attributes as $attr) {
                if(isset($attrs[$attr])){
                    $attrs[$attr] = base64_encode($attrs[$attr]);
                }
            }
        }

        return $attrs;
    }

    public function getParamsUrl()
    {
        return $this->params->getUrl();
    }

    /*
    |--------------------------------------------------------------------------
    | Manipulating Status attribute
    |--------------------------------------------------------------------------
    */

    public function setStatusAttribute($status)
    {
        if(is_array($status)){
            $status = (object)$status;
        }

        if(is_object($status) && property_exists($status,'id') && property_exists($status, 'description')){
            $this->attributes['status'] = json_encode($status);
            return;
        }

        throw new InvalidArgumentException("Status must an object with id and description attributes");
    }

    public function getStatusAttribute()
    {
        // dump($this->status);
        return json_decode($this->attributes['status']);
    }

    /*
    |--------------------------------------------------------------------------
    | Manipulating Response attribute
    |--------------------------------------------------------------------------
    */

    public function setResponseAttribute(array $res)
    {
        $this->attributes['response'] = json_encode($res);
    }

    public function getResponseAttribute()
    {
        return json_decode($this->attributes['response'], true);
    }

    /*
    |--------------------------------------------------------------------------
    | 
    |--------------------------------------------------------------------------
    */

    /**
     * ! this overides the default Model function
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if($this->judged && config('judge0.lock_submisson_after_judging')){
            throw new Exception("This submission has already been judged and cannot be updated");
        }
        parent::update($attributes, $options);
    }

    protected function canBeRejudged(): bool
    {
        if($this->judged && !config('judge0.resubmit_judged_submission'))
        {
            if(config('judge0.throw_error_on_resubmit')){
                throw new Exception("This submission has already been judged, and can not be rejudged");
            }
            return false;
        }
        return true;
    }
}
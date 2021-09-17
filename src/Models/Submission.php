<?php

namespace Mouadbnl\Judge0\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

    protected $hidden = [
        'config', 'params'
    ];

    protected $appends = [
        'config_array', 'params_array'
    ];
    
    protected $guarded = ['id'];

    /**
     * @property array $requestBase64Attributes
     * list of the attribute that needs to be encoded to base64 before sending the request
     */
    protected array $requestBase64Attributes = [
        'source_code', 'stdin', 'expected_output'
    ];

    /**
     * @property array $responseBase64Attributes
     * list of attributes that needs to be decoded from base64 after reciving the response
     */
    protected array $responseBase64Attributes = [
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

    public function submitter(): MorphTo
    {
        return $this->morphTo();
    }

    public function submit()
    {
        if(! $this->canBeRejudged()) return $this;
        $res = Judge0::postSubmission($this);

        if($res['code'] < 200 or $res['code'] >= 300){
            $this->update([
                'response' => $res
            ]);
            throw new Exception("The judge had an error processeing your request");
        }

        
        if($this->getParams('base64'))
        {
            $res['content'] = $this->formatFromBase64($this->responseBase64Attributes, $res['content']);
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

    /**
     * @param float $seconds the number of seconds before declaring the TLE status
     */
    public function setTimeLimit(float $seconds)
    {
        $this->setConfig('cpu_time_limit', $seconds);
        return $this;
    }

    /**
     * @param float $milliseconds setting the time limit in milliseconds
     */
    public function setTimeLimitInMilliseconds(float $milliseconds)
    {
        $this->setConfig('cpu_time_limit', $milliseconds * 0.001);
        return $this;
    }

    /**
     * Setting the defaul time limit from the config file
     */
    public function setDefaultTimeLimit()
    {
        $this->setTimeLimit(config('judge0.submission_config.cpu_time_limit'));
        return $this;
    }

    /**
     * @param int $kiobytes The memory limit in kilobytes before declaring MLE status
     */
    public function setMemoryLimit(int $kilobyte)
    {
        $this->setConfig('memory_limit', $kilobyte);
        return $this;
    }

    /**
     * @param float $megabytes setting the memory limit in megabytes
     */
    public function setMemoryLimitInMegabytes(float $megabytes)
    {
        $this->setConfig('memory_limit', intval($megabytes * 1024));
        return $this;
    }

    /**
     * Setting the defaul memory limit from the config file
     */
    public function setDefaultMemoryLimit()
    {
        $this->setMemoryLimit(config('judge0.submission_config.memory_limit'));
        return $this;
    }

    /**
     * @param string $input the input to provide to the source code
     */
    public function setStdin(?string $input)
    {
        $this->update(['stdin' => $input]);
        return $this;
    }

    /**
     * @param string $input the input to provide to the source code
     */
    public function setInput(?string $input)
    {
        $this->setStdin($input);
        return $this;
    }

    /**
     * @param string $output the expected output of the judge
     * this will define whether the output is wrong or not
     */
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

    /**
     * Appended config_array
     */
    public function getConfigArrayAttribute()
    {
        return $this->config->getConfig();
    }

    /**
     * Appended params_array
     */
    public function getParamsArrayAttribute()
    {
        return $this->params->getParams();
    }

    /**
     * @param string $key The config key to get
     * @return string $value the value of config
     */
    public function getConfig(string $key): string
    {
        return $this->config->getConfig($key);
    }

    /**
     * Get an instance og SubmissionConfig class initiated from the submission's config
     * @return SubmissionConfig $config
     */
    public function getConfigAttribute(): SubmissionConfig
    {
        return SubmissionConfig::init(
            json_decode($this->getAttributes()['config'], true)
        );
    }

    /**
     * Setting the Config on the submission
     * @param SubmissionConfig $config
     */
    public function setConfigAttribute($config)
    {
        if(! $config instanceof SubmissionConfig){
            throw new Exception("The config attribute must be an instance of " . SubmissionConfig::class . ".");
        }

        $this->attributes['config'] = json_encode($config->getConfig());
    }

    /**
     * @param string $key The params key to get
     * @return string $value the value of params
     */
    public function getParams(string $key)
    {
        return $this->params->getParams($key);
    }

    /**
     * Get an instance og SubmissionParams class initiated from the submission's params
     * @return SubmissionParams $params
     */
    public function getParamsAttribute()
    {
        return  SubmissionParams::init(json_decode($this->getAttributes()['params'], true));
    }

    /**
     * Setting the Params on the submission
     * @param SubmissionParams $params
     */
    public function setParamsAttribute($params)
    {
        if(! $params instanceof SubmissionParams){
            throw new Exception("The params attribute must be an instance of " . SubmissionParams::class . ".");
        }

        $this->attributes['params'] = json_encode($params->getParams());
    }

    /**
     * Setting one or mutiple config values
     * @param string|array $key array of config to override, ot the single key to override
     * @param any $value the value to set for the provided key
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
     * Setting one or mutiple params values
     * @param string|array $key array of params to override, ot the single key to override
     * @param any $value the value to set for the provided key
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

    /**
     * Get all model attribute as an array including the config and params 
     * merged with the attributes
     * @return array $attributes
     */
    public function getAllAttributes()
    {
        $attrs = array_merge(
            $this->attributes,
            $this->config->getConfig(),
            $this->params->getParams()
        );

        if($this->getParams('base64')){
            $attrs = $this->formatToBase64($this->requestBase64Attributes, $attrs);
        }

        return $attrs;
    }

    /**
     * get The SubmissionParams fromated get url params
     */
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

    /**
     * Find the the submission could be judged based on whether 
     * it was already judged and if the judge0 config allows it
     * @return bool $judgeable
     */
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

    /**
     * formating  keys to base base64
     * @param array $keys the key to format
     * @param array $attributes to be formated
     */
    protected function formatToBase64(array $keys, array $attributes): array
    {
        foreach ($keys as $key) {
            if(isset($attributes[$key])){
                $attributes[$key] = base64_encode($attributes[$key]);
            }
        }
        return $attributes;
    }

    /**
     * formating  keys to base base64
     * @param array $keys the key to format
     * @param array $attributes to be formated
     */
    protected function formatFromBase64(array $keys, array $attributes): array
    {
        foreach ($keys as $key) {
            if(isset($attributes[$key])){
                $attributes[$key] = base64_decode($attributes[$key]);
            }
        }
        return $attributes;
    }
}
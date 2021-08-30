<?php

namespace Mouadbnl\Judge0\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Services\SubmissionConfig;
use Mouadbnl\Judge0\Services\SubmissionParams;

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
        'stdout', 'stderr'
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
        if($this->judged){
            if(config('judge0.throw_error_on_resubmit')){
                throw new Exception("This submission has already been judged, and can not be rejudged");
            }
            return $this;
        }
        $res = Judge0::postSubmission($this);
        if(isset($res['content']['token'])){
            $this->update([
                'token' => $res['content']['token'],
            ]);
        }
        if(isset($res['content']['status'])){
            $this->update([
                'status' => $res['content']['status']
            ]);
        }
        return $res;
    }

    public static function retrieve(string $token)
    {
        $res = Judge0::getSubmission($token);
        $submission = self::where('token', '=', $token)->firstOrFail();
        if(isset($res['content']['status'])){
            $submission->update([
                'status' => $res['content']['status']
            ]);
        }
        return $res;
    }

    public function retrieveFromJudge()
    {
        $res = Judge0::getSubmission($this->token);
        $content = $res['content'];
        if(isset($content['status'])){
            $this->update([
                'status' => $content['status'],
                'stdout' => $content['stdout'],
                'stderr' => $content['stderr'],
                'time' => $content['time'],
                'memory' => $content['memory'],
                'compile_output' => $content['compile_output'],
                'judged' => true,
            ]);
        }
        return $res;
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
    /*
    |--------------------------------------------------------------------------
    | 
    |--------------------------------------------------------------------------
    */

    public function getAllAttributes()
    {
        $attrs = array_merge(
            $this->attributes,
            $this->getConfigAttribute(),
            $this->getParamsAttribute()
        );

        if($this->params['base64']){
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
        return SubmissionParams::init($this->getParamsAttribute())->getUrl();
    }
    /*
    |--------------------------------------------------------------------------
    | 
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
}
<?php

namespace Mouadbnl\Judge0\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
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

    public function setConfigAttribute()
    {
        throw new Exception("Can not set config directly in the attribute, please use setConfig() on you model");
    }

    public function getParamsAttribute()
    {
        return  json_decode($this->getAttributes()['params'], true);
    }

    public function setParamsAttribute()
    {
        throw new Exception("Can not set params directly in the attribute, please use setParams() on you model");
    }
}
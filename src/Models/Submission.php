<?php

namespace Mouadbnl\Judge0\Models;

use Illuminate\Database\Eloquent\Model;
use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\SubmissionParams;
use Mouadbnl\Judge0\Traits\HasSubmissionConfig;
use Mouadbnl\Judge0\Traits\HasSubmissionParams;

class Submission extends Model
{
    use HasSubmissionConfig;
    use HasSubmissionParams;
    
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->config = SubmissionConfig::init();
        $this->params = SubmissionParams::init();

        parent::__construct($attributes);
    }

    public function getTable()
    {
        return config('judge0.table_names.submissions', parent::getTable());
    }
}
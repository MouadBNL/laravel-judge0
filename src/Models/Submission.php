<?php

namespace Mouadbnl\Judge0\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('judge0.table_names.submissions', parent::getTable());
    }
}
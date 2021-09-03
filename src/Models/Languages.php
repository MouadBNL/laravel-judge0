<?php

namespace Mouadbnl\Judge0\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected $guarded = [];

    public function getTable()
    {
        return config('judge0.table_names.languages', parent::getTable());
    }
}
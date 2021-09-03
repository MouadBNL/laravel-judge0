<?php

namespace Mouadbnl\Judge0\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mouadbnl\Judge0\Models\Submission;

trait Submitter
{
    public function submissions(): MorphMany
    {
        return $this->morphMany(Submission::class, 'submitter');
    }
}
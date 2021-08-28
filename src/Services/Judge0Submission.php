<?php

namespace Mouadbnl\Judge0\Services;

use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionParams;

class Judge0Submission
{


    // TODO verify that the language_id exists (add languages to config file)
    public static function validate(array $submission)
    {
        // validating source_code
        if(! isset($submission['source_code'])){
            throw new InvalidArgumentException("source_code is required.");
        }

        // validating language_id
        if(! isset($submission['language_id'])){
            throw new InvalidArgumentException("language_id is required.");
        }
        if(! is_numeric($submission['language_id'])){
            throw new InvalidArgumentException("language_id must be numeric.");
        }

        // validating stdin
        if( isset($submission['stdin']))
        {
            if(! is_string($submission['stdin'])){
                throw new InvalidArgumentException("stdin must be a string.");
            }
        }

        // validating expected_output
        if( isset($submission['expected_output']))
        {
            if(! is_string($submission['expected_output'])){
                throw new InvalidArgumentException("expected_output must be a string.");
            }
        }

        return [
            'source_code' => $submission['source_code'],
            'language_id' => $submission['language_id'],
            'stdin' => $submission['stdin'],
            'expected_output' => $submission['expected_output'],
            'validated' => true
        ];
    }

    public static function formatToBase64(array $submission){
        if(! $submission['validated']) $submission = Self::validate($submission);

        $submission['source_code'] = base64_encode($submission['source_code']);
        if(isset($submission['stdin'])) $submission['stdin'] = base64_encode($submission['stdin']);
        if(isset($submission['expected_output'])) $submission['expected_output'] = base64_encode($submission['expected_output']);

        return $submission;
    }
}

<?php

namespace Mouadbnl\Judge0;

use InvalidArgumentException;

class SubmissionParams
{
    public 	bool 	$base64 = false;
    public 	bool	$wait = false;
    public 	string 	$fields = "*";

    public function __construct(array $params = [])
    {
        $this->base64 = $params['base64'] ?? config('judge0.submission_params.base64');
        $this->wait = $params['wait'] ?? config('judge0.submission_params.wait');
        $this->fields = $params['fields'] ?? config('judge0.submission_params.fields');
    }
    
    // Adding a static constructor
    public static function init(array $config = []): self
    {
        return new self($config);
    }

    public function set(string $key, string $value)
    {
        if(! property_exists(Self::class, $key)){
            throw new InvalidArgumentException("Error property '" . $key . "' not found");
        }
        
        $this->$key = $value;
        return $this;
    }
}
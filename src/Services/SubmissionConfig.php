<?php

namespace Mouadbnl\Judge0\Services;

use Exception;
use InvalidArgumentException;

class SubmissionConfig
{
    protected $configKeys = [
        'cpu_time_limit'                                => ['float', 'integer', 'double'],
        'cpu_extra_time'                                => ['float', 'integer', 'double'],
        'wall_time_limit'                               => ['float', 'integer', 'double'],
        'memory_limit'                                  => ['integer'],
        'stack_limit'                                   => ['integer'],
        'max_processes_and_or_threads'                  => ['integer'],
        'enable_per_process_and_thread_time_limit'      => ['boolean'],
        'enable_per_process_and_thread_memory_limit'    => ['boolean'],
        'max_file_size'                                 => ['integer'],
        'redirect_stderr_to_stdout'                     => ['boolean'],
        'enable_network'                                => ['boolean'],
        'number_of_runs'                                => ['integer'],
    
        // will send a PUT request with the submission in the body
        'callback_url'                                  => ['string', 'null'],
        'compiler_options'                              => ['string', 'null'],
        'command_line_arguments'                        => ['string', 'null'],
    
        //Additional files that should be available alongside the source code in base64.
        'additional_files'                              => ['string', 'null']
    ];

    protected array $config;

    public function __construct(array $config)
    {
        foreach ($this->configKeys as $key => $types) {
            $value = $config[$key] ?? config('judge0.submission_config.' . $key);
            $this->valdiateKeyValue($key, $value);
            $this->config[$key] = $value;
        }
    }
    
    /**
     * Static constructor
     * @param array $config the config keys to override the defaults
     * @return self
     */
    public static function init(array $config = []): self
    {
        return new self($config);
    }

    /**
     * Changing something in the default config
     * @param string $key key of the config to set
     * @param any $value the value of the new config
     * @return self
     */
    public function set(string $key, $value): self
    {
        $this->valdiateKeyValue($key, $value);
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * @param string $key if null will return the whole config,
     *              else return the value of the key in the config
     * @return array $config if the key is null
     * @return string $value of the config key 
     */
    public function getConfig(string $key = null)
    {
        return $key ? $this->config[$key] : $this->config;
    }

    /**
     * override the config keys provided 
     * @param array $arr the config array to eb merged
     */
    public function mergeWith(array $arr)
    {
        foreach ($this->config as $key => $value) {
            $arr[$key] = $value;
        }
        return $this;
    }

    protected function valdiateKeyValue(string $key, $value)
    {
        if(! array_key_exists($key, $this->configKeys))
        {
            throw new InvalidArgumentException("SubmissionConfig does not contain ". $key .".");
        }

        $types = $this->configKeys[$key];
        $type = strtolower(gettype($value));
        if(! in_array($type, $types)){
            throw new InvalidArgumentException("Invalid type, " . $key . " must be of type ". implode(', ', $types) .".");
        }
    }
}
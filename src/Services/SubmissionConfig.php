<?php

namespace Mouadbnl\Judge0\Services;

use Exception;
use InvalidArgumentException;

class SubmissionConfig
{
    protected $configItems = [
        'cpu_time_limit'                                => ['float'],
        'cpu_extra_time'                                => ['float'],
        'wall_time_limit'                               => ['float'],
        'memory_limit'                                  => ['float'],
        'stack_limit'                                   => ['float'],
        'max_processes_and_or_threads'                  => ['int'],
        'enable_per_process_and_thread_time_limit'      => ['bool'],
        'enable_per_process_and_thread_memory_limit'    => ['bool'],
        'max_file_size'                                 => ['int'],
        'redirect_stderr_to_stdout'                     => ['bool'],
        'enable_network'                                => ['bool'],
        'number_of_runs'                                => ['int'],
    
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
        // TODO add validation here
        foreach ($this->configItems as $key => $types) {
            $this->config[$key] = $config[$key] ?? config('judge0.submission_config.' . $key);
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
     * @param string $value the value of the new config
     * @return self
     */
    public function set(string $key, string $value): self
    {
        // TODO add validation here for the the provided key
        if(! array_key_exists($key, $this->config)){
            throw new InvalidArgumentException("Error property '" . $key . "' not found");
        }
        
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
}
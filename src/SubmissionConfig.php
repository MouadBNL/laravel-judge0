<?php

namespace Mouadbnl\Judge0;

use Exception;
use InvalidArgumentException;

class SubmissionConfig
{
    public 	float   $cpu_time_limit = 2;
    public 	float   $cpu_extra_time = 1;
    public 	float   $wall_time_limit = 10;
    public 	float   $memory_limit = 256000;
    public 	float   $stack_limit = 64000;
    public 	int     $max_processes_and_or_threads = 120;
    public 	bool    $enable_per_process_and_thread_time_limit = false;
    public 	bool    $enable_per_process_and_thread_memory_limit = false;
    public 	Int     $max_file_size = 4096;
    public 	bool    $redirect_stderr_to_stdout = false;
    public 	bool    $enable_network = false;
    public 	int 	$number_of_runs = 1;
    // will send a PUT request with the submission in the body
    public 	?string $callback_url = null;
    public 	?string $compiler_options = null;
    public 	?string $command_line_arguments = null;
    //Additional files that should be available alongside the source code in base64.
    public 	?string $additional_files = null; 

    public function __construct(array $config)
    {
        $this->cpu_time_limit = $config['cpu_time_limit'] ?? config('judge0.submission_config.cpu_time_limit');
        $this->cpu_extra_time = $config['cpu_extra_time'] ?? config('judge0.submission_config.cpu_extra_time');
        $this->wall_time_limit = $config['wall_time_limit'] ?? config('judge0.submission_config.wall_time_limit');
        $this->memory_limit = $config['memory_limit'] ?? config('judge0.submission_config.memory_limit');
        $this->stack_limit = $config['stack_limit'] ?? config('judge0.submission_config.stack_limit');
        $this->max_processes_and_or_threads = $config['max_processes_and_or_threads'] ?? config('judge0.submission_config.max_processes_and_or_threads');
        $this->enable_per_process_and_thread_time_limit = $config['enable_per_process_and_thread_time_limit'] ?? config('judge0.submission_config.enable_per_process_and_thread_time_limit');
        $this->enable_per_process_and_thread_memory_limit = $config['enable_per_process_and_thread_memory_limit'] ?? config('judge0.submission_config.enable_per_process_and_thread_memory_limit');
        $this->max_file_size = $config['max_file_size'] ?? config('judge0.submission_config.max_file_size');
        $this->redirect_stderr_to_stdout = $config['redirect_stderr_to_stdout'] ?? config('judge0.submission_config.redirect_stderr_to_stdout');
        $this->enable_network = $config['enable_network'] ?? config('judge0.submission_config.enable_network');
        $this->number_of_runs = $config['number_of_runs'] ?? config('judge0.submission_config.number_of_runs');
        $this->callback_url = $config['callback_url'] ?? config('judge0.submission_config.callback_url');
        $this->compiler_options = $config['compiler_options'] ?? config('judge0.submission_config.compiler_options');
        $this->command_line_arguments = $config['command_line_arguments'] ?? config('judge0.submission_config.command_line_arguments');
        $this->additional_files = $config['additional_files'] ?? config('judge0.submission_config.additional_files');
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
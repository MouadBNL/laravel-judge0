# Draft on how I will structure this package
The goal here is to provide a simple way to interact with the [Judge0](https://github.com/judge0/judge0) API.
Check the [Judge0 documentation](https://github.com/judge0/judge0/tree/master/docs/api).

## First step
Creating the basic commands that interact directly with judge0 with little to no modifications on the inputs.

### Posting submission
```php
Judge0::postSubmission(
    string  $source_code,
    int     $language_id,
    ?string $stdin = null,
    ?string $expected_output = null,

	// Submission configuration
    float   $cpu_time_limit = 2,
    float   $cpu_extra_time = 1,
    float   $wall_time_limit = 10,
    float   $memory_limit = 256000,
    float   $stack_limit = 64000,
    int     $max_processes_and_or_threads = 120,
    bool    $enable_per_process_and_thread_time_limit = false,
    bool    $enable_per_process_and_thread_memory_limit = false,
    Int     $max_file_size = 4096,
    bool    $redirect_stderr_to_stdout = false,
    bool    $enable_network = false,
	int 	$number_of_runs = 1,
	?string $callback_url = null, // will send a PUT request with the submission in the body
	?string $compiler_options = null,
	?string $command_line_arguments = null,
	?string $additional_files = null, //Additional files that should be available alongside the source code in base64.

	// params
	bool 	$base64 = false,
	bool	$wait = false,
	string 	$fields = "*"
);
```
--------------------------------------------------------------------------------------------------------------------------------
Maybe extracting a `SubmissionConfig` and `SubmissionParams` Classes to simplify this further, 
also to easily load default variables form the `configuration file`. 

`SubmissionConfig.php`
```php
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
	public 	?string $callback_url = null; // will send a PUT request with the submission in the body
	public 	?string $compiler_options = null;
	public 	?string $command_line_arguments = null;
	public 	?string $additional_files = null; //Additional files that should be available alongside the source code in base64.

	public function __construct(array $config)
	{
		$this->cpu_time_limit = $config['cpu_time_limit'] ?? config('judge0.cpu_time_limit');
		$this->cpu_extra_time = $config['cpu_extra_time'] ?? config('judge0.cpu_extra_time');
		$this->wall_time_limit = $config['wall_time_limit'] ?? config('judge0.wall_time_limit');
		$this->memory_limit = $config['memory_limit'] ?? config('judge0.memory_limit');
		$this->stack_limit = $config['stack_limit'] ?? config('judge0.stack_limit');
		$this->max_processes_and_or_threads = $config['max_processes_and_or_threads'] ?? config('judge0.max_processes_and_or_threads');
		$this->enable_per_process_and_thread_time_limit = $config['enable_per_process_and_thread_time_limit'] ?? config('judge0.enable_per_process_and_thread_time_limit');
		$this->enable_per_process_and_thread_memory_limit = $config['enable_per_process_and_thread_memory_limit'] ?? config('judge0.enable_per_process_and_thread_memory_limit');
		$this->max_file_size = $config['max_file_size'] ?? config('judge0.max_file_size');
		$this->redirect_stderr_to_stdout = $config['redirect_stderr_to_stdout'] ?? config('judge0.redirect_stderr_to_stdout');
		$this->enable_network = $config['enable_network'] ?? config('judge0.enable_network');
		$this->number_of_runs = $config['number_of_runs'] ?? config('judge0.number_of_runs');
		$this->callback_url = $config['callback_url'] ?? config('judge0.callback_url');
		$this->compiler_options = $config['compiler_options'] ?? config('judge0.compiler_options');
		$this->command_line_arguments = $config['command_line_arguments'] ?? config('judge0.command_line_arguments');
		$this->additional_files = $config['additional_files'] ?? config('judge0.additional_files');
	}

	// Adding a static constructor
	public static init(array $config): self
	{
		return new self($config);
	}
}
```

`SubmissionParams.php`
```php
class SubmissionParams
{
	public 	bool 	$base64 = false;
	public 	bool	$wait = false;
	public 	string 	$fields = "*";

	public function __construct(array $params)
	{
		$this->base64 = $params['base64'] ?? config('judge0.base64');
		$this->wait = $params['wait'] ?? config('judge0.wait');
		$this->fields = $params['fields'] ?? config('judge0.fields');
	}
}
```

```php
Judge0::postSubmission(
    string  $source_code,
    int     $language_id,
    ?string $stdin = null,
    ?string $expected_output = null,
	SubmissionConfig $config,
	SubmissionParams $params	
);
```

### Getting a submission
```php
Judge0::getSubmission(string $token);
```
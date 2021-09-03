# Submission config
Here we will discuss the submission config

## Attributes of the configuration
There are many different attributes you can change about the submission configuration
I strongly recomande to read [Judge0 Documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md).
This is the full list of what configuration you can set on a submission
|Name |Type |Unit |Description |
|:---:|:----|:----|:-----------|
|`cpu_time_limit` |`float`, `integer`, `double` |second |Time limit for judging the submission |
|`cpu_extra_time` |`float`, `integer`, `double` |second |Some extra time for the program to run, but the submission will still be judge as TLE |
|`wall_time_limit` |`float`, `integer`, `double` |second |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`memory_limit` |`integer` |kilobyte |Limit address space of the program. |
|`stack_limit` |`integer` | |Limit process stack. |
|`max_processes_and_or_threads` |`integer` | |Maximum number of processes and/or threads program can create. |
|`enable_per_process_and_thread_time_limit` |`boolean` | |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`enable_per_process_and_thread_memory_limit` |`boolean` | |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`max_file_size` |`integer` |kilobyte |Limit file size created or modified by the program. |
|`redirect_stderr_to_stdout` |`boolean` | |If `true` standard error will be redirected to standard output. **Recommend to keep it false**. |
|`enable_network` |`boolean` | |If true program will have network access. **Recommend to keep it false**.  |
|`number_of_runs` |`integer` | |Run each program number_of_runs times and take average of time and memory. |
|`callback_url` |`string`, `null` | |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`compiler_options` |`string`, `null` | |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`command_line_arguments` |`string`, `null` | |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |
|`additional_files` |`string`, `null` |**base64** |[See judge0 documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md) |

## Working with a SubmissionConfig instance

### Creating a SubmissionConfig instance
Create a instance of the submission config:
```php
use Mouadbnl\Judge0\Services\SubmissionConfig;

$conf = new SubmissionConfig();

// Using a static constructor
$conf = new SubmissionConfig::init();
```
The instance created will inherite the values in the `judge0` config file.
If you want to override any value:
```php
$conf = new SubmissionConfig([
    'cpu_time_limit' => 1.2
]);

// Using a static constructor
$conf = new SubmissionConfig::init([
    'cpu_time_limit' => 1.2
]);

// Using the set method
$conf->set('cpu_time_limit', 1.2);
```
### Getting a key or the whole config
To get a specific key of the config use:
```php
$conf->getConfig('cpu_time_limit'); // 1.2
```
Or if you want to get the whole config as an array, don't pass any key, like:
```php
$conf->getConfig(); // return an array containing all the config.
```


## Using an instance of the configuration on a submission
By default, any submission created will have a new instance of the submission config.
if you want to change the instance, all you need to do is to affect the instance you created to the `config` attribute of the submission:
```php
$submission->config = SubmissionConfig::init([
    'cpu_time_limit' => 1.2
]);
```
But there is an easy way to change any config on submission wihtou creating a new instance:
```php
$submission->setConfig('cpu_time_limit', 1.2)
    ->setConfig('memory_limit', 10240);
```
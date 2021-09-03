# Submissions
The submission accepts a polymorphic relationship `submitter`, the models that are allowed to send a submission must have `Mouadbnl\Judge0\Traits\Submitter` trait.
```php
use Mouadbnl\Judge0\Traits\Submitter;
class User extends Authenticatable
{
    use Submitter;
    /** **/
}
```
For the rest of this documentation, let's assume that the `User model` has this trait.
## Submission attributes
The `Submission model` has a number of attributes:
- `token` : Which is given to the submission by the judge0 API, and it's a unique key that helps us retrive the submission later from judge0.
- `status` : The status of the submission which is an `object` that has an `id` of `0` and a `description` of `Waiting` by default, and updated when the submission is sent to judge0.
- `language_id` : The `id` of the language used in the submission.
- `source_code` : The source code to be judged.
- `stdin` : The input to give the the source code when running.
- `stdout`: The output of the source code.
- `stderr` : Error output of the source code.
- `expected_output` : The expected output of the submission.
- `compile_output` : The compiler output.
- `time` : The total time that took the source code to run, `**and it is not the time limit**`, the time limit is given in the configuration. please check the documentation for the submission configuration.
- `memory` : The total memry that the source code used, `**and it is not the time limit**`, the memory limit is given in the configuration. please check the documentation for the submission configuration.
- `confi` : An instance of `SubmissionConfig` class.
- `params` : An instance of `SubmissionParams` class.
- `response` : The responce of judge0 API call when submittion, **to be used for debuging only**.
- `judged` : A `boolean` of whether the submission was judged on not.
### Config And params
The config and params are automatically casted when accessing / assigning them, and stored and an a `JSON array`.
## Setting params and config on a submission
The `setConfig()` and `setParams()` methods are available in the `Submission model` for ease of access.
```php
$submission->setConfig('cpu_time_limit', 1.2)
        ->setParams('base64', false);
```
### Crutial attributes (Time limit, Memory limit, Expected output and Input.)
It is also possible to set the most crutial elements of the submission easily
#### Time limit
You can set the time limit using any of the following:
```php
// In seconds
$submission->setTimeLimit(1.2);

// In milliseconds
$submission->setTimeLimitInMilliseconds(1200);

// Set the value from the judge0 config file
$submission->setDefaultTimeLimit();
```
#### Memory limit
You can set the memory limit using any of the following:
```php
// In kilobytes
$submission->setMemoryLimit(10240); // must be over 2048

// In megabytes
$submission->setMemoryLimitInMegabytes(10);

// Set the value from the judge0 config file
$submission->setDefaultMemoryLimit();
```
#### Input
You can set the input using any of the following:
```php
$submission->setInput('judge0');

$submission->setStdin('judge0');
```
#### Expected output
You can set the expected output using the following:
```php
$submission->ExpectedOutput('judge0');
```
### Manipulating Config
#### Get a config key
```php
$submission->getConfig('cpu_time_limit');
```
#### Set a config key / array
```php
$submission->setConfig('cpu_time_limit', 1.2);

$submission->setConfiguration([
    'memory_limit' => 10240, // always in kilobytes
    'cpu_extra_time' => 1
]);
```
### Manipulating Params
#### Get a params key
```php
$submission->getParams('base64');
```
#### Set a params key / array
```php
$submission->setParams('base64', false);

$submission->setParamsuration([
    'wait' => false,
    'fields' => '*'
]);
```

## Creating and Submitting 
### Anonymously
To create an anonymous submission, just create a normal instance of the `Submission model`:
```php
use Mouadbnl\Judge0\Models\Submission;

$submission = Submission::create([
        'language_id' => 54, // C++ (GCC 9.2.0)
        'source_code' =>'
        #include<iostream>
        #include<string>
        using namespace std;

        int main(){
            string s;
            cin >> s;
            cout << "the value you entered is : " << s;
            return 0;
        }
        '
    ])
    ->setInput('judge0')
    ->setExpectedOutput('the value you entered is : judge0')
    ->setTimeLimit(1) // seconds
    ->setMemoryLimitInMegabytes(256);
```
And to submit:
```php
$submission->submit();
```
### Using submitter relationship.
To send a submission for a submitter, use the relationship:
```php
$submission = $user->submissions()->create([
        'language_id' => 54, // C++ (GCC 9.2.0)
        'source_code' =>'
        #include<iostream>
        #include<string>
        using namespace std;

        int main(){
            string s;
            cin >> s;
            cout << "the value you entered is : " << s;
            return 0;
        }
        '
    ])
    ->setInput('judge0')
    ->setExpectedOutput('the value you entered is : judge0')
    ->setTimeLimit(1) // seconds
    ->setMemoryLimitInMegabytes(256);
```
And to submit:
```php
$submission->submit();
```

## Submission Locking
After being judged, the submission is locked from modifications, and any attempt of modification will throw an exception saying that the submission has been locked.
To disable this, change the `lock_submisson_after_judging` key in the judge0 config file to `false`.
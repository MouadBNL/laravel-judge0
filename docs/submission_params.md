# Submission params
Here we will discuss the submission parameters.

## Attributes of the parameters
There are 3 attributes in the params.
I strongly recommend to read [Judge0 Documentation](https://github.com/judge0/judge0/blob/master/docs/api/submissions/submissions.md).
|Name |Type |Description |
|:----|:----|:-----------|
|`base64` |`boolean` |Encoding the following attributes in base64. `source_code`, `stdin`, `expected_output`, `stdout`, `stderr`, `compile_output`. |
|`wait` | `boolean` |Wait for the judge0 to run the submission and return it, and keeping connection on hold, also defined as long polling. |
|`fields` |`string`, `null` |The fields to return for a submission in judge0, `stdout,time,memory,stderr,token,compile_output,message,status...` |

## Working with a SubmissionParams instance

### Creating a SubmissionParams instance
Create a instance of the submission params:
```php
use Mouadbnl\Judge0\Services\SubmissionParams;

$params = new SubmissionParams();

// Using a static constructor
$params = new SubmissionParams::init();
```
The instance created will inherite the values in the `submission_params` key in the `judge0` config file.
If you want to override any value:
```php
$params = new SubmissionParams([
    'wait' => true
]);

// Using a static constructor
$params = new SubmissionParams::init([
    'wait' => true
]);

// Using the set method
$params->set('wait', true);
```
### Getting a key or the whole Params
To get a specific key of the Params use:
```php
$params->getParams('wait'); // true
```
Or if you want to get the whole Params as an array, don't pass any key, like:
```php
$params->getParams(); // return an array containing all the Params.
```
### Getting a formated url query
Getting a formated url, used in Judge0 API requests.
```php
$params->getUrl(); // ?base64_encode=true&wait=true&fields=*
```

## Using an instance of the parameters on a submission
By default, any submission created will have a new instance of the `SubmissionParams`.
if you want to change the instance, all you need to do is to affect the instance you created to the `params` attribute of the submission:
```php
$submission->params = SubmissionParams::init([
    'wait' => true
]);
```
But there is an easy way to change any params on submission wihtou creating a new instance:
```php
$submission->setParams('wait', true)
    ->setParams([
        'base64' => true,
        'fields' => '*'
    ]);
```

## Validation
This package provide two ways for validating the params.
First, is by vilidating each key of the params.
```php
use Mouadbnl\Judge0\Validators\ValideParamsKey;
validator([
    'params' => [
        'cpu_time_limit' => 1.2,
        'memory_limit' => 20480
    ],
    [
        'params.*' => ['required', new ValideParamsKey]
    ]
]);
```
Or, validate the whole array with one rule.
**The Following rule check key by key, if a key is missing, it is ignored since it will be loaded with a default value fron the configuration file**.
```php
use Mouadbnl\Judge0\Validators\ValideParams;
validator([
    'params' => [
        'cpu_time_limit' => 1.2,
        'memory_limit' => 20480
    ],
    [
        'params' => [new ValideParams]
    ]
]);
```
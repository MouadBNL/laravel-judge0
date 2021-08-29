# Laravel Judge0
Judge0 API integration for `running/judging` code with different languages
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
    ->setMemoryLimitInMegabytes(256)
    ->submit();
```

## Installation

You can install the package via composer:

```bash
composer require mouadbnl/laravel-judge0
```
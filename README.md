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
You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Mouadbnl\Judge0\Judge0ServiceProvider" --tag="judge0-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Mouadbnl\Judge0\Judge0ServiceProvider" --tag="judge0-config"
```

## Usage

First add ```Submitter``` trait to the User Model.

```php
use Mouadbnl\Judge0\Traits\Submitter;
class User extends Authenticatable
{
    use Submitter;
    /** **/
}
```

This create a polymorphique relationship between the User model and the Submission model provided by this package.
You can let the User make a submission using the follwing code:

```php
$user = User::firstOrFail();
$user->submissions()->create([
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
## Define the API to use
This package provides two drivers to connect to judge0 API
### Instance Driver
This allows you to connect to a judge0 Docker instance. to do so please define the `JUDGE0_BASE_URI` which is the url to your instance and `JUDGE0_KEY` which it the API key to authenticate in your `environment variables` or `.env` file.
```bash
JUDGE0_BASE_URI=localhost:2358
JUDGE0_KEY=yout_key
```
### Rapidapi Driver
This allows you to connect to the the [Judge0 app on RapidAPI](https://rapidapi.com/judge0-official/api/judge0-ce/).
For this you need to define the following in your `environment variables` or `.env` file. 
- `JUDGE0_RAPIDAPI_BASE_URI` the rapidapi url to make the requests to, which it by default `https://judge0-ce.p.rapidapi.com`.
- `JUDGE0_RAPIDAPI_HOST`, by default it is `judge0-ce.p.rapidapi.com`.
- `JUDGE0_RAPIDAPI_KEY` yout rapidapi key.
```bash
JUDGE0_RAPIDAPI_BASE_URI=https://judge0-ce.p.rapidapi.com
JUDGE0_RAPIDAPI_HOST=judge0-ce.p.rapidapi.com
JUDGE0_RAPIDAPI_KEY=yout_key
```
## Testing

```bash
composer test
```

## Credits

- [Mouad Benali](https://github.com/MouadBNL)
- [Spatie](https://github.com/spatie) For the skeleton and [Package Training](https://laravelpackage.training/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
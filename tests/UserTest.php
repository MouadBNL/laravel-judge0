<?php

namespace Mouadbnl\Judge0\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Mouadbnl\Judge0\Traits\Submitter;

class UserTest extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authenticatable;
    use Authorizable;
    use Submitter;

    protected $table = 'users';
    protected $guarded = [];

    public static function CreateDummyUser()
    {
        return (self::create([
            'name' => 'John',
            'email' => 'john@doe.com',
            'password' => 'password',
        ]));
    }
}
<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Mouadbnl\Judge0\Tests\TestCase;
use Mouadbnl\Judge0\Validators\ValideParams;
use Mouadbnl\Judge0\Validators\ValideParamsKey;

class ParamsValidatorTest extends TestCase
{
    /** @test */
    public function it_validates_key()
    {
        // valide
        $validator = validator(
            [
                'base64' => true
            ],
            [
                'base64' => ['required',new ValideParamsKey]
            ]
        );

        $this->assertFalse($validator->fails());

        // invalide
        $validator = validator(
            ['wertyui' => true],
            ['wertyui' => ['required',new ValideParamsKey]]
        );

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_validates_all_array_of_params()
    {
        // valide
        $data = [
            'params' => [
                'base64' => true,
                'fields' => "*"
            ]
        ];
        $validator = validator($data, [
            'params' => [new ValideParams]
        ]);

        $this->assertFalse($validator->fails());


        // invalide
        $data = [
            'params' => [
                'base64' => true,
                'memssory_limit' => 20480
            ]
        ];
        $validator = validator($data, [
            'params' => [new ValideParams]
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals('The memssory limit must be a valide params key.', $validator->errors()->first());
    }
}
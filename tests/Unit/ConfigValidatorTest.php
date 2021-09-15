<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Mouadbnl\Judge0\Tests\TestCase;
use Mouadbnl\Judge0\Validators\ValideConfig;
use Mouadbnl\Judge0\Validators\ValideConfigKey;

class ConfigValidatorTest extends TestCase
{
    /** @test */
    public function it_validates_key()
    {
        // valide
        $validator = validator(
            [
                'cpu_time_limit' => 1.2
            ],
            [
                'cpu_time_limit' => ['required',new ValideConfigKey]
            ]
        );

        $this->assertFalse($validator->fails());

        // invalide
        $validator = validator(
            ['wertyui' => 1.2],
            ['wertyui' => ['required',new ValideConfigKey]]
        );

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_validates_all_array_of_config()
    {
        // valide
        $data = [
            'config' => [
                'cpu_time_limit' => 1.2,
                'memory_limit' => 20480
            ]
        ];
        $validator = validator($data, [
            'config' => [new ValideConfig]
        ]);

        $this->assertFalse($validator->fails());


        // invalide
        $data = [
            'config' => [
                'cpu_time_limit' => 1.2,
                'memssory_limit' => 20480
            ]
        ];
        $validator = validator($data, [
            'config' => [new ValideConfig]
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals('The memssory limit must be a valide config key.', $validator->errors()->first());
    }
}
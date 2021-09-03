<?php

namespace Mouadbnl\Judge0\Tests\Feature;

use Mouadbnl\Judge0\Tests\TestCase;
use Mouadbnl\Judge0\Tests\UserTest;

class SubmitterTest extends TestCase
{
    /** @test */
    public function submitter_can_submit()
    {
        $user = UserTest::CreateDummyUser();

        $submission = $user->submissions()->create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->submit();

        $this->assertTrue($submission->exists);
        $this->assertEquals($user->id, $submission->submitter->id);
    }
}
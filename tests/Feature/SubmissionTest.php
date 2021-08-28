<?php

namespace Mouadbnl\Judge0\Tests\Feature;

use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\Tests\TestCase;

class SubmissionTest extends TestCase
{
    /** @test */
    public function it_can_create_a_basic_submission()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $this->assertTrue($submission->exists);
    }
}
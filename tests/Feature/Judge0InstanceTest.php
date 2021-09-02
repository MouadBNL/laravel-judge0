<?php

namespace Mouadbnl\Judge0\Tests\Feature;

use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\Tests\TestCase;

class Judge0InstanceTest extends TestCase
{
    /** @test */
    public function it_can_authenticate()
    {
        $res = Judge0::authenticate();
        $this->assertEquals(200, $res['code']);    
    }

    /** @test */
    public function it_can_send_post_submission()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);
        $res = Judge0::postSubmission($submission);

        $this->assertArrayHasKey('code', $res);
        $this->assertEquals(201, $res['code']);
    }

    /* ------------------------------------------------------------------------------ */

    /** @test */
    public function get_languages()
    {
        $res = Judge0::getLanguages();
        
        $this->assertEquals(200, $res['code']);
    }

    /** @test */
    public function get_all_languages()
    {
        $res = Judge0::getAllLanguages();
        
        $this->assertEquals(200, $res['code']);
    }

    /** @test */
    public function get_language()
    {
        $res = Judge0::getLanguage(71);

        $this->assertEquals(200, $res['code']);
        $this->assertEquals(71, $res['content']['id']);
    }

    /* ------------------------------------------------------------------------------ */

    /** @test */
    public function get_statuses()
    {
        $res = Judge0::getStatuses();

        $this->assertEquals(200, $res['code']);
    }

    /* ------------------------------------------------------------------------------ */

    /** @test */
    public function get_statistics()
    {
        $res = Judge0::getStatistics();

        $this->assertEquals(200, $res['code']);
    }

    /* ------------------------------------------------------------------------------ */

    /** @test */
    public function get_workers()
    {
        $res = Judge0::getWorkers();

        $this->assertEquals(200, $res['code']);
    }
}
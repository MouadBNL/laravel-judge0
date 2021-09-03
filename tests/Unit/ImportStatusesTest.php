<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Mouadbnl\Judge0\Models\Statuses;
use Mouadbnl\Judge0\Tests\TestCase;

class ImportStatusesTest extends TestCase
{
    /** @test */
    public function it_imports_statuses()
    {
        DB::table(config('judge0.table_names.statuses'))->delete();

        $this->assertTrue(Statuses::all()->isEmpty());

        $cmd = $this->artisan('judge0:import-statuses');
        $cmd->expectsConfirmation(
            'Do you want to rest the table ?',
            'yes'
        );

        $cmd->execute();

        $this->assertFalse(Statuses::all()->isEmpty());
    }
}
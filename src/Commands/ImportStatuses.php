<?php

namespace Mouadbnl\Judge0\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Models\Statuses;

class ImportStatuses extends Command
{
    protected $signature = 'judge0:import-statuses';

    protected $description = 'Import the submission statuses from judge0 API';

    public function handle()
    {
        $this->testConnection();

        $this->resetTable();

        $statuses = $this->loadStatuses();

        $this->insertStatuses($statuses);
    }

    protected function testConnection()
    {
        $this->info('Testing Judge0 Connection...');

        $res = Judge0::authenticate();
        if($res['code'] != 200) throw new Exception("Could not connect, response code ". $res['code'] . ".");

        $this->info('Connection is valid.');
    }

    protected function loadStatuses()
    {
        $this->info('Loading statuses...');

        $res = Judge0::getStatuses();
        if($res['code'] != 200) throw new Exception("Could not load statuses, Judge0 error message: ". $res['message'] ." .");
        
        $statuses = $res['content'];
        $this->info('Statuses Loaded.');

        return $statuses;
    }

    protected function insertStatuses($statuses)
    {
        $this->info('Inserting statuses in the database');

        $bar = $this->output->createProgressBar(count($statuses));

        $bar->start();

        foreach ($statuses as $status) {
            // $this->info('Inseting Status of id:'. $status->id);
            Statuses::firstOrCreate(['id' => $status->id], ['description' => $status->description]);
            $bar->advance();
        }

        $bar->finish();

        $this->info('Insertion done!');
    }

    protected function resetTable()
    {
        $this->info('Resetting the table will remove all content you have in the statuses table.');
        if($this->confirm('Do you want to rest the table ?', true))
        {
            $this->info('Resetting the table...');
            DB::table(config('judge0.table_names.statuses'))->delete();
            $this->info('Done resetting.');
        }
    }
}
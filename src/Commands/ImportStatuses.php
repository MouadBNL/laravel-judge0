<?php

namespace Mouadbnl\Judge0\Commands;

use Exception;
use Illuminate\Console\Command;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Models\Statuses;

class ImportStatuses extends Command
{
    protected $signature = 'judge0:import-statuses';

    protected $description = 'Import the submission statuses from judge0 API';

    public function handle()
    {
        $this->testConnection();

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

        foreach ($statuses as $status) {
            $this->info('Inseting Status of id:'. $status->id);
            Statuses::firstOrCreate(['id' => $status->id], ['description' => $status->description]);
        }
        $this->info('Insertion done!');
    }
}
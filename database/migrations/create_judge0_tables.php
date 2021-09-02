<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJudge0Tables extends Migration
{
    public function up()
    {
        $tables = config('judge0.table_names');

        if (empty($tables)) {
            throw new \Exception('Can not load table_names from config/judge0.php. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tables['submissions'], function (Blueprint $table) {
            $table->id();
            $table->string("token")->nullable();
            $table->json("status")->nullable();

            // $table->morphs('submitter');
            $table->integer("language_id");
            $table->string("language")->nullable();
            $table->longText("source_code");
            $table->longText("stdin")->nullable();
            $table->longText("stderr")->nullable();
            $table->longText("stdout")->nullable();
            $table->longText("expected_output")->nullable();
            $table->longText("compile_output")->nullable();
            $table->string("time")->nullable();
            $table->string("memory")->nullable();

            $table->json("config")->nullable();
            $table->json("params")->nullable();
            $table->json("response")->nullable();

            $table->boolean('judged')->default(0);

            $table->timestamps();
        });
    }
};
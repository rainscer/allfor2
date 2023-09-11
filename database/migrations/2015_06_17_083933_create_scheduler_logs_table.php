<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateSchedulerLogsTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            /*Schema::create(
                'scheduler_logs',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('job', 50)
                        ->index();
                    $table->string('description');
                    $table->timestamps();
                }
            );*/
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            /*Schema::drop('scheduler_logs');*/
        }

    }

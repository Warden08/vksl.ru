<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnLeagueUniversities extends Migration
{
    public function up()
    {
        Schema::create('wrdn_league_universities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('name');
            $table->string('short_name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_league_universities');
    }
}

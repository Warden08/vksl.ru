<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnLeagueTeams extends Migration
{
    public function up()
    {
        Schema::create('wrdn_league_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('team_name');
            $table->string('university');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_league_teams');
    }
}

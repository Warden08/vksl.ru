<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnLeagueTeamsUniversities extends Migration
{
    public function up()
    {
        Schema::create('wrdn_league_teams_universities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('team_id');
            $table->integer('university_id');
            $table->primary(['team_id','university_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_league_teams_universities');
    }
}

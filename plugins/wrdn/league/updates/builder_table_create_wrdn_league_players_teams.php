<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnLeaguePlayersTeams extends Migration
{
    public function up()
    {
        Schema::create('wrdn_league_players_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('player_id');
            $table->integer('team_id');
            $table->primary(['player_id','team_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_league_players_teams');
    }
}

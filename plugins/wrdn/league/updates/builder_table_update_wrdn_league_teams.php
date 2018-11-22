<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnLeagueTeams extends Migration
{
    public function up()
    {
        Schema::table('wrdn_league_teams', function($table)
        {
            $table->dropColumn('university');
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_league_teams', function($table)
        {
            $table->string('university', 191);
        });
    }
}

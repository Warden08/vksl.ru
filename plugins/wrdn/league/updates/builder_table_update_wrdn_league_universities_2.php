<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnLeagueUniversities2 extends Migration
{
    public function up()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->string('logo');
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->dropColumn('logo');
        });
    }
}

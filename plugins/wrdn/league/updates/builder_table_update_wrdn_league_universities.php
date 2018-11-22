<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnLeagueUniversities extends Migration
{
    public function up()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->string('location');
            $table->increments('id')->unsigned(false)->change();
            $table->string('short_name')->change();
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->dropColumn('location');
            $table->increments('id')->unsigned()->change();
            $table->string('short_name', 191)->change();
        });
    }
}

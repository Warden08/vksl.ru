<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnLeagueUniversities3 extends Migration
{
    public function up()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->string('slug');
            $table->string('logo')->change();
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_league_universities', function($table)
        {
            $table->dropColumn('slug');
            $table->string('logo', 191)->change();
        });
    }
}

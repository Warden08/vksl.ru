<?php namespace wrdn\League\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnLeaguePlayers extends Migration
{
    public function up()
    {
        Schema::create('wrdn_league_players', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('player_nickname');
            $table->string('name');
            $table->string('last_name');
            $table->integer('age');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_league_players');
    }
}

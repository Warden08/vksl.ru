<?php namespace wrdn\video\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWrdnVideoVideos extends Migration
{
    public function up()
    {
        Schema::create('wrdn_video_videos', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('img');
            $table->integer('date');
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wrdn_video_videos');
    }
}
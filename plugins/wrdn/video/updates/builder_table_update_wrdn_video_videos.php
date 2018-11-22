<?php namespace wrdn\video\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnVideoVideos extends Migration
{
    public function up()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->string('video_id');
            $table->increments('id')->unsigned(false)->change();
            $table->string('name')->change();
            $table->string('slug')->change();
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->dropColumn('video_id');
            $table->increments('id')->unsigned()->change();
            $table->string('name', 191)->change();
            $table->string('slug', 191)->change();
        });
    }
}

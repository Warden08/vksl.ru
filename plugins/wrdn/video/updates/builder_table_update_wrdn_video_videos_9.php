<?php namespace wrdn\video\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnVideoVideos9 extends Migration
{
    public function up()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->time('duration')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->string('duration', 10)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}

<?php namespace wrdn\video\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWrdnVideoVideos2 extends Migration
{
    public function up()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->string('video_url');
            $table->string('name')->change();
            $table->string('slug')->change();
            $table->dropColumn('video_id');
        });
    }
    
    public function down()
    {
        Schema::table('wrdn_video_videos', function($table)
        {
            $table->dropColumn('video_url');
            $table->string('name', 191)->change();
            $table->string('slug', 191)->change();
            $table->string('video_id', 191);
        });
    }
}

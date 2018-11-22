<?php namespace wrdn\video;

use System\Classes\PluginBase;
use Backend;
use Controller;
use Wrdn\Video\Models\Video;
use RainLab\Blog\Classes\TagProcessor;
use Event;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Wrdn\Video\Components\Video'       => 'videoVideo',
            'Wrdn\Video\Components\Videos'       => 'videoVideos',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            $videos = Video::get();
            foreach ($videos as $video) {
                $apikey = "AIzaSyAFWuyCE6ok1UsFD-fqtB6iyqcHO3ZEz64";
                $json_output = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$video->video_id."&key=".$apikey."&part=snippet,contentDetails,statistics,status");
                $json = json_decode($json_output,true);

                Video::where('id', $video->id)->update([
                    'likes' => $json['items'][0]['statistics']['likeCount'],
                    'views' => $json['items'][0]['statistics']['viewCount'],
                ]);
            }
        })->everyTenMinutes();
    }

    public function boot()
    {

        /*
         * Register menu items for the RainLab.Pages plugin
         */
        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'video-entry'           => 'video entry',
                'all-videos'      => 'all videos',
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function($type) {
            if ($type == 'video-entry' || $type == 'all-videos') {
                return Video::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            if ($type == 'video-entry' || $type == 'all-videos') {
                return Video::resolveMenuItem($item, $url, $theme);
            }
        });
    }
}

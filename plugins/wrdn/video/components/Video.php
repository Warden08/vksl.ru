<?php namespace Wrdn\Video\Components;

use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Wrdn\Video\Models\Video as videoVideo;

class Video extends ComponentBase
{
    /**
     * @var RainLab\Blog\Models\Post The post model used for display.
     */
    public $video;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'videoVideo',
            'description' => ''
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'rainlab.blog::lang.settings.post_slug',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],

        ];
    }
    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }


    public function onRun()
    {
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->video = $this->page['video'] = $this->loadVideo();
    }

    public function onRender()
    {
        if (empty($this->video)) {
            $this->video = $this->page['video'] = $this->loadVideo();
        }
    }

    protected function loadVideo()
    {
        $slug = $this->property('slug');

        $video = new videoVideo;

        $video = $video->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $video->transWhere('slug', $slug)
            : $video->where('slug', $slug);

        $video = $video->first();


        return $video;
    }

    public function previousPost()
    {
        return $this->getVideoSibling(-1);
    }

    public function nextPost()
    {
        return $this->getVideoSibling(1);
    }
    protected function getVideoSibling($direction = 1)
    {
        if (!$this->video) {
            return;
        }

        $method = $direction === -1 ? 'previousVideo' : 'nextVideo';

        if (!$video = $this->video->$method()) {
            return;
        }

        $videoPage = $this->getPage()->getBaseFileName();

        $video->setUrl($videoPage, $this->controller);

        return $video;
    }
}

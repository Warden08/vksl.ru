<?php namespace Wrdn\League\Components;

use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Wrdn\League\Models\University as TeamsUniversity;

class University extends ComponentBase
{
    public $team;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'teamsUniversity',
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
        $this->team = $this->page['team'] = $this->loadTeam();
    }

    public function onRender()
    {
        if (empty($this->team)) {
            $this->team = $this->page['team'] = $this->loadTeam();
        }
    }

    protected function loadTeam()
    {
        $slug = $this->property('slug');

        $team = new TeamsUniversity();

        $team = $team->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $team->transWhere('slug', $slug)
            : $team->where('slug', $slug);

        $team = $team->first();


        return $team;
    }

    public function previousTeam()
    {
    }

    public function nextTeam()
    {
    }
    protected function getTeamSibling($direction = 1)
    {
        if (!$this->team) {
            return;
        }

        $method = $direction === -1 ? 'previousTeam' : 'nextTeam';

        if (!$team = $this->team->$method()) {
            return;
        }

        $teamPage = $this->getPage()->getBaseFileName();

        $team->setUrl($teamPage, $this->controller);

        return $team;
    }
}

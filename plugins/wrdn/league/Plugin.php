<?php namespace wrdn\League;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Wrdn\League\Components\University'       => 'teamsUniversity',
        ];
    }

    public function registerSettings()
    {
    }
}

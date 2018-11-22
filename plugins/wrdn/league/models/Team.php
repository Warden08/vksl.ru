<?php namespace wrdn\League\Models;

use Model;

/**
 * Model
 */
class Team extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'wrdn_league_teams';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'universities' => ['Wrdn\League\Models\University',
            'table' => 'wrdn_league_teams_universities',
            'order' => 'name'
        ],
        'players' => ['Wrdn\League\Models\Player',
            'table' => 'wrdn_league_players_teams',
            'order' => 'player_nickname'
        ],
    ];
}

<?php namespace wrdn\League\Models;

use Model;

/**
 * Model
 */
class Player extends Model
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
    public $table = 'wrdn_league_players';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'teams' => ['Wrdn\League\Models\Team',
            'table' => 'wrdn_league_players_teams',
            'order' => 'team_name'
        ],
    ];
}

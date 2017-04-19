<?php

namespace Multiple\Frontend\Models;
use Phalcon\Mvc\Model;

class NbaTeam extends Model
{
    public function initialize()
    {
        $this->hasMany(
            'teamId',
            'Multiple\Frontend\Models\NbaInjuries',
            'teamId',
            [
                'alias' => 'Injuries',
            ]
        );
    }
}

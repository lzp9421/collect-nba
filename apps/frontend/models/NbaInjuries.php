<?php

namespace Multiple\Frontend\Models;
use Phalcon\Mvc\Model;

class NbaInjuries extends Model
{
    public function initialize()
    {
        $this->useDynamicUpdate(true);
        $this->belongsTo(
            "teamId",
            'Multiple\Frontend\Models\NbaTeam',
            "teamId",
            [
                'alias' => 'Team',
            ]
        );
    }
}

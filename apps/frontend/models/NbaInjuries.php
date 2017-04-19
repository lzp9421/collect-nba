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

    public function isNew()
    {
        date_default_timezone_set("PRC");
        return $this->commentCn == null && time() - strtotime($this->createtime) < 24 * 3600 ? true : false;
    }
}

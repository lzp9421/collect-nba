<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Models\NbaInjuries;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $injuries = NbaInjuries::find([
            'conditions' => 'isShow = 0 or isShow = 1',
        ]);
        $invalid = NbaInjuries::find([
            'conditions' => 'isShow = 2',
        ]);
        $this->view->injuries = $injuries;
        $this->view->invalid = $invalid;
    }

}

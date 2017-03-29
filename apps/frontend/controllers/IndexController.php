<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Models\NbaInjuries;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $injuries = NbaInjuries::find();
        $this->view->injuries = $injuries;
    }

}

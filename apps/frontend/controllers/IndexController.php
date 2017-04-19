<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Models\NbaInjuries;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $show_order = $this->request->get('show_order');
        $create_order = $this->request->get('create_order');
        $date_order = $this->request->get('date_order');

        $query = [
            'conditions' => 'isShow = 0 or isShow = 1',
        ];

        $show_order !== null && $order[] = 'isShow' . ($show_order == 1 ? ' DESC' : '');
        $order[] = 'createtime' . ($create_order == 1 ? ' ASC' : ' DESC');
        $date_order !== null && $order[] = 'date' . ($date_order == 1 ? ' DESC' : '');
        // 加入排序条件
        empty($order) || $query['order'] = implode(',', $order);

        $injuries = NbaInjuries::find($query); // 实时信息
        $invalid = NbaInjuries::find([         // 失效信息
            'conditions' => 'isShow = 2',
            'order' => 'createtime DESC',
        ]);
        $this->view->injuries = $injuries;
        $this->view->invalid = $invalid;
    }

}

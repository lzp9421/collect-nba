<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Models\NbaInjuries;
use Multiple\Frontend\Models\NbaPlayer;

class ApiController extends BaseController
{
    public function listAction()
    {

    }

    /**
     * 获取一条信息
     * @param int $id
     */
    public function showAction($id = 0)
    {
        if ($id) {
            $injuries = NbaInjuries::findFirst($id);
        } else {
            $injuries = NbaInjuries::findFirst('isShow = 1');
        }
        $this->response->setJsonContent($injuries->toArray([
            'id',
            'displayName',
            'displayNameEn',
            'status',
            'statusCn',
            'date',
            'dateCn',
            'comment',
            'commentCn',
        ]));
        $this->response->send();
    }

    /**
     * 保存修改
     * @param $id
     */
    public function updateAction($id)
    {

        $injuries = NbaInjuries::findFirst([
            "conditions" => "id = ?1",
            "bind"       => [
                1 => $id,
            ]
        ]);
        if ($injuries) {
            // 保存操作
            $errors = [];
            $fields = ['statusCn', 'commentCn'];
            foreach ($fields as $field) {
                $value = trim(htmlspecialchars($this->request->get($field)));
                empty($value) && $errors[] = "请填写${field}字段";
                $injuries->$field = $value;
            }
            if (empty($errors)) {
                $injuries->isShow = 0;
                $injuries->save();
                $this->response->setJsonContent(['status' => 'success', 'data' => '']);
            } else {
                $this->response->setJsonContent(['status' => 'error', 'data' => $errors]);
            }
        } else {
            $this->response->setJsonContent(['status' => 'error', 'data' => '不存在该条记录']);
        }
        $this->response->send();
    }

}

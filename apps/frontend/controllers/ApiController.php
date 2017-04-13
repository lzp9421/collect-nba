<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Models\NbaInjuries;
use Multiple\Frontend\Models\NbaPlayer;

class ApiController extends BaseController
{

    /**
     * 数据查询接口
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function queryAction()
    {
        $team_name = (array)$this->request->get('team_name');
        if (empty($team_name)) {
            $query = [
                'conditions' => 'isShow IN(0, 1)',
                'columns' => 'group_concat(id, \':\', dateCn) AS ids',
                'group' => 'teamName, displayNameEn, commentCn',
            ];
        } else {
            $bind = [];
            foreach ($team_name as $key => $name) {
                $bind[$key + 1] = $name;
            }
            $in = array_map(function ($value) {
                return '?' . $value;
            }, array_keys($bind));
            $query = [
                'conditions' => 'isShow IN(0, 1)  AND teamName IN (' . implode(', ', $in) . ')',
                'bind'       => $bind,
                'columns' => 'group_concat(id, \':\', dateCn) AS ids',
                'group' => 'teamName, displayNameEn, commentCn',
            ];
        }
        $in = array_map(function ($data) {
            return $this->getTimelyId($data['ids']);
        }, NbaInjuries::find($query)->toArray());
        $injuries = NbaInjuries::find([
            'conditions' => 'id IN (' . implode(', ', $in) . ')',
        ]);

        $this->response->setJsonContent($injuries);
        return $this->response;
    }

    // 获取最新的id
    private function getTimelyId($data)
    {
        // "1384:04月01日,1385:04月11日"
        $id = 0;
        $time = '';
        foreach (explode(',', $data) as $_data) {
            list($_id, $_time) = explode(':', $_data);
            if ($_time > $time) {
                $time = $_time;
                $id = $_id;
            }
        }
        return $id;
    }

    /**
     * 获取一条信息
     * @param int $id
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
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
        return $this->response;
    }

    /**
     * 保存修改
     * @param $id
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
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
                $injuries->isShow == '1' && $injuries->isShow = 0;
                $injuries->save();
                $this->response->setJsonContent(['status' => 'success', 'data' => '']);
            } else {
                $this->response->setJsonContent(['status' => 'error', 'data' => $errors]);
            }
        } else {
            $this->response->setJsonContent(['status' => 'error', 'data' => '不存在该条记录']);
        }
        return $this->response;
    }

}

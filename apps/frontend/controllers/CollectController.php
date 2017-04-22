<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Db\Adapter\Pdo\Mysql;
use PHPHtmlParser\Dom;
use Multiple\Frontend\Models\NbaInjuries;
use Multiple\Frontend\Models\NbaPlayer;

class CollectController extends BaseController
{
    private $datatime;
    private $new_data = 0;
    private $real_time_data = 0;
    private $start_time;
    private $bad = []; // 没有对应信息的球员

    public function initialize()
    {
        $this->start_time = microtime(true);
        parent::initialize();
    }

    public function indexAction()
    {
        $this->datatime = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $html = $this->getHtml('http://www.espn.com/nba/injuries');
        try {
            $list = $this->parseHtml($html);
            if (empty($list)) {
                throw new \Exception('采集失败');
            }
            foreach($list as $data) {
                if (!$this->saveInjuries($data)) {
                    throw new \Exception('保存失败');
                }
            }
        } catch (\Exception $e) {
            return $this->response->setJsonContent(['status' => 'error', 'data' => $e->getMessage()]);
        }

        // 标记失效等消息
        $injuries = NbaInjuries::find([
            "conditions" => "updatetime < ?1",
            "bind"       => [
                1 => $this->datatime,
            ]
        ]);
        $injuries->update(['isShow' => 2]);

        return $this->response->setJsonContent([
            'status' => 'success',
            'real_time_data' => $this->real_time_data,
            'new_data' => $this->new_data,
            'invalid_data' => count($injuries),
            'error' => $this->bad,
            'time' => sprintf('%.4f', round(microtime(true) - $this->start_time, 4)),
        ]);
    }

    /**
     * 保存一条信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    protected function saveInjuries($data)
    {

        $player_code = strtolower(str_replace(' ', '_', trim($data[1])));
        $display_name_en = $data[1];
        $status = $data[2];
        $date = $data[3];
        $comment = $data[4];

        $injuries = NbaInjuries::findFirst([
            "conditions" => "displayNameEn = ?1 and status = ?2 and date = ?3 and comment = ?4",
            "bind"       => [
                1 => $display_name_en,
                2 => $status,
                3 => $date,
                4 => $comment,
            ]
        ]);
        if (empty($injuries)) {
            $injuries = new NbaInjuries; // 新的数据
            // 获取球员信息
            $player = NbaPlayer::findFirst([
                "conditions" => "playerCode = ?1",
                "bind"       => [
                    1 => $player_code,
                ]
            ]);

            if (empty($player)) {
                $this->bad[] = $display_name_en;
                return -1;
                //throw new \Exception('找不到该球员等信息');
            }
            $injuries->displayNameEn = $display_name_en;
            $injuries->status = $status;
            $injuries->statusCn = !strcasecmp($status, 'out') ? '无法上场' : (!strcasecmp($status, 'day-to-day') ? '伤病进展' : '其他');
            $injuries->date = $date;
            $injuries->dateCn = (new \DateTime($data['3'], new \DateTimeZone('UTC')))->format('m月d日');
            $injuries->injury = $this->lastInjury($display_name_en);
            $injuries->comment = $comment;
            $injuries->updatetime = $injuries->createtime = $this->datatime;
            $injuries->isShow = 1;
            // 从球员信息中复制信息
            $fields = ['playerId', 'playerCode', 'displayName', 'teamId', 'teamCode', 'teamName'];
            foreach ($fields as $field) {
                $injuries->$field = $player->$field;
            }
            $this->new_data++;
        } else {
            $injuries->isShow == 2 && $injuries->isShow = 1; // 还原失效信息
            $injuries->updatetime = $this->datatime;
        }
        $this->real_time_data++;
        return $injuries->save();

    }

    /**
     * 最新伤停部位
     * @param $display_name_en
     * @return null|\Phalcon\Mvc\Model\Resultset
     */
    private function lastInjury($display_name_en)
    {
        $injuries = NbaInjuries::findFirst([
            'conditions' => 'displayNameEn = ?1 AND injury IS NOT NULL',
            'bind' => [
                1 => $display_name_en,
            ],
            'order' => 'dateCn DESC',
        ]);

        return $injuries ? $injuries->injury : null;
    }

    /**
     * 下载url
     * @param string $url
     * @return string
     */
    protected function getHtml($url)
    {

        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $html = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        return $html;
    }

    /**
     * 解析html，获取球员信息并返回数组
     * @param string $html
     * @return array
     * @throws \Exception
     */
    protected function parseHtml($html)
    {
        $data = [];
        $count = 0;
        $team = '';

        $dom = new Dom;
        $dom->load($html);

        foreach ($dom->find('#my-players-table table tr') as $key => $tr) {
            // 跳过表头等无关信息
            $attr = $tr->getAttribute('class');
            if (strstr($attr, 'stathead')) {
                // 获取球队
                $td = $tr->find('td');
                $team = strip_tags($td->innerHtml);
            } elseif (strstr($attr, 'oddrow') || strstr($attr, 'evenrow')) {
                $td = $tr->find('td');
                if (count($td) === 3) {
                    // 球员信息
                    $data[intval($count / 2)][] = $team;
                    foreach ($td as $value) {
                        $data[intval($count / 2)][] = strip_tags($value->innerHtml);
                    }
                } elseif (count($td) === 1) {
                    // 评论
                    $data[intval($count / 2)][] = strip_tags($td->text);
                    // 此处得到完整等信息，存入数据库

                } else {
                    // 格式发生变化导致数据获取异常
                    throw new \Exception('内容格式错误');
                }
                $count++;
            }
        }
        return $data;
    }
}

<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use PHPHtmlParser\Dom;
use Multiple\Frontend\Models\NbaInjuries;
use Multiple\Frontend\Models\NbaPlayer;

class IndexController extends Controller
{
    public function indexAction()
    {
        $html = $this->getHtml('http://www.espn.com/nba/injuries');
        array_walk($this->parseHtml($html), function ($data) {
            $this->saveInjuries($data);
        });
        return json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 保存一条信息
     * @param $data
     */
    protected function saveInjuries($data)
    {
        $player_code = strtolower(str_replace(' ', '_', trim($data[1])));

        $injuries = NbaInjuries::findFirst([
            "conditions" => "playerCode = ?1",
            "bind"       => [
                1 => $player_code,
            ]
        ]);

        empty($injuries) && $injuries = new NbaInjuries;

        $player = NbaPlayer::findFirst([
            "conditions" => "playerCode = ?1",
            "bind"       => [
                1 => strtolower(str_replace(' ', '_', trim($data[1]))),
            ]
        ]);

        //
        $injuries->displayNameEn = $data['1'];
        $injuries->status = $data['2'];
        $injuries->date = $data['3'];
        $injuries->comment = $data['4'];
        $fields = ['playerId', 'playerCode', 'teamId', 'teamCode', 'teamName', 'createtime', 'updatetime'];
        foreach ($fields as $field) {
            $injuries->$field = $player->$field;
        }
        $injuries->save();
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

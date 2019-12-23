<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use QL\QueryList;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use think\Db;

class collect11 extends Command
{
    private $default_end_time = 1558022400;
    private $data = [];
    private $url;
    private $url_type;
    private static $list_ql_obj;
    private static $info_ql_obj;
    private $webname;

    protected function configure()
    {
        // 指令配置
        $this->setName('collect11');
        // 设置参数

    }

    protected function execute(Input $input, Output $output)
    {
        $this->url_type = 11;
        $this->url = config('collect_url_type')[$this->url_type][2];
        $this->webname = config('collect_url_type')[$this->url_type][0];
        $this->default_end_time = DB::name("yosi_collect_data")->where("url_type", $this->url_type)->max("release_time") ?: $this->default_end_time;
        self::$list_ql_obj = QueryList::getInstance();
        self::$info_ql_obj = QueryList::range('.article');
        if ($this->getTreeData()) {
            $output->writeln('collect11 添加成功!');
        } else {
            $output->writeln('collect11 添加失败，或者数据没有更新');
        }
    }

    /**
     * 无限极循环获取数据
     */
    public function getTreeData($page = 1)
    {
        if ($page == 1) {
            $url = $this->url . 'game-newslist.html';
        } else {
            $url = $this->url . 'game-newslist.html?page=' . $page;
        }

        try {

            $self_data = self::$list_ql_obj->get($url)
                ->rules([
                    'title' => ['.gb-list1 .tit a', 'text'],
                    'source_url' => ['.gb-list1 .tit a', 'href'],
                    'release_time' => ['.gb-list1 .date', 'text'],
                ])
                ->queryData(function ($item) {
                    $item['url_type'] = $this->url_type;
                    $details = $this->getContenData($item['source_url']);
                    $item['cont'] = $details['content_html'];
                    $ql = self::$info_ql_obj->get($item['source_url']);
                    $item['description'] = $ql->find('#dd-cnt')->text();
                    $item['release_time'] = strtotime($ql->find('.article .info .col-01')->text());
                    $image = $ql->find('.p-image img')->attrs('src');
                    $attrImg = [];
                    foreach ($image as $key => $val) {
                        if ($val) {
                            if(strstr($val,"http://")){
                                $attrImg[] = substr($val, 7);
                            }else{
                            $attrImg[] = substr($val, 2);
                            }
                        }
                    }
                    $item['image_arr'] = json_encode($attrImg);
                    $item['type'] = 2;
                    $item['extra'] = json_encode([
                        'webname' => $this->webname,
                        "headimage" => '',
                        "username" => substr($ql->find('.col-03')->text(), 9),
                        'gamename' => '',
                        'seo' => '',
                        'gameicon' => '',
                        'gamedescription' => '',
                    ]);

//                    if ($item['release_time'] > $this->default_end_time && Db::name("yosi_collect_data")->insertGetId($item)) {
//                        dump("{$item["title"]} 添加成功");
//                    } else {
//                        dump("{$item["title"]} 添加失败或者不需要添加");
//                    }
                    $ql->destruct();
                    return $item;
                });
            dump($self_data);

            if (end($self_data)['release_time'] <= $this->default_end_time) {
                unset($self_data);
                return true;
            } else {
                $page++;
                return $this->getTreeData($page);
            }
        } catch (RequestException $e) {
            return false;
        }
    }


    public function getContenData($source_url)
    {
        $str = substr($source_url, -7, 1);
        if ($str == 1) {
            $str = substr($source_url, 0, strlen($source_url) - 7);
            $url = $str . 'all.shtml';
        } else {
            $url = $source_url;
        }
        $rules = [
            'content_html' => ['.article-con', 'html', '-p:last -iframe'],
        ];
        $sql = self::$info_ql_obj->get($url)->getHtml();
        $rt = QueryList::rules($rules)->html($sql)->query()->getData();
        return $rt->all()[0];
    }
}

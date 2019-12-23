<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class MediaImgtext extends Base
{
    protected $autoWriteTimestamp = true;
    protected $table = 'yosi_media_imgtext';


    /**
     * 获取中的一个数据
     * @param $condition
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getMediaImgtextInfo($condition)
    {
        if ($condition['uploadtype'] == 3) {
            return Db::name('yosi_collect_data')
                ->field([
                    'id',
                    'title',
                    'description',
                    'image_arr' => 'image_arr',
                    'images_key' => 'images_src',
                    'cont',
                    'source_url',
                    'extra',
                    'release_time' => 'create_time'
                ])
                ->json(['extra'])
                ->where([
                    ['id', '=', $condition['id']],
                    ['delete_time', '=', 0],
                    ['type','=' ,1]
                ])
                ->withAttr("images_src", function ($value, $data) {
                    return explode(",", $value);
                })
                ->find();
        }elseif ($condition['uploadtype'] == 4){
            return Db::name('yosi_collect_data')
                ->field([
                    'id',
                    'title',
                    'description',
                    'image_arr' => 'image_arr',
                    'images_key' => 'images_src',
                    'cont',
                    'source_url',
                    'extra',
                    'release_time' => 'create_time',
                    'type',
                ])
                ->json(['extra'])
                ->where([
                    ['id', '=', $condition['id']],
                    ['delete_time', '=', 0],
                    ['type','=' ,2]
                ])
                ->withAttr("images_src", function ($value, $data) {
                    return explode(",", $value);
                })
                ->find();

        } else {
            return Db::name('yosi_media_imgtext')
                ->alias("m_it")
                ->field([
                    'm.title',
                    'm.create_time',
                    'm.status',
                    'm.game_id',
                    'm.push',
//                    'mg.game_id',
                    'g.name' => 'gamename',
                    'm.user_id',
                    'u.username' => 'username',
                    'm_it.images',
                    'm_it.cont',
                ])
                ->leftJoin("yosi_media m", "m.id = m_it.media_id")
                ->leftJoin('yosi_user u', 'u.id = m.user_id')
//                ->leftJoin("media_game mg", "mg.media_id = m_it.media_id")
                ->leftJoin("yosi_game g", "g.id = m.game_id")
                ->where([
                    ["m_it.media_id", '=', $condition['id']],
                    ["m.delete_time", "=", 0],
                ])
                ->withAttr("images_src", function ($value, $data) {
                    $arr = [];
                    $data["images"] = explode(",", $data["images"]);
                    foreach ($data["images"] as $v) {
                        $arr[] = parent::getImgUrl($v, 2);
                    }
                    return $arr;
                })
                ->find();
        }
    }

    /**
     * 获取数据
     * @param $condition
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getMediaImgtextList($condition)
    {
        $where = [
            ["delete_time", "=", 0],
        ];
        if (!empty($condition['keywords'])) {
            $where[] = ["title|cont|extra", "like", "%{$condition['keywords']}%"];
        }
        if ($condition['uploadtype'] == 3) {
            return Db::name("yosi_collect_data")
                ->field([
                    'id',
                    'title',
                    'description',
                    'cont',
                    'extra',
                    'url_type',
                    'source_url' => 'url',
                    'release_time'
                ])
                ->json(["extra"])
                ->where($where)
                ->where(['type'=>1])
                ->order("release_time desc")
                ->paginate($condition['limit'], false, [
                    'page' => $condition['page'],
                ])
                ->each(function ($item, $k) {
                    $item['create_time'] = date("Y-m-d H:i:s", $item['release_time']);
                    $item['count'] = mb_strlen($item['cont']);
                    $item['platform'] = 0;
                    return $item;
                })
                ->toArray();
        }
        if($condition['uploadtype'] == 4){
            return Db::name("yosi_collect_data")
                ->field([
                    'id',
                    'title',
                    'description',
                    'cont',
                    'extra',
                    'url_type',
                    'source_url' => 'url',
                    'release_time',
                    'type'
                ])
                ->json(["extra"])
                ->where($where)
                ->where(['type'=>2])
                ->order("release_time desc")
                ->paginate($condition['limit'], false, [
                    'page' => $condition['page'],
                ])
                ->each(function ($item, $k) {
                    $item['create_time'] = date("Y-m-d H:i:s", $item['release_time']);
                    $item['count'] = mb_strlen($item['cont']);
                    $item['platform'] = 0;
                    return $item;
                })
                ->toArray();
        }
        $where = [
            ["m.delete_time", "=", 0],
            ["m.type", "=", $condition['type']],
//            ["m.platform", $condition["uploadtype"] == 2 ? "=" : "<>", 0],
        ];
        if (!empty($condition['keywords'])) {
            $where[] = ["m.number|m.title|u.username", "like", "%{$condition['keywords']}%"];
        }

        return Db::name("yosi_media_imgtext")
            ->alias("m_it")
            ->distinct('m.id')
            ->field([
                'm.id',
                'm.number',
                'm.title',
                'm.user_id',
                'm.create_time',
                'm.status',
                'm.platform',
                'm.is_recommend',
                'm.isbest',
                'm.fire',
                'm.istop',
                'm.game_id',
                'm.view_count',
                'm.condition_status',
                'g.name' => 'gamename',
                'u.username' => 'uname',
//                'm_it.images',
//                'mp.count' => 'playcount'
            ])
            ->leftJoin('yosi_media m', 'm.id = m_it.media_id')
            ->leftJoin('yosi_user u', 'u.id = m.user_id')
//            ->leftJoin("media_game mg", "mg.media_id = m_it.media_id")
//            ->leftJoin("media_play mp", "mp.media_id = m_it.media_id")
            ->leftJoin("yosi_game g", "g.id = m.game_id")
            ->where($where)
            ->order("m.update_time desc")
            ->paginate($condition['limit'], false, [
                'page' => $condition['page'],
            ])
            ->each(function ($item, $k) {
                if(isset($item['images'])){
                    $item['images'] = explode(',', $item['images']);
                    $item['images'] = parent::getImgUrl(empty($item['images']) ? "" : $item['images'][0], 2);
                }

                $item['create_time'] = date("Y-m-d H:i:s", $item['create_time']);
                return $item;
            })
            ->toArray();
    }
}

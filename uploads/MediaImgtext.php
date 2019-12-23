<?php

namespace app\admin\controller;

use app\admin\model\CollectData;
use think\Controller;
use think\Request;
use think\Validate;
use think\Db;
use app\admin\model\MediaImgtext as MediaImgtextModel;
use app\admin\model\Media as MediaModel;
use app\admin\model\Game as GameModel;
use think\Queue;
use app\admin\model\Push as PushModel;
use Qiniu\Auth;


class MediaImgtext extends Controller
{
    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 添加
     * @return mixed
     */
    public function addWord()
    {
        return $this->fetch();
    }

    /**
     * 编辑页面
     * @return mixed
     */
    public function edit()
    {
        return $this->fetch();
    }

    /**
     * web编辑页面
     * @return mixed
     */
    public function editorEdit()
    {
        return $this->fetch();
    }

    /**
     * 审核图文的内容
     * 0禁止 | 1 正常 | 2 待审核
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function audit(Request $request)
    {
        if ($request->isGet()) {
            return $this->fetch();
        }
        $data = [
            'media_id' => $request->param('data.vid', 0),
            'manager_id' => $request->manager_id,
            'cont' => $request->param('data.cont'),
            'status' => $request->param('data.status/d'),
            'create_time' => time(),
            'update_time' => time(),
        ];
        $MediaModel = MediaModel::field('status,type')->where([['id', '=', $data['media_id']]])->find();
        if (!$MediaModel) {
            return lang_rtn_json("mediaimgtext not exist or notallow");
        }
        if ($MediaModel['type'] == 2) {
            if ($MediaModel['status'] == 1 || $MediaModel['status'] == $data['status']) {
                return lang_rtn_json("mediaimgtext status is same");
            }
        }
        Db::startTrans();
        try {
            MediaModel::where([['id', '=', $data['media_id']]])->setField('status', $data['status']);
            if ($data['status'] == 1) {
                Queue::push('app\api\job\UserGame', [
                    'game_id' => Db::name('media_game')->where('media_id', $data['media_id'])->value('game_id')
                ], 'thinkqueue');
            }
            if (!Db::name("mediaimg_audit_log")->strict(false)->insert($data)) {
                throw new \Exception("数据添加失败");
            }
            Db::commit();
            return lang_rtn_json("success");
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return rtn_json(config("adminapi_invalid_code"), $e->getMessage());
        }
    }

    /**
     * 生成随机的编码-且唯一
     * @return string
     */
    private function getNumberRand()
    {
        $getTwoBitRand = getTwoBitRand(); //获取上传的视频三位数
        //验证编码是否重复，如果重复重新生成
        $number = config("media_prefix") . date("ymd", time()) . getmicrotime() . $getTwoBitRand;
        return $number;
    }

    /**
     * 添加和修改数据
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request)
    {

        $validate = new \app\admin\validate\MediaImgtext();

        if (!$validate->check($request->param('data'))) {
            return rtn_json(config("adminapi_invalid_code"), $validate->getError());
        }

        Db::startTrans();
        try {
            $data = [
                'number' => $this->getNumberRand(),
                'title' => $request->param('data.title'),
                'user_id' => $request->param('data.uid', 0),
                'only_me' => 0,
                'status' => 1,
                'type' => 2,
                'platform' => 0,
                'game_id' => $request->param('data.gameid'),
                'push' => $request->param('data.push'),
                'uploadtype' => $request->param('data.uploadtype/d'),
                'condition_status' => $request->param('data.condition_status/d'),
            ];

            $id = $request->param('data.vid/d');

            if ($data['uploadtype'] != 3 && $data['uploadtype'] != 4 && $id > 0) {
                //1、修改图文
                $MediaModel = MediaModel::where([['id', '=', $id], ['type', '=', '2']])->find();
                unset($data["status"]);
                unset($data["platform"]);
                if (!$MediaModel) {
                    return lang_rtn_json("mediaimgtext not exist or notallow");
                }
                if (!($MediaModel->allowField(true)->save($data))) {
                    throw new \Exception("图文数据插入失败");
                }
//                //2、修改图文的游戏
//                if (!empty($data['game_id'])) {
//                    Db::name('media_game')->where(['media_id' => $MediaModel['id']])->delete();
//                    $res = Db::name('media_game')->insert(['game_id' => $data['game_id'], 'media_id' => $MediaModel['id']]);
//                    if (!$res) throw new \Exception("游戏分类数据插入失败");
//                }
                //3、修改图文详情
                if ($data['condition_status'] == 4) {
                    $save = [
                        'images' => $request->param('data.imgs', ''),
                        'cont' => htmlspecialchars_decode(str_replace('17173', '', $request->param('data.cont')))
                    ];

                } elseif ($data['condition_status'] == 3) {
                    foreach ($request->param('data.cont') as $k => $v) {
                        $imgs[] = $v['img'];
                    }
                    $images = implode(",", $imgs);

                    $save = [
                        'images' => $images,
                        'cont' => json_encode($request->param('data.cont'))
                    ];
                } else {
                    $save = [
                        'images' => $request->param('data.images'),
                        'cont' => $request->param('data.cont'),
                    ];
                }

                $MediaImgtextModel = MediaImgtextModel::where('media_id', $id)->find();
                if (!($MediaImgtextModel->allowField(true)->force()->save($save))) {
                    throw new \Exception("图文数据插入失败");
                }
            } else {

                //1、添加视频
                $MediaModel = new MediaModel();
                if (!($MediaModel->allowField(true)->save($data))) {
                    throw new \Exception("图文数据插入失败");
                }

                //2、增加游戏视频中间表
//                $res = Db::name('media_game')->insert(['game_id' => $data['game_id'], 'media_id' => $MediaModel['id'],]);
//                if (!$res) throw new \Exception("游戏分类数据插入失败");
                //3、添加其他数据到mediaimagetext
                if ($data['condition_status'] == 3) {

                    foreach ($request->param('data.cont') as $k => $v) {
                        $imgs[] = $v['img'];
                    }
                    $images = implode(",", $imgs);
                    $save = [
                        'media_id' => $MediaModel['id'],
                        'images' => $images,
                        'cont' => json_encode($request->param('data.cont'))
                    ];
                } elseif ($data['condition_status'] == 4) {
                    $save = [
                        'media_id' => $MediaModel['id'],
                        'images' => $request->param('data.imgs', ''),
                        'cont' => htmlspecialchars_decode(str_replace('17173', '', $request->param('data.cont')))
                    ];
                } else {
                    $request->param('data', '');
                    $save = [
                        'media_id' => $MediaModel['id'],
                        'images' => $request->param('data.images', ''),
                        'cont' => $request->param('data.cont')
                    ];
                }
                $MediaImgtextModel = new MediaImgtextModel();
                if (!($MediaImgtextModel->allowField(true)->save($save))) {
                    throw new \Exception("图文数据插入失败");
                }

                Queue::push('app\api\job\UserGame', [
                    'game_id' => $data['game_id']
                ], 'thinkqueue');
                //$data['uploadtype'] == 3 && DB::name('yosi_collect_data')->where('id', $id)->delete();
                if ($data['uploadtype'] == 3 || $data['uploadtype'] == 4) {
                    $CollectDataModel = CollectData::where('id', $id)->find();
                    $CollectDataModel->allowField(true)->force()->save(['delete_time' => time()]);
                }

            }


            if ($MediaModel['push']) {
                $PushModel = new PushModel();
                $PushModel->type = 2;
                $arr = DB::name('yosi_media')->where('id', $MediaModel['id'])->find();
                $arr['images'] = config('video_play_url') . $arr['v_img'];
                $PushModel->title = $arr['title'];
                $PushModel->push_data = [
                    'dtype' => 2,
                    'title' => mb_substr($arr['title'], 40) . '...',
                    'pid' => 13100,
                    'url' => '',
                    'isshare' => 1, //临时写死
                    'img' => $arr['images'],
                    'des' => '',
                    'did' => $MediaModel['id'],
                    'epid' => '',
                ];
                $PushModel->allowField(true)->save();

                \Jpush::send($PushModel['title'], [
                    'type' => $PushModel->type,
                    'id' => $PushModel->id,
                    'data' => $PushModel->push_data
                    ,]);
            }

            Db::commit();
            return lang_rtn_json("success");
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return rtn_json(config("adminapi_invalid_code"), $e->getMessage());
        }
    }

    /**
     * 获取编辑的信息
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function getEditInfo(Request $request)
    {
        /*uploadtype
         * 运营数据  2
         * 上图下文第三方 3
         * 富文本第三方 4
         * type 图文混排
         */
        $data = $this->getAddInfo_cont($request->manager_id);

        $data['data'] = MediaImgtextModel::getMediaImgtextInfo([
            'id' => $request->param('data.vid/d'),
            'uploadtype' => $request->param('data.uploadtype/d'),
        ]);

        if ($request->param('data.type/d') == 3) {

            $data['data']['cont'] = json_decode($data['data']['cont'], true);
            foreach ($data['data']['cont'] as $k => $v) {
                if ($v['img']) {
                    $data['data']['cont'][$k]['img'] = 'http://qn.yocotv.com/' . $v["img"];
                }
            }
        }
        if ($request->param('data.uploadtype/d') == 3) {

            $data['data']['image_arr'] = json_decode($data['data']['image_arr'], true);
            foreach ($data['data']['image_arr'] as $k => $v) {
                if (strpos($v, "https://") === false && strpos($v, "http://") === false) {
                    $data['data']['image_arr'][$k] = "http://" . $v;
                }
            }


            foreach ($data['data']['image_arr'] as $key => $val) {
                $file = $this->curlGet($val);
                $img_src[] = $this->imgRoute($file);

            }
            $data['data']['image_arr'] = $img_src;

        }
        if ($request->param('data.uploadtype/d') == 4) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/", $data['data']['cont'], $matches);

            if ($matches[1]) {
                foreach ($matches[1] as $k => $v) {
                    if ($v) {
                        if (strpos($v, "https://") === false && strpos($v, "http://") === false) {
                            $imag = substr($v, 2);
                            $src_array[] = "http://" . $imag;
                        } else {
                            $src_array[] = $v;
                        }
                    }
                };


                foreach ($src_array as $key => $val) {
                    $file = $this->curlGet($val);
                    $img_src[] = $this->imgRoute($file);
                }
                $data['data']['cont'] = str_replace($matches[1], $img_src, $data['data']['cont']);

            }


        }

       
        return lang_rtn_json("success", $data);
    }

    public function imgRoute($file)
    {
        $path = '../public/uploads/collectdata/' . date('Ym');
        !file_exists($path) && mkdir($path, 0777, true);
        $file_path = '/' . md5($file) . '.jpg';
        $localPath = $path . $file_path;
        file_put_contents($localPath, $file);
        return '/uploads/collectdata/' . date('Ym') . $file_path;

    }

    /**
     * 获取图片字符串
     */
    public function curlGet($url)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; c8650 Build/GWK74) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1s');   //模拟浏览器访问
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        $values = curl_exec($curl);
        curl_close($curl);
        return ($values);


    }


    /**
     * 获取添加时所需要的信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getAddInfo(Request $request)
    {
        return lang_rtn_json("success", $this->getAddInfo_cont($request->manager_id));
    }

    public function getAddInfo_cont($mid)
    {
        return [
            'users' => Db::name("yosi_manager_user")->field("u.id,u.username")->alias("mu")->leftJoin("yosi_user u", "u.id = mu.user_id")->where("mu.delete_time = 0 and u.status = 1")->where("mu.manager_id", $mid)->all(),
            'games' => GameModel::field("id,name as gamename")->all(),
        ];
    }

    /**
     * 展示图文列表的数据
     * @param Request $request
     * @return array|mixed|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        if ($request->isPost()) {
            $condition = [
                'page' => $request->param("page"),
                'limit' => $request->param("limit"),
                'keywords' => trim($request->param("keywords")),
                'mediatype' => trim($request->param("mediatype")),
                'uploadtype' => $request->param("uploadtype/d"),
                'type' => 2,
            ];
            if (!is_positive_int($condition['page'])) {
                return lang_rtn_json("invalid parameter");
            }
            if (!is_positive_int($condition['limit'])) {
                $condition['limit'] = config("app.admin_info.admin_page_number");
            }
            $info = MediaImgtextModel::getMediaImgtextList($condition);
            return layui_rtn($info['data'], $info['total']);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 展示图文列表的数据
     * @param Request $request
     * @return array|mixed|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function postIndex(Request $request)
    {
        if ($request->isPost()) {
            $condition = [
                'page' => $request->param("page"),
                'limit' => $request->param("limit"),
                'keywords' => trim($request->param("keywords")),
                'mediatype' => trim($request->param("mediatype")),
                'uploadtype' => 0,
                'type' => 3,
            ];
            if (!is_positive_int($condition['page'])) {
                return lang_rtn_json("invalid parameter");
            }
            if (!is_positive_int($condition['limit'])) {
                $condition['limit'] = config("app.admin_info.admin_page_number");
            }
            $info = MediaImgtextModel::getMediaImgtextList($condition);
            return layui_rtn($info['data'], $info['total']);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 获取图文混排的列表
     * @param Request $request
     * @return array|mixed|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function mixindex(Request $request)
    {
        if ($request->isPost()) {
            $condition = [
                'page' => $request->param("page"),
                'limit' => $request->param("limit"),
                'keywords' => trim($request->param("keywords")),
                'mediatype' => trim($request->param("mediatype")),
                'uploadtype' => 0,
                'type' => 4,
            ];
            if (!is_positive_int($condition['page'])) {
                return lang_rtn_json("invalid parameter");
            }
            if (!is_positive_int($condition['limit'])) {
                $condition['limit'] = config("app.admin_info.admin_page_number");
            }
            $info = MediaImgtextModel::getMediaImgtextList($condition);
            return layui_rtn($info['data'], $info['total']);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 删除收集的数据
     * @param $id
     * @return bool|\think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete($id)
    {
        if (Db::name('yosi_collect_data')
            ->where('id', $id)
            ->useSoftDelete('delete_time', time())
            ->delete()) {
            return json_msg(200, '删除成功');
        } else {
            return json_msg(500, '删除失败');
        }
    }

}

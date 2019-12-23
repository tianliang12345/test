<?php

namespace app\command;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\facade\Log;

class downloadimg extends Command
{

    protected function configure()
    {
        // 指令配置
        $this->setName('downloadimg');
        // 设置参数

    }

    protected function execute(Input $input, Output $output)
    {
        $path = str_replace("/application/command", "", __DIR__) . '/uploads/collectdata/' . date('Ym');
        !file_exists($path) && mkdir($path, 0777, true);
        $data = Db::name('yosi_collect_data')
            ->field([
                'id',
                'image_arr'
            ])
            ->where([
                ['delete_time', '=', 0],
                ['is_dl', '=', 0],
            ])
            ->cursor();
        foreach ($data as $v) {
            $v['image_arr'] = json_decode($v['image_arr'], true);
            Db::startTrans();
            try {
                $img = [];
                foreach ($v['image_arr'] as $src) {
                    $file = $this->curlGet($src);
                    $file_path = '/' . md5($src) . '.jpg';
                    $localPath = $path . $file_path;
                    file_put_contents($localPath, $file);
                    $img[] = '/uploads/collectdata/' . date('Ym') . $file_path;
//                    $file = file_get_contents($src);
//                    $file_path = '/' . md5($src) . '.jpg';
//                    $localPath = $path . $file_path;
//                    file_put_contents($localPath, $file);
//                    $img[] = '/uploads/collectdata/' . date('Ym') . $file_path;
                }

                if ($this->changeStatu($v['id'], implode(',', $img))) {
                    // 提交事务
                    Db::commit();
                } else {
                    throw new \Exception("{$v['id']} 更新失败");
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        }
        // 指令输出
        $output->writeln('downloadimg');
    }

    /**
     * 修改视频修改过程中的状态
     */
    protected function changeStatu($id, $values)
    {
        return Db::name("yosi_collect_data")->where("id", $id)->update([
            'is_dl' => 1,
            'images_key' => $values
        ]);
    }
    /**
     * 获取图片字符串
     */
    protected  function curlGet($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");  //模拟浏览器访问
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        $values = curl_exec($curl);
        curl_close($curl);
        return($values);
    }
}

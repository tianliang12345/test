<!---->
<!--return [-->
<!--    // 文件上传默认驱动-->
<!--'UPLOAD_DRIVER' => 'Qiniu', //设置七牛上传驱动-->
<!--    //'UPLOAD_DRIVER' => 'Local',-->
<!--    // 七牛上传驱动配置说明-->
<!--    'UPLOAD_Qiniu_CONFIG' => array(-->
<!--    'secretKey' => 'WFqGBhS21Mi1qbyIwPPmsPndnpFUCuEDdPF7mDqL1', //七牛服务器-->
<!--    'accessKey' => 'qR3UXu03aZqHYD3V2WKhlNCiCBEUhFfwEcrDlkQV1', //七牛用户-->
<!--    'domain'    => 'http://img.zengzone.com/', //七牛域名-->
<!--    'bucket'    => 'hiki19871', //空间名称-->
<!--    'timeout'   => 300, //超时时间-->
<!--),-->
<!--];-->

<?php

?>
    public function qiniu_upload(){
    require_once EXTEND_PATH.'Qiniu/autoload.php';
    $config = Config::get('UPLOAD_Qiniu_CONFIG');
    $accessKey = $config['accessKey'];
    $secretKey = $config['secretKey'];
    $auth = new Auth($accessKey, $secretKey);

    $bucket = $config['bucket'];// 要上传的空间
    $token = $auth->uploadToken($bucket);// 生成上传 Token

    // 要上传文件的本地路径
    if(isset($_FILES['upfile'])){
        $filePath = $_FILES['upfile']['tmp_name'];//ueditor上传图片
    }
    if(isset($_FILES['file'])){
        $filePath = $_FILES['file']['tmp_name'];//ueditor上传图片
    }
    $file = new File($filePath);
    $info = $file->webuploader_move(ROOT_PATH . 'public' . DS . 'uploads');//本地上传

    // 上传到七牛后保存的文件名
    if($info){
        $key = $info->getFilename();
    }else{
        $key = md5(time()).'.png';
    }
    // 初始化 UploadManager 对象并进行文件的上传
    $uploadMgr = new UploadManager();

    // 调用 UploadManager 的 putFile 方法进行文件的上传
    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
    if ($err === null) {
        $data['url'] = $config['domain'].$ret['key'];
    }

    if($info){
        $data['name'] = $info->getFilename();
        $data['md5'] = $info->hash('sha1');
        $data['sha1'] = $info->hash('md5');
        $data['ext'] = 'jpg';
        $data['path'] = '/uploads/' . $info->getSaveName() . 'jpg';
        $data['location'] = 'Qiniu';
        $data['create_time'] = time();
        $data['status'] = 1;
        $id = Db::name("admin_upload")->insertGetId($data);//插入图片数据
        if($id > 0){
            $return['path'] = $data['path'];
            $return['name'] = $data['name'];
            $return['id'] = $id;
            $return['state'] = 'SUCCESS';
            $return['url'] = $data['url'];
        }else{
            $return['error']   = 1;
            $return['success'] = 0;
            $return['status']  = 0;
            $return['message'] = '上传出错'.$file->getError();
        }
    }else{
        // 上传失败获取错误信息
        $return['error']   = 1;
        $return['success'] = 0;
        $return['status']  = 0;
        $return['message'] = '上传出错'.$file->getError();
    }
    exit(json_encode($return));
}
    //删除图片
    public function removefile(){
    $cover = input("request.cover",0);
    Db::name('admin_upload')->where("id={$cover}")->delete();
    return ['status' => 1, 'info' => '删除成功'];
}
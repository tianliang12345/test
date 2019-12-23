<?php
////echo intval('5fdf23');
////$cmd = 'ffmpeg -i 1.mp4 -y -f image2 -t 0.001 -s 352x240 12.jpg';
////$cmdTo = exec($cmd, $out);
////var_dump($out);
////var_dump($cmdTo);
////echo "Starting ffmpeg...\n\n";
////echo exec("/usr/local/bin/ffmpeg -i 1.mp4 -y -f image2 -t 0.001 -s 352x240 12.jpg");
////echo "Done.\n";export PATH="/usr/local/bin:$PATH"
//$img_info = getimagesize('APP.png');
//var_dump($img_info);
//
////案例二：将活动背景图片设置透明，然后和动态二维码图片合成一张图片
//// 图片一
//$path_1 = 'APP.png';
//// 图片二
//$path_2 = 'scan.png';
////创建图片对象
//$image_1 = imagecreatefrompng($path_1);
//$image_2 = imagecreatefrompng($path_2);
////创建真彩画布
////imagecreatetruecolor(int $width, int $height)--新建一个真彩色图像
//$image_3 = imageCreatetruecolor(imagesx($image_1), imagesy($image_1));
////为真彩画布创建白色背景
////imagecolorallocate(resource $image, int $red, int $green, int $blue)
//$color = imagecolorallocate($image_3, 255, 255, 255);
////imagefill(resource $image ,int $x ,int $y ,int $color)
////在 image 图像的坐标 x，y（图像左上角为 0, 0）处用 color 颜色执行区域填充（即与 x, y 点颜色相同且相邻的点都会被填充）
//imagefill($image_3, 0, 0, $color);
////设置透明
////imagecolortransparent(resource $image [,int $color])
////将image图像中的透明色设定为 color
////imageColorTransparent($image_3, $color);
////复制图片一到真彩画布中（重新取样-获取透明图片）
////imagecopyresampled(resource $dst_image ,resource $src_image ,int $dst_x ,int $dst_y ,int $src_x , int $src_y ,int $dst_w ,int $dst_h ,int $src_w ,int $src_h)
//// dst_image:目标图象连接资源
//// src_image:源图象连接资源
//// dst_x:目标 X 坐标点
//// dst_y:目标 Y 坐标点
//// src_x:源的 X 坐标点
//// src_y:源的 Y 坐标点
//// dst_w:目标宽度
//// dst_h:目标高度
//// src_w:源图象的宽度
//// src_h:源图象的高度
//imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
////与图片二合成
////imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )---拷贝并合并图像的一部分
//// //将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
//imagecopymerge($image_3, $image_2, 402, 1350, 0, 0, imagesx($image_2), imagesy($image_2), 100);
//// 输出合成图片
//var_dump(imagepng($image_3, './merge2.png'));
//
//$a = '{"addressBookContent":"[{"cid":"1","email":"fghggg@163.com","name":"嘻嘻嘻","phoneNum":"2222,12365 4,65866669855,6885566666,8858 8888,558 896 6699,55665877555,2569897455665899,187 9999 9999,158 9999 9999,"}, {"cid":"2","email":"","name":"乐克乐克金坷垃乐克乐克了啦","phoneNum":"158 6666 6666,"}]","addressBookCount":2,"appContent":"[{"appName":"屏幕录制","packageName": "com.miui.screenrecorder"},{"appName":"StickyNavigationBar","packageName": "com.bruce.stickynavigationbar"},{"appName":"影梭","packageName":"com.github.shadowsocks"}, {"appName":"CBRatingBar","packageName":"com.codebear.demo"},{"appName":"TestSharedElements", "packageName":"spikeking.github.com.testsharedelements"},{"appName":"ScreenCaptureDemo", "packageName":"com.example.screencapturedemo"},{"appName":"OurPlay","packageName": "com.excean.gspace"},{"appName":"小米直播助手","packageName":"com.mi.liveassistant"},{"appName": "SwipeMenuSample","packageName":"com.tubb.smrv.demo"},{"appName":"蓝灯","packageName": "org.getlantern.lantern"},{"appName":"MD5签名生成器","packageName":"com.sina.weibo.sdk.gensign"}, {"appName":"EasyBehavior","packageName":"me.stefan.easybehavior"},{"appName":"华为应用市场", "packageName":"com.huawei.appmarket"},{"appName":"PushDemo","packageName": "com.push.demo"},{"appName":"即刻","packageName":"com.ruguoapp.jike"},{"appName":"牛播放器", "packageName":"com.qiniu.droid.niuplayer"},{"appName":"StatusBarUtil","packageName": "com.jaeger.statusbardemo"},{"appName":"Microsoft Excel","packageName":"com.microsoft.office.excel"}, {"appName":"百度输入法小米版","packageName":"com.baidu.input_mi"},{"appName":"用户手册", "packageName":"com.miui.userguide"},{"appName":"Google Play 商店","packageName": "com.android.vending"},{"appName":"微信","packageName":"com.tencent.mm"},{"appName":"小爱语音 引擎","packageName":"com.xiaomi.mibrain.speech"},{"appName":"应用宝","packageName": "com.tencent.android.qqdownloader"},{"appName":"二杠","packageName":"com.appcpi.yoco"}, {"appName":"小米换机","packageName":"com.miui.huanji"},{"appName":"健康","packageName": "com.mi.health"},{"appName":"XhsEmoticonsKeyboard","packageName":"com.xhsemoticonskeyboard"}, {"appName":"小米文档查看器(WPS定制)","packageName":"cn.wps.moffice_eng.xiaomi.lite"},{"appName": "BottomDialog","packageName":"me.shaohui.bottomdialog"},{"appName":"小米画报","packageName": "com.mfashiongallery.emag"},{"appName":"test","packageName":"com.test"},{"appName": "MyTestProject","packageName":"example.com.mytestproject"},{"appName":"PhotoBrowse", "packageName":"com.sleepwind"},{"appName":"Lame","packageName":"com.clam314.lame"}, {"appName":"Vysor","packageName":"com.koushikdutta.vysor"},{"appName":"全球上⺴", "packageName":"com.miui.virtualsim"},{"appName":"指南针","packageName":"com.miui.compass"}, {"appName":"小米云盘","packageName":"com.android.midrive"},{"appName":"AdViewDemoAS", "packageName":"com.xmapp.app.fushibao"},{"appName":"MIUI论坛","packageName": "com.miui.miuibbs"},{"appName":"一键投影","packageName":"com.depthlink.airlink"},{"appName":"万能遥 控","packageName":"com.duokan.phone.remotecontroller"},{"appName":"离线鉴⻩","packageName": "com.example.open_nsfw_android"},{"appName":"PagerPlayer","packageName":"com.reikyz.pagerplayer"}, {"appName":"QQ浏览器","packageName":"com.tencent.mtt"},{"appName":"EaseUISimpleDemo", "packageName":"com.hyphenate.easeuisimpledemo"},{"appName":"GenSignature","packageName": "com.tencent.mm.openapi"},{"appName":"文件下载引擎","packageName": "com.liulishuo.filedownloader.demo"},{"appName":"App","packageName":"com.ximsfei.skindemo"}, {"appName":"ImagePicker","packageName":"com.lzy.imagepickerdemo"},{"appName":"Microsoft Word", "packageName":"com.microsoft.office.word"},{"appName":"Microsoft PowerPoint","packageName": "com.microsoft.office.powerpoint"},{"appName":"今日头条极速版","packageName": "com.ss.android.article.lite"},{"appName":"垃圾分类专家","packageName":"com.letui.garbage"}, {"appName":"JZVideoDemo","packageName":"com.zzh12138.jzvideodemo"},{"appName":"皮皮虾", "packageName":"com.sup.android.superb"},{"appName":"小米搜狐视频播放器插件","packageName": "com.sohu.sohuvideo.miplayer"},{"appName":"CameraView Demo","packageName": "com.google.android.cameraview.demo"},{"appName":"UC浏览器","packageName":"com.UCMobile"}, {"appName":"RecyclerView_Gallery","packageName":"com.suming.recyclerview_gallery"},{"appName":"智 能识物","packageName":"com.xiaomi.lens"},{"appName":"小米卡包","packageName":"com.xiaomi.pass"}, {"appName":"小米商城","packageName":"com.xiaomi.shop"},{"appName":"爱奇艺播放器", "packageName":"com.qiyi.video.sdkplayer"},{"appName":"抖音短视频","packageName": "com.ss.android.ugc.aweme"},{"appName":"Luban","packageName":"top.zibin.luban.example"}, {"appName":"Google Play 服务","packageName":"com.google.android.gms"},{"appName":"Google 服务框 架","packageName":"com.google.android.gsf"},{"appName":"MyKeep","packageName": "com.zhoujian.mykeep"},{"appName":"DK播放器","packageName":"com.dueeeke.dkplayer"},{"appName": "Google合作伙伴设置","packageName":"com.google.android.partnersetup"},{"appName":"QQ", "packageName":"com.tencent.mobileqq"},{"appName":"便签","packageName":"com.miui.notes"}, {"appName":"OpenPagerAdapter","packageName":"com.homg.openpageradapter"},{"appName": "CollapsibleTextView","packageName":"com.timqi.collapsibletextview.example"},{"appName":"Tiki", "packageName":"com.buddy.tiki"},{"appName":"小米快传","packageName":"com.xiaomi.midrop"}, {"appName":"FlycoTabLayout","packageName":"com.flyco.tablayoutsamples"},{"appName":"Facebook", "packageName":"com.facebook.katana"},{"appName":"com.skin.night","packageName": "com.skin.night"},{"appName":"JSCKit","packageName":"jsc.exam.jsckit"},{"appName":"Google帐号管理程 序","packageName":"com.google.android.gsf.login"},{"appName":"快视频","packageName": "com.xiaomi.apps.videodaily"},{"appName":"StatusUtil","packageName":"crossoverone.statusutil"}, {"appName":"计算器","packageName":"com.miui.calculator"},{"appName":"软件商店","packageName": "com.oppo.market"},{"appName":"游戏中心","packageName":"com.xiaomi.gamecenter"},{"appName": "一点资讯","packageName":"com.yidian.xiaomi"},{"appName":"GSYVideoPlayer","packageName": "com.example.gsyvideoplayer"},{"appName":"CameraApplication","packageName": "com.zxing.cameraapplication"},{"appName":"垃圾清理","packageName":"com.miui.cleanmaster"}, {"appName":"NVP","packageName":"com.xiao.nicevieoplayer"},{"appName":"MyTestApplication", "packageName":"example.com.mytestapplication"},{"appName":"领英","packageName": "com.linkedin.android"},{"appName":"天气","packageName":"com.miui.weather2"},{"appName": "StickyNavLayout","packageName":"com.gxz.stickynavlayout"},{"appName":"ImagePickerDemo", "packageName":"com.lwkandroid.imagepicker"},{"appName":"高德地图","packageName": "com.autonavi.minimap"},{"appName":"扫一扫","packageName":"com.xiaomi.scanner"},{"appName": "ycshareelement","packageName":"us.pinguo.shareelementdemo"},{"appName":"阅读","packageName": "com.duokan.reader"},{"appName":"test","packageName":"example.com.test"},{"appName":"audio demo","packageName":"com.renhui.audiodemo"},{"appName":"Banner Example","packageName": "com.test.banner"},{"appName":"PickerViewDemo","packageName":"com.bigkoo.pickerviewdemo"}, {"appName":"电子邮件","packageName":"com.android.email"},{"appName":"微博","packageName": "com.sina.weibo"},{"appName":"FlowLayout","packageName":"com.zhy.flowlayout"},{"appName":"My Application","packageName":"com.appcpi.garbage"},{"appName":"七牛短视频","packageName":
//"com.qiniu.pili.droid.shortvideo.demo"},{"appName":"和平精英","packageName": "com.tencent.tmgp.pubgmhd"},{"appName":"智能出行","packageName":"com.miui.smarttravel"}, {"appName":"MySkinTest","packageName":"com.myskintest"},{"appName":"小米金服安全组件", "packageName":"com.xiaomi.jr.security"},{"appName":"相册冲印组件","packageName": "com.mimoprint.xiaomi"},{"appName":"wuwei-Beta","packageName":"com.apackwolves.wuwei.test"}, {"appName":"com.umeng.soexample.App","packageName":"com.umeng.soexample"},{"appName": "PictureSelector","packageName":"com.luck.pictureselector"},{"appName":"全球上⺴工具插件", "packageName":"com.xiaomi.mimobile.noti"},{"appName":"米家","packageName": "com.xiaomi.smarthome"},{"appName":"二杠-Beta","packageName":"com.appcpi.yoco.test"}, {"appName":"LineChart","packageName":"com.linechart"},{"appName":"YouTubeDemo", "packageName":"com.zhixin.raulx.youtubedemo"},{"appName":"驾⻋模式","packageName": "com.xiaomi.drivemode"},{"appName":"CatchU!","packageName":"com.gnetop.catchu"},{"appName": "WPS Office","packageName":"cn.wps.moffice_eng"},{"appName":"SwitchViewDemo","packageName": "com.yl.switchview"},{"appName":"JChat","packageName":"io.jchat.android"}]","appCount": 129,"callRecords":"[{"date":1537548931146,"duration":0,"number":"18192275586","type":2},{"date": 1533633045675,"duration":8,"number":"18792551640","type":1}]","deviceLocation":"","deviceTypeNumber":"Redmi 6 Pro","imeiNumber":"868139032579358","loanCount": 0,"packageName":"com.apackwolves.wuwei.test","smsContent":"[]","smsCount":0,"socialCount": 0,"systemVersion":"9","userId":0}';
//
//$b = json_decode($a);
//var_dump($b);


$redis = new redis();
$redis->connect('127.0.0.1', 6379);
$redis->set('test', "hello worl2d");
$result = $redis->get('test');
var_dump($result);

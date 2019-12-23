<?php
//$version1="v1.0.5";
//
//$version2="v1.0.4";
//
//if( version_compare($version1,$version2) >= 0){
//    var_dump(123);
//}

//public function getContenData($source_url, $page = 1)
//{
//    $source_url = "http://newgame.17173.com/content/06142019/102849287_1.shtml";
////        $str = substr($source_url, 0, strlen($source_url) - 7);
//    $str = substr($source_url,  -7,1);
//
//
//    if ($page == 1) {
//        $url = $source_url;
//    } else {
//        $str = substr($source_url, 0, strlen($source_url) - 7);
//        $url = $str . $page . '.shtml';
//    }
//
//    $rules = [
//        'content_html' => ['.article-con', 'html'],
//    ];
//    $ql = self::$info_ql_obj->get($url)->getHtml();
//    $rt = QueryList::rules($rules)->html($ql)->query()->getData();
////        global $nextdate;
////        $nextdate = [];
////        if($rt->all()[0]['content_html']){
////            $page++;
////            $nextdate =$rt->all()[0]['content_html'];
////            $this->getContenData($source_url,$page);
////        }
////      die;
//    foreach ($rt->all() as $k=>$v){
//        dump($v);die;
//        if($v["content_html"]){
//            $page++;
////              $nextdate .= $v["content_html"];
//            $nextdate[]= $v["content_html"];
//            $this->getContenData($source_url,$page);
//
//        }
//
//    }
//
//
//}

//function deldir($dir) {
//    //先删除目录下的文件：
//    $dh=opendir($dir);
//    while ($file=readdir($dh)) {
//        if($file!="." && $file!="..") {
//            $fullpath=$dir."/".$file;
//            if(!is_dir($fullpath)) {
//                unlink($fullpath);
//            } else {
//                deldir($fullpath);
//            }
//        }
//    }
//
//    closedir($dh);
//    //删除当前文件夹：
//    if(rmdir($dir)) {
//        return true;
//    } else {
//        return false;
//    }
//}

// function delDirAndFile($path, $delDir = FALSE) {
//
//     if (is_array($path)) {
//        foreach ($path as $subPath)
//            delDirAndFile($subPath, $delDir);
//    }
//    if (is_dir($path)) {
//        $handle = opendir($path);
//        if ($handle) {
//            while (false !== ( $item = readdir($handle) )) {
//                if ($item != "." && $item != "..")
//                    is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
//            }
//            closedir($handle);
//            if ($delDir)
//                return rmdir($path);
//        }
//    } else {
//        if (file_exists($path)) {
//            return unlink($path);
//        } else {
//            return FALSE;
//        }
//    }
//    clearstatcache();
//}
//delDirAndFile('images');



/*$content = "\"<div id=\"article-body\" class=\"text-copy bodyCopy auto\">
<figure><p class=\"bordeaux-image-check\"><img src=\"https://vanilla.futurecdn.net/pcgamer/media/img/missing-image.svg\" alt=\"\" class=\" lazy-image lazy-image-loading lazyload optional-image\" onerror=\"this.parentNode.replaceChild(window.missingImage(),this)\" sizes=\"auto\" data-normal=\"https://vanilla.futurecdn.net/pcgamer/media/img/missing-image.svg\" data-src=\"https://cdn.mos.cms.futurecdn.net/YoNFbKfERE6LGbuNSsDvAd-320-80.jpg\" data-srcset=\"https://cdn.mos.cms.futurecdn.net/YoNFbKfERE6LGbuNSsDvAd-320-80.jpg 320w, https://cdn.mos.cms.futurecdn.net/YoNFbKfERE6LGbuNSsDvAd-650-80.jpg 650w\" data-sizes=\"auto\" data-original-mos=\"https://cdn.mos.cms.futurecdn.net/YoNFbKfERE6LGbuNSsDvAd.jpg\" data-pin-media=\"https://cdn.mos.cms.futurecdn.net/YoNFbKfERE6LGbuNSsDvAd.jpg\"></p></figure><p>On an average day, about a dozen new games are released on Steam. And while we think that's a good thing, it can be understandably hard to keep up with. Potentially exciting gems are sure to be lost in the deluge of new things to play unless you sort through every single game that is released on Steam. So that’s exactly what we’ve done. If nothing catches your fancy this week, we've gathered the <a href=\"https://www.pcgamer.com/best-pc-games/\" target=\"_blank\">best PC games</a> you can play right now and a running list of the <a href=\"https://www.pcgamer.com/new-games-2019/\" target=\"_blank\">new games of 2019</a>. </p>
<h2 id=\"198x\">198X</h2>
<div class=\"youtube-video\"><iframe data-lazy-priority=\"high\" data-lazy-src=\"https://www.youtube.com/embed/Avbkptw6mSM\" allowfullscreen></iframe></div>
<p><a href=\"https://store.steampowered.com/app/1086010/198X/\">Steam page</a><br>
Release: June 21<br>
Developer: Hi-Bit Studios<br>
Price: $9.99 | £8.99 | AU$14.50</p>
<p><aside class=\"hawk-widget\" data-widget-type=\"seasonal\" data-render-type=\"editorial\"></aside></p>
<p>As the name implies, 198X is set during an unspecified year of the 1980s, and is basically an episodic collection of arcade games, strung together by a narrative. \"This is the journey of Kid, a teenager stuck between the limitations of innocent youth and the obligations of inevitable adulthood,\" the Steam description reads. \"The story unfolds when Kid discovers the local arcade – finding new worlds, and new meaning, in video games.\" The games range arcade racers through to retro-styled dungeon crawlers, and in addition to the five games included there will presumably be more when forthcoming episodes drop.</p>
<h2 id=\"lovely-planet-2-april-skies\">Lovely Planet 2: April Skies</h2>
<div class=\"youtube-video\"><iframe data-lazy-priority=\"low\" data-lazy-src=\"https://www.youtube.com/embed/-KYR_W7GCRo\" allowfullscreen></iframe></div>
<p><a href=\"https://store.steampowered.com/app/1019590/Lovely_Planet_2_April_Skies/\"><u>Steam page</u></a><br>
Release: June 19<br>
Developer: QUICKTEQUILA<br>
Price: $9.99 | £7.19 | AU$14.50</p>
<p>Here's a sequel to the quietly beloved pastel-hued precision shooter that <a href=\"https://www.pcgamer.com/lovely-planet-review/\">Tyler really likes</a>. There are over 100 levels of reflex-oriented first-person shooting here, but instead of mercilessly slaughtering live humans or aliens or monsters, you're taking out cute blobs and cute giant fruit with your cute rubbery arrows. It's probably the most benign first-person shooter on Steam, and it's a lot of fun too: a kind of marriage of Doom and Super Meat Boy. </p>
<h2 id=\"steel-sword-story\">Steel Sword Story</h2>
<p class=\"mid__article\"></p>
<div class=\"youtube-video\"><iframe data-lazy-priority=\"low\" data-lazy-src=\"https://www.youtube.com/embed/1P6UBIWpUn8\" allowfullscreen></iframe></div>
<p><a href=\"https://store.steampowered.com/app/978190/Steel_Sword_Story/\">Steam page</a><br>
Release: June 21<br>
Developer: 8bits Fanatics<br>
Price: $4.99 | £3.99 | AU$7.50</p>
<p>Steel Sword Story is a new retro-styled platformer from the studio responsible for hard-as-nails cult classic 1001 Spikes. As a result, don't expect this to be a walk in park, but if you're averse to outrageous difficulty maybe the immaculate 8-bit inspired art work will help convince you. It's created using Pixel Game Maker MV, which shares a publisher with Steel Sword Story. </p>
<h2 id=\"summer-islands\">Summer Islands</h2>
<div class=\"youtube-video\"><iframe data-lazy-priority=\"low\" data-lazy-src=\"https://www.youtube.com/embed/4IVTcmW48Ng\" allowfullscreen></iframe></div>
<p><a href=\"https://store.steampowered.com/app/731650/Summer_Islands/\">Steam page</a><br>
Release: June 20<br>
Developer: MatthiasMa<br>
Price: $19.99 | £15.49 | AU$28.95</p>
<p>Launched into Early Access last week, Summer Islands is a tycoon game about building a fancy island resort. In addition to plotting your resort out – with cabins, hotels, golf courses, bars, and lots more – you'll also be contending with typically capricious tropical weather and, of course, competitors. The game boasts multiplayer and will likely release into 1.0 by the end of the year, with more building types, weather conditions, and other improvements.</p>
<h2 id=\"littlewood\">Littlewood</h2>
<div class=\"youtube-video\"><iframe data-lazy-priority=\"low\" data-lazy-src=\"https://www.youtube.com/embed/Xv7gnhAP3as\" allowfullscreen></iframe></div>
<p><a href=\"https://store.steampowered.com/app/894940/Littlewood/\">Steam page</a><br>
Release: June 18<br>
Developer: Sean Young<br>
Price: $14.99 | £11.39 | AU$21.50</p>
<p>Described by its creator as a \"peaceful and relaxing RPG\", Littlewood definitely shares a lot in common with Stardew Valley, but it's not exactly the same. For instance, Littlewood lets you design and populate your own town, and while there is farming, it doesn't look like you'll have to plant a single seed, if you don't want to. You could go collect rare items, chop some wood, go fishing, do some cooking, vendor wares as a merchant... basically, it's one of those games. It looks beautiful in action, too.</p>
<p><br><em>These games were released between June 17 and 24 2019. Some online stores give us a small cut if you buy something through one of our links. Read our</em> <a href=\"https://www.pcgamer.com/a-note-on-affiliate-links/\"><u><em>affiliate policy</em></u></a> <em>for more info.</em>  </p>
</div>
<div class=\"read-more-container\"></div>
<div class=\"jump-to-comments\"><a href=\"#comment-jump\">
<span>See comments</span>
<i class=\"icon icon-arrow-down-dotted\"></i>
</a></div>
<div id=\"this-will-be-used-for-mpu-2\"></div>\"
";


preg_match_all("/<iframe[^>]*?data-lazy-src=\"([^\"]*?)\"[^>]*?>/i", $content, $matches);

foreach ($matches[1] as $k => $v){

    $v = str_replace('embed','v',$v);
    $arr[] = '<object width="425" height="350" data='.$v.' type="application/x-shockwave-flash"><param name="src" value='.$v.' "/></object>';
}


preg_match_all("/<iframe .*?>.*?<\/iframe>/", $content, $mat);


$str_replace = str_replace($mat[0],$arr,$content);
var_dump($str_replace);
die;

// preg_match(
//    '/[\\?\\&]data-lazy-src=([^\\?\\&]+)/',
//     $content,
//    $matches
//);

 var_dump($matches[1]);*/


function imgtobase64($img='', $imgHtmlCode=true)
{
    $imageInfo = getimagesize($img);
    $base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
    return 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode(file_get_contents($img)));;
}

$image = imgtobase64("http://i.17173cdn.com/2fhnvk/YWxqaGBf/cms3/rPolDybnojfBExh.jpg");
echo  $image;die;


/**
 * 获取图片字符串
 */

function curlGet($url)
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

$image = curlGet("http://i.17173cdn.com/2fhnvk/YWxqaGBf/cms3/rPolDybnojfBExh.jpg");
echo  $image;die;



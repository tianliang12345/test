<?php
ini_set('memory_limit', '1024M');
require_once dirname(dirname(__FILE__))."/src/vendor/multi-array/MultiArray.php";
require_once dirname(dirname(__FILE__))."/src/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once dirname(dirname(__FILE__))."/src/class/Jieba.php";
require_once dirname(dirname(__FILE__))."/src/class/Finalseg.php";
require_once dirname(dirname(__FILE__))."/src/class/JiebaAnalyse.php";
require_once dirname(dirname(__FILE__))."/src/class/Posseg.php";
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\JiebaAnalyse;
use Fukuball\Jieba\Posseg;
Jieba::init();
Finalseg::init();
JiebaAnalyse::init();
Posseg::init();
$seg_list = Jieba::cut("小明硕士毕业于中国科学院计算所，后在日本京都大学深造");
var_dump($seg_list);die;
function loader($class) {
    $file = $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('loader');
?>
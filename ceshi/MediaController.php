<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers\Api\Yosi;

use App\Models\Entity\MediaGame;
use App\Models\Entity\YosiCategory;
use App\Models\Entity\YosiComment;
use App\Models\Entity\YosiCommentZan;
use App\Models\Entity\YosiGame;
use App\Models\Entity\YosiMediaImgtext;
use App\Models\Entity\YosiUser;
use App\Models\Entity\YosiMedia;
use App\Models\Entity\YosiMediaExtend;
use App\Models\Entity\YosiViewHistory;
use Swoft\Http\Message\Server\Response;
use PhpParser\Comment;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\Integer;
use Swoft\Db\Db;
use Swoft\Db\Query;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Http\Message\Server\Request;
use App\Middlewares\CheckAccessTokenMiddleware;
use App\Models\Entity\YosiCollection;
// use Swoft\View\Bean\Annotation\View;
// use Swoft\Http\Message\Server\Response;

/**
 * Class MediaController
 * @Controller(prefix="/api/yosi/media")
 * @package App\Controllers\Api\Yosi
 */
class MediaController{

    protected $image_size_200='?imageView2/5/w/360/h/360/interlace/1/q/100';
    /**
     * this is a example action. access uri path: /api/yosi
     * @RequestMapping(route="/api/yosi/media", method=RequestMethod::GET)
     * @return array
     */
    public function index(Request $request): array
    {
        $page = max((int)$request->query('page', 1),1);
        $pageSize = (int)$request->query('pageSize', 10);
        $offset=($page-1)*$pageSize;
        $best=(int)$request->query('best', 0);
        $recommend=(int)$request->query('recommend', 0);
        $type=(int)$request->query('type', 1);

//        $condition=array(
//            'type'=>$type,
//            [
//                "CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1)",
//                '>',
//                "CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER)"
//            ]
//        );
        $condition=' 1= 1';
//        if($best){
//           $condition['isbest']=1;
//        }
//        if($recommend){
//            $condition['is_recommend']=1;
//        }
        if($best){
            $condition.= ' and isbest =1 ';
        }
        if($recommend){
            $condition.=' and is_recommend ';
        }

        if($type==1){
            $condition.=" and CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1), UNSIGNED INTEGER) > CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER)";
        }


        $result = Db::query("select *    from yosi_media  where $condition  and status=1 and delete_time=0 Order by update_time desc  limit ".$offset.",".$pageSize)->getResult();
//
        $resultTotal = Db::query("select count(*) as total  from yosi_media  where $condition and status=1 and delete_time=0 ")->getResult();

      //  $result = YosiMedia::findAll($condition, ['orderby' => ['create_time' => 'ASC'], 'limit' => $pageSize, 'offset'=>$offset])->getResult();
//        $total = YosiMedia::count("*",$condition)->getResult();
//return returnData($result);
        $res=array();
        foreach ($result as $key=>$v){
            //$v=$value->getAttrs();
            $v['commentCount'] = YosiComment::count("*",['media_id'=>$v['id'],'status'=>1,'parent_id'=>0,'delete_time'=>0])->getResult();

//            $mediaExtend = YosiMediaExtend::FindOne(['media_id'=>$v['id']])->getResult();
//            $v['viewCount'] = !empty($mediaExtend)?$mediaExtend->getViewCount():0;
            if($v['type']==2){
                $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$v['id']])->getResult();
                $imageUrl = env('IMG_URL','');
                $v['images']=!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();
                foreach ($v['images'] as $key=>&$vimage){
                    $vimage=$imageUrl.'/'.$vimage;
                }
                $v['content']=!empty($yosiMediaImgtext)?$yosiMediaImgtext['cont']:'';
            }
            if($v['type']==1){
                $imageUrl = env('IMG_URL','');
                $vedioUrl = env('VEDIO_URL','');
                $v['vImg']=!empty($v['v_img'])?$imageUrl.'/'.$v['v_img']:'';
                $v['vQiuniukey']=!empty($v['v_qiuniukey'])?$vedioUrl.'/'.$v['v_qiuniukey']:'';
                $v['vLength']=$v['v_length'];



            }
            $v['updateTime']=empty($v['update_time'])?$v['create_time']:$v['update_time'];
            $v['createTime']=$v['create_time'];
            $v['viewCount']=$v['view_count'];

            $res[]=$v;
        }
        $data=array(
            'total'=>$resultTotal[0]['total'],
            'data'=>$res,
        );
        return returnData($data);
    }


    /**
     * this is a example action. access uri path: /api/yosi
     * @RequestMapping(route="/api/yosi/media/list", method=RequestMethod::GET)
     * @return array
     */
    public function list(Request $request): array
    {
        $page = max((int)$request->query('page', 1),1);
        $pageSize = (int)$request->query('pageSize', 10);
        $offset=($page-1)*$pageSize;
        $categoryId=(int)$request->query('categoryId', 0);
        $type=(int)$request->query('type', 1);
        $user_id=(int)$request->query('userId', 0);

        if(empty($categoryId)){
//            $condition=array(
//                'is_recommend'=>1,
//                'type'=>$type
//            );

            $conditionStr="    is_recommend =1 and type=".$type;

        }else{
            $yosiCategory = YosiCategory::FindOne(['id'=>$categoryId])->getResult();

            if(!empty($yosiCategory)){
                if($yosiCategory->getType()==1){
                    $categoryCondition=array(
                        'vedio_category_id'=>$categoryId
                    );
                }elseif($yosiCategory->getType()==2){
                    $categoryCondition=array(
                        'imgtext_category_id'=>$categoryId
                    );
                }
            }else{
                return returnData('',404,'类别不存在');

            }

            $yosiGameList= YosiGame::findAll($categoryCondition, ['orderby' => ['id' => 'DESC']])->getResult();

            $categoryArray=array();
            if(!empty($yosiGameList)){
                $yosiGameList=$yosiGameList->toArray();
                $categoryArray=array_column($yosiGameList,'id');
            }

//            $condition=array(
//                'game_id' => $categoryArray,
//                'type'=>$yosiCategory->getType()
//            );

            if(!empty($categoryArray)){

                $conditionStr="    game_id in ( ".implode(',',$categoryArray)." ) and type=".$yosiCategory->getType();

            }else{
                $conditionStr="    game_id <0 and type=".$yosiCategory->getType();

            }
        }
        $res=array();

        if($type==1){
            $conditionStr.=" and CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1), UNSIGNED INTEGER) > CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER)";
        }

        $mediaLIst = Db::query("select *    from yosi_media  where ".$conditionStr." and status=1 and delete_time=0  Order by istop desc ,ca desc limit ".$offset.",".$pageSize)->getResult();


      //  $resultTotal = Db::query("select count(*) as total    from yosi_media  where CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1), UNSIGNED INTEGER) > CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER) ")->getResult();

      //  $mediaLIst = YosiMedia::findAll($condition,['orderby' => ['id' => 'DESC'], 'limit' => $pageSize, 'offset'=>$offset])->getResult();

            foreach ($mediaLIst as $key=>$v){
               // $v=$value->getAttrs();
//                $mediaExtend = YosiMediaExtend::FindOne(['media_id'=>$v['id']])->getResult();
//                $v['viewCount'] = !empty($mediaExtend)?$mediaExtend->getViewCount():0;

                $v['commentCount'] = YosiComment::count("*",['media_id'=>$v['id'],'status'=>1,'parent_id'=>0,'delete_time'=>0])->getResult();
                if($user_id){
                    $collect = YosiCollection::FindOne(['media_id'=>$v['id'],'type'=>$v['type'],'user_id'=>$user_id])->getResult();

                    $v['collection']=!empty($collect)?1:0;

                }else{
                    $v['collection']=0;
                }

                //如果是图文，找初内容和图片
                if($v['type']==2){
                    $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$v['id']])->getResult();
                    $imageUrl = env('IMG_URL','');
                    $v['images']=!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();

                    foreach ($v['images'] as $key=>&$vimage){
                        if(!empty($vimage)){
                            $vimage=$imageUrl.'/'.$vimage.$this->image_size_200;
                        }
                    }
                }

                if($v['type']==1){
                    $imageUrl = env('IMG_URL','');
                    $vedioUrl = env('VEDIO_URL','');
                    $v['vImg']=!empty($v['v_img'])?$imageUrl.'/'.$v['v_img']:'';
                    $v['vQiuniukey']=!empty($v['v_qiuniukey'])?$vedioUrl.'/'.$v['v_qiuniukey']:'';
                    $v['vLength']=$v['v_length'];


                }
                $v['createTime']=$v['create_time'];
                $v['updateTime']=empty($v['update_time'])?$v['create_time']:$v['update_time'];
                $v['viewCount']=$v['view_count'];

                $res[]=$v;
            }


        $data=array(
            'data'=>$res,
        );
        return returnData($data);
    }


    /**
     * this is a example action. access uri path: /api/yosi
     * @RequestMapping(route="/api/yosi/media/searchList", method=RequestMethod::GET)
     * @return array
     */
    public function searchList(Request $request): array
    {
        $page = max((int)$request->query('page', 1),1);
        $pageSize = (int)$request->query('pageSize', 10);
        $offset=($page-1)*$pageSize;
        $keywords=$request->query('keywords', '');
        $type=(int)$request->query('type', 1);

        //如果是图文同时也搜内容
        if($type==1){
            $mediaExtendList = Query::table(YosiMedia::class,'m')->where('m.title', '%'.$keywords.'%','like')->andWhere('type',1)->orderBy('m.id','DESC')->limit($pageSize,$offset)->get()->getResult();
        }elseif($type==2){
            $mediaExtendList = Query::table(YosiMedia::class,'m')->openWhere('(')->where('m.title', '%'.$keywords.'%','like')->orWhere('t.cont','%'.$keywords.'%','like')->closeWhere(')')->andWhere('m.type',2)->leftJoin(YosiMediaImgtext::class,'m.id=t.media_id','t')->orderBy('m.id','DESC')->limit($pageSize,$offset)->get()->getResult();
        }else{
            $mediaExtendList=array();
        }

        $res=array();
        if($mediaExtendList){
            $mediaExtendList=array_column($mediaExtendList,'id');
            if(!empty($mediaExtendList)){

                $conditionStr="    id in ( ".implode(',',$mediaExtendList)." )";

            }else{
                $conditionStr="    id <0 ";

            }
            if($type==1){
                $conditionStr.=" and CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1), UNSIGNED INTEGER) > CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER)";
            }

            $mediaLIst = Db::query("select *    from yosi_media  where ".$conditionStr." and status=1 and delete_time=0 Order by update_time desc  limit ".$offset.",".$pageSize)->getResult();

            foreach ($mediaLIst as $key=>$v){
                //$v=$value->getAttrs();
//                $mediaExtend = YosiMediaExtend::FindOne(['media_id'=>$v['id']])->getResult();
//                $v['viewCount'] = !empty($mediaExtend)?$mediaExtend->getViewCount():0;

                $v['commentCount'] = YosiComment::count("*",['media_id'=>$v['id'],'status'=>1,'parent_id'=>0,'delete_time'=>0])->getResult();

                //如果是图文，找初内容和图片
                if($v['type']==2){
                    $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$v['id']])->getResult();
                    $imageUrl = env('IMG_URL','');
                    $v['images']=!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();

                    foreach ($v['images'] as $key=>&$vimage){
                        if(!empty($vimage)){
                            $vimage=$imageUrl.'/'.$vimage.$this->image_size_200;
                        }
                    }
                }

                if($v['type']==1){
                    $imageUrl = env('IMG_URL','');
                    $vedioUrl = env('VEDIO_URL','');
                    $v['vImg']=!empty($v['v_img'])?$imageUrl.'/'.$v['v_img']:'';
                    $v['vQiuniukey']=!empty($v['v_qiuniukey'])?$vedioUrl.'/'.$v['v_qiuniukey']:'';
                    $v['vLength']=$v['v_length'];


                }
                $v['updateTime']=empty($v['update_time'])?$v['create_time']:$v['update_time'];
                $v['createTime']=$v['create_time'];
                $v['viewCount']=$v['view_count'];
                $res[]=$v;
            }
        }

        $data=array(
            'data'=>$res,
        );
        return returnData($data);
    }

    /**获取详情
     * Get data list. access uri path: /api/user/
     * @RequestMapping(route="/api/yosi/media/view", method=RequestMethod::GET)
     *
     * @return array
     */
    public function view(Request $request): array
    {
        $id=(int)$request->input('id', 0);
        $user_id=(int)$request->input('userId', 0);

        $result=$this->show($user_id,$id,$request);

        return returnData($result);
    }



    //获取资源详情数据
    protected function show($user_id,$id,$request){
        //修改资源浏览量
        $media = YosiMedia::findOne(['id' => $id])->getResult();

        if(empty($media)){
            return returnData('',404,'资源不存在');
        }

        if(!empty($media)&&($media->getDeleteTime()>0)){
            return returnData('',404,'资源已删除');
        }

        $media->setViewCount($media->getViewCount()+1);
        $media->update()->getResult();

//        $mediaExtend = YosiMediaExtend::FindOne(['media_id'=>$media->getId()])->getResult();

        $result=$media->getAttrs();

        $imageUrl = env('IMG_URL','');
        if($result['type']==1){
            $vedioUrl = env('VEDIO_URL','');
            $result['vImg']=!empty($result['vImg'])?$imageUrl.'/'.$result['vImg']:'';
            $result['vQiuniukey']=!empty($result['vQiuniukey'])?$vedioUrl.'/'.$result['vQiuniukey']:'';

        }

//        $result['view_count']=!empty($mediaExtend)?$mediaExtend['viewCount']:0;

        $commentList = YosiComment::findAll(['media_id'=>$result['id'],'status'=>1,'parent_id'=>0,'delete_time'=>0], ['orderby' => ['id' => 'DESC'], 'limit' => 3, 'offset'=>0])->getResult();

        //获取用户头像和用户名
        $resCommentList=array();
        foreach ($commentList as $keyC=>$valueC){
            $vC=$valueC->getAttrs();
            $user = YosiUser::findOne(['id'=>$valueC->getUserId()])->getResult();
            if(empty($user)){
                $vC['username'] ='Yosi_用户';
                $vC['sex'] =2;
                $vC['avatar'] ='';
            }else{
                $vC['username'] =empty($user->getUsername())?'yosi_'.$user->getId():$user->getUsername();
                $vC['sex'] =$user->getSex();
                $vC['avatar'] =$imageUrl.'/'.$user->getAvatar();
            }

            $vC['childCommentCount'] = YosiComment::count("*",['parent_id'=>$vC['id'],'status'=>1,'delete_time'=>0])->getResult();

            $vC['isZan']=0;

            if($user_id){
                $YosiCommentZan = YosiCommentZan::findOne(['user_id'=>$user_id,'comment_id'=>$vC['id']])->getResult();
                if(!empty($YosiCommentZan)){
                    $vC['isZan']=1;
                }
            }

            $resCommentList[]=$vC;
        }

        $result['commentList']=$resCommentList;
        $result['commentCount'] = YosiComment::count("*",['media_id'=>$id,'status'=>1,'parent_id'=>0,'delete_time'=>0])->getResult();

        //获取推荐
        //  $mediaLIst= YosiMedia::findAll(['game_id'=>$result['gameId'],['id','!=',$result['id']]], ['orderby' => ['rand()' => 'ASC'], 'limit' => 3, 'offset'=>0])->getResult();

        $conditionStrGameId="    game_id= ".(int)$result['gameId']." and ";
        $conditionStr=" id !=".$result['id']." and type =".$result['type'];

        if($result['type']==1){
            $conditionStr.=" and CONVERT(substring(`v_dpi`,1,POSITION('*' in `v_dpi`)-1), UNSIGNED INTEGER) > CONVERT(substring(`v_dpi`,POSITION('*' in `v_dpi`)+1), UNSIGNED INTEGER)";
        }

        $mediaLIst = Db::query("select *    from yosi_media  where ".$conditionStrGameId.$conditionStr." and delete_time=0 and id >$id Order by update_time desc  limit 0,3")->getResult();

        if(count($mediaLIst)<3){
            $mediaLIst = Db::query("select *    from yosi_media  where ".$conditionStrGameId.$conditionStr." and delete_time=0  Order by update_time asc  limit 0,3")->getResult();
            if(count($mediaLIst)<3){
                $mediaLIst = Db::query("select *    from yosi_media  where ".$conditionStr." and delete_time=0  Order by rand() asc  limit 0,3")->getResult();
            }
        }

//        $mediaLIst=array();
//        if(!empty($mediaExtendList)){
//            $mediaExtendList=$mediaExtendList->toArray();
//            $mediaExtendList=array_column($mediaExtendList,'mediaId');
//            $mediaLIst = YosiMedia::findAll(['id' => $mediaExtendList])->getResult();
//        }

        $res=array();
        foreach ($mediaLIst as $key=>$v){
            // $v=$value->getAttrs();

            //评论
//            $mediaExtend = YosiMediaExtend::FindOne(['media_id'=>$v['id']])->getResult();
//            $v['viewCount'] = !empty($mediaExtend)?$mediaExtend->getViewCount():0;
            $v['commentCount'] = YosiComment::count("*",['media_id'=>$v['id'],'status'=>1,'parent_id'=>0,'delete_time'=>0])->getResult();

            //如果是图文，找初内容和图片
            if($v['type']==2){
                $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$v['id']])->getResult();
                $imageUrl = env('IMG_URL','');
                $v['images']=!empty($yosiMediaImgtext)&&!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();
                foreach ($v['images'] as $key=>&$vimage){
                    $vimage=$imageUrl.'/'.$vimage.$this->image_size_200;
                }
            }

            if($v['type']==1){
                $imageUrl = env('IMG_URL','');
                $vedioUrl = env('VEDIO_URL','');
                $v['vImg']=!empty($v['v_img'])?$imageUrl.'/'.$v['v_img']:'';
                $v['vQiuniukey']=!empty($v['v_qiuniukey'])?$vedioUrl.'/'.$v['v_qiuniukey']:'';
                $v['vLength']=$v['v_length'];


            }
            $v['updateTime']=empty($v['update_time'])?$v['create_time']:$v['update_time'];
            $v['createTime']=$v['create_time'];
            $v['viewCount']=$v['view_count'];
            $res[]=$v;
        }

        $result['recommandList']=$res;

        //收藏
        if($user_id){
            $collect = YosiCollection::FindOne(['media_id'=>$media->getId(),'user_id'=>$user_id])->getResult();
            $result['collection']=!empty($collect)?1:0;
            //添加浏览记录
            $create_time=time();
            $userViewHistory = YosiViewHistory::FindOne(['media_id'=>$media->getId(),'user_id'=>$user_id])->getResult();
            if(empty($userViewHistory)){
                $YosiViewHistory = new YosiViewHistory();
                $YosiViewHistory->setUserId($user_id);
                $YosiViewHistory->setType($result['type']);
                $YosiViewHistory->setMediaId($media->getId());
                $YosiViewHistory->setCreateTime($create_time);
                $YosiViewHistory->save()->getResult();
            }else{
                $userViewHistory->setCreateTime($create_time);
                $userViewHistory->update()->getResult();
            }


        }else{
            $result['collection']=0;
        }

        //如果是图文，找初内容和图片
        if($result['type']==2){
            $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$media->getId()])->getResult();
            $imageUrl = env('IMG_URL','');
            $result['images']=!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();
            foreach ($result['images'] as $key=>&$vimage){
                $vimage=$imageUrl.'/'.$vimage;
            }

            $result['content']=!empty($yosiMediaImgtext)?$yosiMediaImgtext['cont']:'';
        }

//
//        $a = [
//            ['text'=>'whereRaw 和 orWhereRaw 方法将原生的 where 注入到你的查询中。这两个方法的第二个参数还是可选项，值还是绑定参数的数组','img'=>'http://qn.yocotv.com/FtuY4rlJWBXf6LPLzpgx4Scvky8n'],
//            ['text'=>'havingRaw 和 orHavingRaw 方法可以用于将原生字符串设置为 having 语句的值','img'=>'http://qn.yocotv.com/FhZx3Oq5ksWc6FDGSJB_QLymasfG'],
//            ['text'=>'方法可用于将原生字符串设置为 ,order by 子句的值','img'=>'http://qn.yocotv.com/FmmDLdt_KjNva7FxBZzYJuon-W3X'],
//            ['text'=>'查询构造器也可以编写 join 方法。若要执行基本的 「内链接」，你可以在查询构造器实例上使用 join 方法。传递给 join 方法的第一个参数是你需要连接的表的名称，而其他参数则使用指定连接的字段约束','img'=>'http://qn.yocotv.com/Fg6VYSLHpYaQ-ck5kHggtMTy2i3k'],
//        ];
//        dump(json_encode($a));
//        die;

        //如果是混排，找内容和图片
        if($result['type'] ==3){
            $yosiMediaImgtext = YosiMediaImgtext::FindOne(['media_id'=>$media->getId()])->getResult();
            $imageUrl = env('IMG_URL','');
            $result['images']=!empty($yosiMediaImgtext['images'])?explode(',',$yosiMediaImgtext['images']):array();
            foreach ($result['images'] as $key=>&$vimage){
                $vimage=$imageUrl.'/'.$vimage;
            }
            $result['newList']=!empty($yosiMediaImgtext)?json_decode($yosiMediaImgtext['cont']):'';
        }


        $result['share']=env('DOMAIN').'/share.html?id='.$result['id'];

        return $result;
    }

    /**
     * Create a new record. access uri path: /api/yosi/media/create
     * @RequestMapping(route="/api/yosi/media/create", method=RequestMethod::POST)
     * @Strings(from=ValidatorFrom::POST, name="type", min=1, max=2, default="", template="字段{name}必须在{min}到{max}之间,您提交的值是{value}")
     *
     * @param Request $request
     * @Middleware(CheckAccessTokenMiddleware::class)
     * @return array
     */
    public function post(Request $request): array
    {
        $data=$request->input();


        $create_time=time();
        $yosiMedia = new YosiMedia();
        $data['release_time']=$create_time;
        $data['create_time']=$create_time;
        $data['update_time']=$create_time;
        $id = $yosiMedia->fill($data)->save()->getResult();


        if($data['type']==2){
            $yosiMediaImgtext=new YosiMediaImgtext();

            if(!empty($data['images'])&&!empty($data['cont'])){
                $imgTextData=array(
                    'media_id'=>$id,
                    'images'=>$data['images'],
                    'cont'=>htmlspecialchars($data['cont'])
                );
                $yosiMediaImgtext->fill($imgTextData)->save()->getResult();
            }

        }
        return returnData();

    }


    /**倒入media_game 表的数据
     * this is a example action. access uri path: /api/yosi
     * @RequestMapping(route="/api/yosi/media/gameid", method=RequestMethod::GET)
     * @return array
     */
    public function gameid(Request $request): array
    {
        set_time_limit(0);
        $page = max((int)$request->query('page', 1),1);
        $pageSize = (int)$request->query('pageSize', 10);
        $offset=($page-1)*$pageSize;
        $id=(int)$request->query('id', 0);
        $total = YosiMedia::count("*")->getResult();
        for ($i=1;$i<=15752;$i++){
            $yosiMedia = YosiMedia::FindOne(['id'=>$i])->getResult();
            if(!empty($yosiMedia)){
                $mediaGame = MediaGame::FindOne(['media_id'=>$i])->getResult();
                if(!empty($mediaGame)){
                    $yosiMedia->setGameId($mediaGame['gameId']);
                    $yosiMedia->update()->getResult();
                }
            }
        }


        return returnData();
    }



}

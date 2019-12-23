<?php
namespace App\Models\Entity;

use Swoft\Db\Model;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Types;

/**
 * @Entity()
 * @Table(name="yosi_media")
 * @uses      YosiMedia
 */
class YosiMedia extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $number 视频编号
     * @Column(name="number", type="string", length=15, default="")
     */
    private $number;

    /**
     * @var int $type 1->视频， 2->图文
     * @Column(name="type", type="tinyint", default=1)
     */
    private $type;

    /**
     * @var string $title 视频标题
     * @Column(name="title", type="string", length=255, default="")
     */
    private $title;

    /**
     * @var string $description 视频描述
     * @Column(name="description", type="string", length=255, default="")
     */
    private $description;

    /**
     * @var string $vQiuniukey 七牛的视频key
     * @Column(name="v_qiuniukey", type="string", length=255, default="")
     */
    private $vQiuniukey;

    /**
     * @var string $vDpi 视频尺寸
     * @Column(name="v_dpi", type="string", length=30, default="")
     */
    private $vDpi;

    /**
     * @var string $vImg 视频封面
     * @Column(name="v_img", type="string", length=255, default="")
     */
    private $vImg;

    /**
     * @var int $vLength 播放时长
     * @Column(name="v_length", type="integer", default=0)
     */
    private $vLength;

    /**
     * @var int $vWeight 视频权重
     * @Column(name="v_weight", type="integer", default=0)
     */
    private $vWeight;

    /**
     * @var int $userId 用户id
     * @Column(name="user_id", type="integer", default=0)
     */
    private $userId;

    /**
     * @var int $onlyMe 是否仅自己可见 0代表所有人 1代表仅自己可见
     * @Column(name="only_me", type="tinyint", default=0)
     */
    private $onlyMe;

    /**
     * @var int $status 0禁止 | 1 正常 | 2 待审核|3 待转码  | 4 待上传 |5 正在转码 |6 正在上传
     * @Column(name="status", type="tinyint", default=2)
     */
    private $status;

    /**
     * @var int $uploadtype 上传类型 1外网上传 2内网上传
     * @Column(name="uploadtype", type="tinyint", default=0)
     */
    private $uploadtype;

    /**
     * @var int $platform 0 PC端 | 1 ios | 2 android | 3 web
     * @Column(name="platform", type="tinyint", default=0)
     */
    private $platform;

    /**
     * @var int $isRecommend 是否添加入推荐池
     * @Column(name="is_recommend", type="tinyint", default=0)
     */
    private $isRecommend;

    /**
     * @var int $istop 置顶
     * @Column(name="istop", type="tinyint", default=0)
     */
    private $istop;

    /**
     * @var int $isbest 精华
     * @Column(name="isbest", type="tinyint", default=0)
     */
    private $isbest;

    /**
     * @var int $commentTime 评论时间
     * @Column(name="comment_time", type="integer", default=0)
     */
    private $commentTime;

    /**
     * @var int $releaseTime 发布时间
     * @Column(name="release_time", type="integer", default=0)
     */
    private $releaseTime;

    /**
     * @var int $deleteTime 是否删除
     * @Column(name="delete_time", type="integer", default=0)
     */
    private $deleteTime;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer", default=0)
     */
    private $createTime;

    /**
     * @var int $updateTime 
     * @Column(name="update_time", type="integer", default=0)
     */
    private $updateTime;

    /**
     * @var int $gameId 游戏类别id
     * @Column(name="game_id", type="integer", default=0)
     */
    private $gameId;

    /**
     * @var int $viewCount 浏览数
     * @Column(name="view_count", type="integer", default=0)
     */
    private $viewCount;

    /**
     * @var int $push 是否推送
     * @Column(name="push", type="tinyint", default=0)
     */
    private $push;

    /**
     * @var int $fire 是否显示火
     * @Column(name="fire", type="tinyint", default=0)
     */
    private $fire;

    /**
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * 视频编号
     * @param string $value
     * @return $this
     */
    public function setNumber(string $value): self
    {
        $this->number = $value;

        return $this;
    }

    /**
     * 1->视频， 2->图文
     * @param int $value
     * @return $this
     */
    public function setType(int $value): self
    {
        $this->type = $value;

        return $this;
    }

    /**
     * 视频标题
     * @param string $value
     * @return $this
     */
    public function setTitle(string $value): self
    {
        $this->title = $value;

        return $this;
    }

    /**
     * 视频描述
     * @param string $value
     * @return $this
     */
    public function setDescription(string $value): self
    {
        $this->description = $value;

        return $this;
    }

    /**
     * 七牛的视频key
     * @param string $value
     * @return $this
     */
    public function setVQiuniukey(string $value): self
    {
        $this->vQiuniukey = $value;

        return $this;
    }

    /**
     * 视频尺寸
     * @param string $value
     * @return $this
     */
    public function setVDpi(string $value): self
    {
        $this->vDpi = $value;

        return $this;
    }

    /**
     * 视频封面
     * @param string $value
     * @return $this
     */
    public function setVImg(string $value): self
    {
        $this->vImg = $value;

        return $this;
    }

    /**
     * 播放时长
     * @param int $value
     * @return $this
     */
    public function setVLength(int $value): self
    {
        $this->vLength = $value;

        return $this;
    }

    /**
     * 视频权重
     * @param int $value
     * @return $this
     */
    public function setVWeight(int $value): self
    {
        $this->vWeight = $value;

        return $this;
    }

    /**
     * 用户id
     * @param int $value
     * @return $this
     */
    public function setUserId(int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * 是否仅自己可见 0代表所有人 1代表仅自己可见
     * @param int $value
     * @return $this
     */
    public function setOnlyMe(int $value): self
    {
        $this->onlyMe = $value;

        return $this;
    }

    /**
     * 0禁止 | 1 正常 | 2 待审核|3 待转码  | 4 待上传 |5 正在转码 |6 正在上传
     * @param int $value
     * @return $this
     */
    public function setStatus(int $value): self
    {
        $this->status = $value;

        return $this;
    }

    /**
     * 上传类型 1外网上传 2内网上传
     * @param int $value
     * @return $this
     */
    public function setUploadtype(int $value): self
    {
        $this->uploadtype = $value;

        return $this;
    }

    /**
     * 0 PC端 | 1 ios | 2 android | 3 web
     * @param int $value
     * @return $this
     */
    public function setPlatform(int $value): self
    {
        $this->platform = $value;

        return $this;
    }

    /**
     * 是否添加入推荐池
     * @param int $value
     * @return $this
     */
    public function setIsRecommend(int $value): self
    {
        $this->isRecommend = $value;

        return $this;
    }

    /**
     * 置顶
     * @param int $value
     * @return $this
     */
    public function setIstop(int $value): self
    {
        $this->istop = $value;

        return $this;
    }

    /**
     * 精华
     * @param int $value
     * @return $this
     */
    public function setIsbest(int $value): self
    {
        $this->isbest = $value;

        return $this;
    }

    /**
     * 评论时间
     * @param int $value
     * @return $this
     */
    public function setCommentTime(int $value): self
    {
        $this->commentTime = $value;

        return $this;
    }

    /**
     * 发布时间
     * @param int $value
     * @return $this
     */
    public function setReleaseTime(int $value): self
    {
        $this->releaseTime = $value;

        return $this;
    }

    /**
     * 是否删除
     * @param int $value
     * @return $this
     */
    public function setDeleteTime(int $value): self
    {
        $this->deleteTime = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCreateTime(int $value): self
    {
        $this->createTime = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setUpdateTime(int $value): self
    {
        $this->updateTime = $value;

        return $this;
    }

    /**
     * 游戏类别id
     * @param int $value
     * @return $this
     */
    public function setGameId(int $value): self
    {
        $this->gameId = $value;

        return $this;
    }

    /**
     * 浏览数
     * @param int $value
     * @return $this
     */
    public function setViewCount(int $value): self
    {
        $this->viewCount = $value;

        return $this;
    }

    /**
     * 是否推送
     * @param int $value
     * @return $this
     */
    public function setPush(int $value): self
    {
        $this->push = $value;

        return $this;
    }

    /**
     * 是否显示火
     * @param int $value
     * @return $this
     */
    public function setFire(int $value): self
    {
        $this->fire = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 视频编号
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * 1->视频， 2->图文
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 视频标题
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 视频描述
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * 七牛的视频key
     * @return string
     */
    public function getVQiuniukey()
    {
        return $this->vQiuniukey;
    }

    /**
     * 视频尺寸
     * @return string
     */
    public function getVDpi()
    {
        return $this->vDpi;
    }

    /**
     * 视频封面
     * @return string
     */
    public function getVImg()
    {
        return $this->vImg;
    }

    /**
     * 播放时长
     * @return int
     */
    public function getVLength()
    {
        return $this->vLength;
    }

    /**
     * 视频权重
     * @return int
     */
    public function getVWeight()
    {
        return $this->vWeight;
    }

    /**
     * 用户id
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 是否仅自己可见 0代表所有人 1代表仅自己可见
     * @return int
     */
    public function getOnlyMe()
    {
        return $this->onlyMe;
    }

    /**
     * 0禁止 | 1 正常 | 2 待审核|3 待转码  | 4 待上传 |5 正在转码 |6 正在上传
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 上传类型 1外网上传 2内网上传
     * @return int
     */
    public function getUploadtype()
    {
        return $this->uploadtype;
    }

    /**
     * 0 PC端 | 1 ios | 2 android | 3 web
     * @return int
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * 是否添加入推荐池
     * @return int
     */
    public function getIsRecommend()
    {
        return $this->isRecommend;
    }

    /**
     * 置顶
     * @return int
     */
    public function getIstop()
    {
        return $this->istop;
    }

    /**
     * 精华
     * @return int
     */
    public function getIsbest()
    {
        return $this->isbest;
    }

    /**
     * 评论时间
     * @return int
     */
    public function getCommentTime()
    {
        return $this->commentTime;
    }

    /**
     * 发布时间
     * @return int
     */
    public function getReleaseTime()
    {
        return $this->releaseTime;
    }

    /**
     * 是否删除
     * @return int
     */
    public function getDeleteTime()
    {
        return $this->deleteTime;
    }

    /**
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * 游戏类别id
     * @return int
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * 浏览数
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * 是否推送
     * @return int
     */
    public function getPush()
    {
        return $this->push;
    }

    /**
     * 是否显示火
     * @return int
     */
    public function getFire()
    {
        return $this->fire;
    }

}

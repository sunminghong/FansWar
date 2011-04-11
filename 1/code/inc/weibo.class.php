<?php
/**
 * @file			weibo.class.php
 * @CopyRight		(C)1996-2099 SINA Inc.
 * @Project			Xweibo
 * @Author			heli <heli1@staff.sina.com.cn>
 * @Create Date:	2010-07-08
 * @Modified By:	heli/2010-11-15
 * @Brief			微博api操作类
 */

include_once P_CLASS.'/oauth.class.php';

 class weibo
 {

	var $http;
	var $token;
	var $shal_method;
	var $consumer;
	var $storage;
	var $format = 'json';

	/**
	 * 构造函数
	 *
	 * @param $oauth_token
	 * @param $oauth_token_secret
	 * @return
	 */
	function weibo($oauth_token = NULL, $oauth_token_secret = NULL)
	{
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
        $this->consumer = new OAuthConsumer(WB_AKEY, WB_SKEY);
		$this->setConfig();

		$this->http = APP::ADP('http');
	}


	/**
	 * 设置
	 *
	 * @return
	 */
	function setConfig()
	{
		$key = USER::getOAuthKey(true);

        if (!empty($key['oauth_token']) && !empty($key['oauth_token_secret'])) {
            $this->token = new OAuthConsumer($key['oauth_token'], $key['oauth_token_secret']);
        } else {
            $this->token = NULL;
        }
	}

	/**
	 * 手动设置Token
	 *
	 * @param int $type 身份类型，1代表当前用户，2代表内置用户，3代表指定用户，默认是1
	 * @param $oauth_token
	 * @param $oauth_token_secret
	 * @return unknown
	 */
	function setToken($type = 1, $oauth_token = null, $oauth_token_secret = null)
	{
		if ($type == 1) {
			$token = USER::getOAuthKey(true);
			$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		} elseif ($type == 2) {
			$this->token = new OAuthConsumer(WB_USER_OAUTH_TOKEN, WB_USER_OAUTH_TOKEN_SECRET);
		} elseif ($type == 3) {
			$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
		} else {
			$this->token = null;
		}
	}

	/**
	 * 格式化错误信息
	 *
	 * @param array $error api返回的错误信息
	 * @return unknown
	 */
	function throwException($error)
	{
		$error = json_decode($error, true);
		$error_array = explode(':', $error['error']);
		switch ($error_array[0]) {
			/*API升级维护时，进行全站停止操作 开始*/
			case '40085':
				$api = APP::O('apiStop');
				$api->setStop();
				break;
			/*API升级维护时，进行全站停止操作 结束*/
			case '40113':
				$msg = array('error_code' => '1040000', 'error' => $error['error']);
				break;
			case '40302':
				$msg = array('error_code' => '1040011', 'error' => $error['error']);
				break;
			case '40312':
				$msg = array('error_code' => '1040009', 'error' => $error['error']);
				break;
			case '40313':
				$msg = array('error_code' => '1040001', 'error' => $error['error']);
				break;
			case '40303':
				$msg = array('error_code' => '1020805', 'error' => $error['error']);
				break;
			case '40012':
				if (strpos($error['request'], 'update')) {
					$error_code = '1020000';
				} elseif (strpos($error['request'], 'comment')) {
					$error_code = '1020401';
				} elseif (strpos($error['request'], 'reply')) {
					$error_code = '1020502';
				} elseif (strpos($error['request'], 'direct_messages')) {
					$error_code = '1020900';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40013':
				if (strpos($error['request'], 'update')) {
					$error_code = '1020001';
				} elseif (strpos($error['request'], 'upload')) {
					$error_code = '1020103';
				} elseif (strpos($error['request'], 'repost')) {
					$error_code = '1020201';
				} elseif (strpos($error['request'], 'comment')) {
					$error_code = '1020403';
				} elseif (strpos($error['request'], 'reply')) {
					$error_code = '1020503';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40025':
				$msg = array('error_code' => '1020002', 'error' => $error['error']);
				break;
			case '40035':
				$msg = array('error_code' => '1021002', 'error' => $error['error']);
				break;
			case '40009':
				$msg = array('error_code' => '1020100', 'error' => $error['error']);
				break;
			case '40045':
				$msg = array('error_code' => '1020101', 'error' => $error['error']);
				break;
			case '40008':
				$msg = array('error_code' => '1020102', 'error' => $error['error']);
				break;
			case '40027':
				$msg = array('error_code' => '1040008', 'error' => $error['error']);
				break;
			case '40028':
				if (strpos($error['error'], 'M01129')) {
					$error_code = '1021300';
				} elseif (strpos($error['error'], '发表违法和不良信息')) {
					$error_code = '1040004';
				} elseif (strpos($error['error'], ':发微博太多')) {
					$error_code = '1040006';
				} elseif (strpos($error['error'], '发评论太多')) {
					$error_code = '1040007';
				} elseif (strpos($error['error'], '昵称')) {
					$error_code = '1021301';
				} elseif (strpos($error['error'], '添加')) {
					$error_code = '1020800';
				} elseif (strpos($error['request'], 'create')) {
					$error_code = '1020801';
				} elseif (strpos($error['request'], 'destroy')) {
					$error_code = '1020802';
				} elseif (strpos($error['error'], '你无法进行评论')) {
					$error_code = '1020404';
				} else {
					$error_code = '1020104';
				}
				/*
			   	else {
					$error_code = '1020803';
				}
				 */
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40016':
				if (strpos($error['request'], 'repost')) {
					$error_code = '1020200';
				} elseif (strpos($error['request'], 'destroy')) {
					$error_code = '1020300';
				} elseif (strpos($error['request'], 'comment')) {
					$error_code = '1020400';
				} elseif (strpos($error['request'], 'reply')) {
					$error_code = '1020500';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40031':
				if (strpos($error['request'], 'destroy')) {
					$error_code = '1020301';
				} elseif (strpos($error['request'], 'comment')) {
					$error_code = '1020402';
				} elseif (strpos($error['request'], 'reply')) {
					$error_code = '1020504';
				} elseif (strpos($error['request'], 'favorites')) {
					$error_code = '1020701';
				} else {
					$error_code = $error_array[0];
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40036':
				if (strpos($error['request'], 'destroy')) {
					$error_code = '1020302';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40020':
				if (strpos($error['reqeust'], 'reply')) {
					$error_code = '1020501';
				} elseif (strpos($error['request'], 'comment_destroy')) {
					$error_code = '1020600';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '40015':
				if (strpos($error['request'], 'comment_destroy')) {
					$error_code = '1020601';
				}
				$msg = array('error_code' => $error_code, 'error' => $error['error']);
				break;
			case '50001':
				$msg = array('error_code' => '1020700', 'error' => $error['error']);
				break;
			case '40017':
				$msg = array('error_code' => '1020902', 'error' => $error['error']);
				break;
			case '40021':
				$msg = array('error_code' => '1020903', 'error' => $error['error']);
				break;
			case '40010':
				$msg = array('error_code' => '1020904', 'error' => $error['error']);
				break;
			case '40022':
				$msg = array('error_code' => '1040003', 'error' => $error['error']);
				break;
			case '40026':
				$msg = array('error_code' => '1021200', 'error' => $error['error']);
				break;
			case '40072':
				$msg = array('error_code' => '1040008', 'error' => $error['error']);
				break;
			default:
				$msg = array('error_code' => '1050000', 'error' => 'unknow systme error');
		}

		return RST('', $msg['error_code'], $msg['error'], 0);
	}


	//数据集(timeline)接口

	/**
	 * 获取最新更新的公共微博消息
	 *
	 * @param bool $base_app 是否只获取当前应用的数据
	 * @param bool $oauth 是否使用oauth方式请求api
	 * @return array
	 */
	 function getPublicTimeline($base_app = '0', $oauth = true)
	 {
		$url = WEIBO_API_URL.'statuses/public_timeline.'.$this->format;
		$params = array();
		$params['base_app'] = $base_app;

		if ($oauth == false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}

		return $response;
	 }


	/**
	 * 获取当前用户所关注用户的最新微博信息
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比sinace_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	 function getHomeTimeline($count = null, $page = null, $since_id = null, $max_id = null, $base_app = '0')
	 {
		$url = WEIBO_API_URL.'statuses/home_timeline.'.$this->format;
		$params = array();
		$params['base_app'] = $base_app;
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户所关注用户的最新微博信息
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @param int|string $pub_type 返回某一发布类型结果的微博  类型有全部-0, 原创-1, 转发-2默认返回全部
	 * @param int|stirng $content_type 返回某一内容类型结果的微博 类型有全部-0, 图片-1, 音乐-2, 视频-3, 纯文本-4,
	 * 默认返回全部
	 * @return array
	 */
	 function getFriendsTimeline($count = null, $page = null, $since_id = null, $max_id = null, $base_app = '0', $feature = 0)
	 {
		$url = WEIBO_API_URL.'statuses/friends_timeline.'.$this->format;
		$params = array();
		$params['base_app'] = $base_app;
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}
		if ($feature) {
			$params['feature'] = $feature;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取用户发布的微博信息列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param int|string $since_id 返回比since_id的的微博数据
	 * @parma int|string $max_id 返回不大于max_id的微博数据
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param bool $oauth 是否使用oauth方式访问api
	 * @return array
	 */
	 function getUserTimeline($id = null, $user_id = null, $name = null, $since_id = null, $max_id = null, $count = null, $page = null, $feature = 0, $oauth = true, $base_app = '0')
	 {
		if ($id) {
			$url = WEIBO_API_URL.'statuses/user_timeline/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'statuses/user_timeline.'.$this->format;
		}

		$params = array();
		$params['base_app'] = $base_app;
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}
		if ($feature) {
			$params['feature'] = $feature;
		}

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}
		return $response;
	 }


	 /**
	  * 获取@当前用户的微博列表
	  *
	  * @param int $count 获取条数
	  * @param int $page 页码数
	  * @param int|string $since_id 返回比since_id大的微博数据
	  * @param int|string $max_id 返回不大于max_id的微博数据
	  * @return array
	  */
	 function getMentions($count = null, $page = null, $since_id = null, $max_id = null)
	 {
		$url = WEIBO_API_URL.'statuses/mentions.'.$this->format;

		$params = array();
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户发送及收到的评论列表
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	 function getCommentsTimeline($count = null, $page = null, $since_id = null, $max_id = null)
	 {
		$url = WEIBO_API_URL.'statuses/comments_timeline.'.$this->format;

		$params = array();
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户发出的评论
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	 function getCommentsByMe($count = null, $page = null, $since_id = null, $max_id = null)
	 {
		$url = WEIBO_API_URL.'statuses/comments_by_me.'.$this->format;

		$params = array();

		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户收到的评论列表
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	function getCommentsToMe($count = null, $page = null, $since_id = null, $max_id = null)
	{
		$url = WEIBO_API_URL.'statuses/comments_to_me.'.$this->format;

		$params = array();
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}


	/**
	 * 获取指定微博的评论列表
	 *
	 * @param int|string $id 微博id
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @return array
	 */
	 function getComments($id, $count = null, $page = null)
	 {
		$url = WEIBO_API_URL.'statuses/comments.'.$this->format;

		$params = array();
		$params['id'] = $id;

		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 批量获取一组微博的评论数及转发数
	 *
	 * @param string|array $ids 微博id,多个id以数组形式传入
	 * @return array
	 */
	 function getCounts($ids, $oauth = true)
	 {
		$url = WEIBO_API_URL.'statuses/counts.'.$this->format;

		$params = array();
		if (is_array($ids)) {
			$params['ids'] = implode(',', $ids);
		} else {
			$params['ids'] = $ids;
		}

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}

		return $response;
	 }


	/**
	 * 获取当前用户未读消息数
	 *
	 * @param int|string $with_new_status 默认为0。1表示结果包含是否有新微博，0表示结果不包含是否有新微博
	 * @param int|string $since_id 微博id，返回此条id之后，是否有新微博产生，有返回1，没有返回0
	 * @return array
	 */
	 function getUnread($with_new_status = null, $since_id = null)
	 {
		$url = WEIBO_API_URL.'statuses/unread.'.$this->format;

		$params = array();
		if ($with_new_status) {
			$params['with_new_status'] = $with_new_status;
		}
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	 //访问接口

	/**
	 * 根据ID获取单条微博信息内容
	 *
	 * @param int|string 微博id
	 * @return array
	 */
	 function getStatuseShow($id)
	 {
		$url = WEIBO_API_URL.'statuses/show/'.$id.'.'.$this->format;

		$params = array();

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 发布一条微博信息
	 *
	 * @param string $status 微博内容
	 * @return array
	 */
	 function update($status)
	 {
		$url = WEIBO_API_URL.'statuses/update.'.$this->format;

		$params = array();
		$params['status'] = urlencode($status);

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	 /**
	  * 上传图片并发布一条微博信息
	  *
	  * @param string $status 微博内容
	  * @param string $pic 图片路径
	  * @param string $lat 纬度
	  * @param string $long 经度
	  * @return array
	  */
	 function upload($status, $pic, $lat = null, $long = null)
	 {
		$url = WEIBO_API_URL.'statuses/upload.'.$this->format;

		$params = array();
		$params['status'] = urlencode($status);
		$params['pic'] = '@'.$pic;

		if ($lat) {
			$params['lat'] = $lat;
		}
		if ($long) {
			$params['long'] = $long;
		}
		$response = $this->oAuthRequest($url, 'post', $params, true);

		return $response;
	 }

	/**
	 * 上传图片，返回pcid，缩略图，原图
	 *
	 * @param string $pic 上传的图片路径
	 * @return array
	 */
	 function uploadPic($pic)
	 {
		$url = WEIBO_API_URL.'statuses/upload_pic.'.$this->format;

		$params = array();
		$params['pic'] = '@'.$pic;

		$response = $this->oAuthRequest($url, 'post', $params, true);

		return $response;
	 }

	 /**
	  * 发布图片微博
	  *
	  * @param string $status 微博内容
	  * @param string $picid 图片id
	  * @param string $picurl 图片url 必须是以http:://开头
	  * @return array
	  */
	 function uploadUrlText($status, $picid = null, $picurl = null)
	 {
		$url = WEIBO_API_URL.'statuses/upload_url_text.'.$this->format;

		$params = array();
		$params['status'] = urlencode($status);
		if ($picid) {
			$params['pic_id'] = $picid;
		}
		if ($picurl) {
			$params['url'] = $picurl;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 删除微博
	 *
	 * @param int|string $id 微博id
	 * @return array
	 */
	 function destroy($id)
	 {
		$url = WEIBO_API_URL.'statuses/destroy/'.$id.'.'.$this->format;

		$params = array();

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 转发一条微博信息（可加评论）
	 *
	 * @param int|string 微博id
	 * @param string $status 微博内容
	 * @return array
	 */
	 function repost($id, $status = null)
	 {
		$url = WEIBO_API_URL.'statuses/repost.'.$this->format;

		$params = array();
		$params['id'] = $id;
		if ($status) {
			$params['status'] = urlencode($status);
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 对一条微博信息进行评论
	 *
	 * @param int|string $id 微博id
	 * @param string $comment 评论内容
	 * @param int|string $cid 要评论的评论id
	 * @return array
	 */
	 function comment($id, $comment, $cid = null)
	 {
		$url = WEIBO_API_URL.'statuses/comment.'.$this->format;

		$params = array();
		$params['id'] = $id;
		$params['comment'] = urlencode($comment);
		if ($cid) {
			$params['cid'] = $cid;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 删除当前用户的微博评论信息
	 *
	 * @param int|sting 评论id
	 * @return array
	 */
	 function commentDestroy($id)
	 {
		$url = WEIBO_API_URL.'statuses/comment_destroy/'.$id.'.'.$this->format;

		$params = array();

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }

	 /**
	  * 批量删除评论
	  *
	  * @param string $ids 评论id, 多个用逗号隔开(最多20个)
	  * @return array
	  */
	 function commentDestroyBatch($ids)
	 {
		$url = WEIBO_API_URL.'statuses/comment/destroy_batch.'.$this->format;

		$params = array();
		$params['ids'] = join(',', $ids);
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	 /**
	  * 回复微博评论信息
	  *
	  * @param int|string 微博id
	  * @param int|string 评论id
	  * @param string $comment 回复评论内容
	  * @return array
	  */
	 function reply($id, $cid, $comment)
	 {
		$url = WEIBO_API_URL.'statuses/reply.'.$this->format;

		$params = array();
		$params['id'] = $id;
		$params['comment'] = urlencode($comment);
		$params['cid'] = $cid;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }



	 //用户接口

	/**
	 * 根据用户ID获取用户资料（授权用户）
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param bool $oauth 是否用户oauth方式请求api
	 * @return array
	 */
	function getUserShow($id = null, $user_id = null, $name = null, $oauth = true)
	{
		if ($id) {
			$url = WEIBO_API_URL.'users/show/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'users/show.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}
		return $response;
	}


	/**
	 * 获取当前用户关注对象列表及最新一条微博信息
	 *
	 * @param int|string $id 用户id
	 * @parma int $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFriends($id = null, $user_id = null, $name = null, $cursor = null, $count = null)
	 {
		if ($id) {
			$url = WEIBO_API_URL.'statuses/friends/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'statuses/friends.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		if ($count) {
			$params['count'] = $count;
		}


		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户粉丝列表及最新一条微博信息
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursos 分页位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFollowers($id = null, $user_id = null, $name = null, $cursor = null, $count = null)
	 {
		if ($id) {
			$url = WEIBO_API_URL.'statuses/followers/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'statuses/followers.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		if ($count) {
			$params['count'] = $count;
		}


		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }

	/**
	 * 获取当前用户感兴趣的用户列表
	 *
	 * @param string $cursor 分页位置
	 * @param int $count 获取条数
	 * @return array
	 */
	function getUserSuggestions($cursor = null, $count = null, $with_reason = 1)
	{
		$url = WEIBO_API_URL.'users/suggestions.'.$this->format;

		$params = array();
		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($with_reason) {
			$params['with_reason'] = $with_reason;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 添加好友备注
	 *
	 * @param int|string $id 用户id
	 * @param string $remark 备注
	 * @return array
	 */
	 function updateFriendRemark($id, $remark)
	 {
		$url = WEIBO_API_URL.'user/friends/update_remark.'.$this->format;

		$params = array();
		if ($id) {
			$params['id'] = $id;
		}
		if ($remark) {
			$params['remark'] = $remark;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 *  返回系统推荐的用户列表
	 *
	 * @param string $category 分类，返回某一类别的推荐用户，默认为default。如果不在以下分类中，返回空列表
     * default：人气关注
     * ent：影视名星
     * hk_famous：港台名人
     * model：模特
     * cooking：美食&健康
     * sport：体育名人
     * finance：商界名人
     * tech：IT互联网
     * singer：歌手
     * writer：作家
     * moderator：主持人
     * medium：媒体总编
     * stockplayer：炒股高手
	 *
	 * @return array
	 */
	 function getUsersHot($category = 'default')
	 {
		$url = WEIBO_API_URL.'users/hot.'.$this->format;

		$params = array();
		$params['category'] = $category;

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }

	 //私信接口

	/**
	 * 获取当前用户最新私信列表
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	 function getDirectMessages($count = null, $page = null, $since_id = null, $max_id = null)
	 {
		$url = WEIBO_API_URL.'direct_messages.'.$this->format;

		$params = array();
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户发送的最新私信列表
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @return array
	 */
	 function getSentDirectMessages($count = null, $page = null, $since_id = null, $max_id = null)
	 {
		$url = WEIBO_API_URL.'direct_messages/sent.'.$this->format;

		$params = array();
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 发送一条私信
	 *
	 * @param int|string $d 用户id
	 * @param string $text 私信内容
	 * @param string $name 用户昵称
	 * @param int $user_id 用户user id
	 * @return array
	 */
	 function sendDirectMessage($id, $text, $name = null, $user_id = null)
	 {
		$url = WEIBO_API_URL.'direct_messages/new.'.$this->format;

		$params = array();
		$params['id'] = $id;
		$params['text'] = $text;
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($user_id) {
			$params['user_id'] = $user_id;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 删除一条私信
	 *
	 * @param int|string 私信id
	 * @return array
	 */
	 function deleteDirectMessage($id)
	 {
		$url = WEIBO_API_URL.'direct_messages/destroy/'.$id.'.'.$this->format;

		$params = array();

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }



	 //关注接口

	/**
	 * 关注某用户
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $follow
	 * @return array
	 */
	 function createFriendship($id = null, $user_id = null, $name = null, $follow = null)
	 {
		if ($id) {
			$url = WEIBO_API_URL.'friendships/create/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'friendships/create.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($follow) {
			$params['follow'] = $follow;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }

	 /**
	  * 批量添加关注
	  *
	  * @param string $ids 用户id, 多个用逗号隔开(最多20个)
	  * @return array
	  */
	 function createFriendshipBatch($ids)
	 {
		$url = WEIBO_API_URL.'friendships/create_batch.'.$this->format;

		$params = array();
		$params['ids'] = join(',', $ids);
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 取消关注或移除粉丝
	 *
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param int $is_follower 默认为0。1表示为移除粉丝，0表示为取消关注
	 * @return array
	 */
	 function deleteFriendship($user_id = null, $name = null, $is_follower = 0)
	 {
		$url = WEIBO_API_URL.'friendships/destroy.'.$this->format;

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($is_follower) {
			$params['is_follower'] = $is_follower;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 判断两个用户是否有关注关系
	 *
	 * @param int|string $user_a 要判断的用户UID
	 * @param int|string $user_b 要判断的被关注人用户UID
	 * @return array
	 */
	 function existsFriendship($user_a, $user_b)
	 {
		$url = WEIBO_API_URL.'friendships/exists.'.$this->format;

		$params = array();
		$params['user_a'] = $user_a;
		$params['user_b'] = $user_b;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 获取两个用户关系的详细情况
	 *
	 * @param int|string $target_id 要判断的目的用户UID
	 * @param string $target_screen_name 要判断的目的微博昵称
	 * @param int $source_id 源用户UID
	 * @param string $source_screen_name 源微博昵称
	 * @return array
	 */
	 function getFriendship($target_id = null, $target_screen_name = null, $source_id = null, $source_screen_name = null)
	 {
		$url = WEIBO_API_URL.'friendships/show.'.$this->format;

		$params = array();
		if ($target_id) {
			$params['target_id'] = $target_id;
		}
		if ($target_screen_name) {
			$params['target_screen_name'] = $target_screen_name;
		}
		if ($source_id) {
			$params['source_id'] = $source_id;
		}
		if ($source_screen_name) {
			$params['source_screen_name'] = $source_screen_name;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }



	 //Social Graph接口

	/**
	 * 获取用户关注对象uid列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页的位置
	 * @param int 获取条数
	 * @return array
	 */
	 function getFriendIds($id = null, $user_id = null, $name = null, $cursor = null, $count = null)
	 {
		if ($id) {
			$url = WEIBO_API_URL.'friends/ids/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'friends/ids.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		if ($count) {
			$params['count'] = $count;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取用户粉丝对象uid列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页的位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFollowerIds($id = null, $user_id = null, $name = null, $cursor = null, $count = null)
	 {
		if ($id) {
			$url = WEIBO_API_URL.'followers/ids/'.$id.'.'.$this->format;
		} else {
			$url = WEIBO_API_URL.'followers/ids.'.$this->format;
		}

		$params = array();
		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($name) {
			$params['screen_name'] = $name;
		}
		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		if ($count) {
			$params['count'] = $count;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }

   /**
	* 获取用户优质粉丝列表，每次最多返回20条，包括用户的最新的微博
	*
	* @param int|string $user_id 用户user id
	* @param int $count 获取条数
	* @param bool $oauth 是否要用身份认证 默认为true需要, false为不需要
	* @return array
	*/
	function getMagicFollowers($user_id, $count = null, $oauth = true)
	{
		$url = WEIBO_API_URL.'statuses/magic_followers.'.$this->format;

		$params = array();

		$params['user_id'] = $user_id;
		if ($count) {
			$params['count'] = $count;
		}

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}

		return $response;
	}


	 //帐号接口

	/**
	 * 验证当前用户身份是否合法
	 *
	 * @return array
	 */
	 function verifyCredentials()
	 {
		$url = WEIBO_API_URL.'account/verify_credentials.'.$this->format;

		$params = array();
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 获取当前用户API访问频率限制
	 *
	 * @return array
	 */
	 function getRateLimitStatus()
	 {
		$url = WEIBO_API_URL.'account/rate_limit_status.'.$this->format;

		$params = array();
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 当前用户退出登录
	 *
	 * @return array
	 */
	 function endSession()
	 {
		$url = WEIBO_API_URL.'account/end_session.'.$this->format;

		$params = array();
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 更改头像
	 *
	 * @param string $image 头像路径
	 * @return array
	 */
	 function updateProfileImage($image)
	 {
		$url = WEIBO_API_URL.'account/update_profile_image.'.$this->format;

		$params = array();
		$params['image'] = '@'.$image;

		$response = $this->oAuthRequest($url, 'post', $params, true);

		return $response;
	 }


	/**
	 * 更改资料
	 *
	 * @param array $params array('name' => string,
	 *								'gender' => string,
	 *								'province' => int|string,
	 *								'city' => int|string,
	 *								'description' => string)
	 * @return array
	 */
	 function updateProfile($params)
	 {
		$url = WEIBO_API_URL.'account/update_profile.'.$this->format;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 注册新浪微博帐号
	 *
	 * @param array $params array('nick' => string,
	 *								'gender' => string,
	 *								'password' => string,
	 *								'email' => string,
	 *								'province' => int|string,
	 *								'city' => int|string,
	 *								'ip' => string)
	 * @return array|string
	 */
	 function register($params)
	 {
		$params['source'] = WB_AKEY;
		$url = WEIBO_API_URL.'account/register.'.$this->format;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }

	/**
	 * 二次注册新浪微博帐号
	 *
	 * @param array $params array('uid' => string,
	 *								'nickname' => string,
	 *								'gender' => string,
	 *								'email' => string,
	 *								'province' => int|string,
	 *								'city' => int|string,
	 *								'ip' => string)
	 * @return array|string
	 */
	 function activate($params)
	 {
		$params['source'] = WB_AKEY;
		$url = WEIBO_API_URL.'account/activate.'.$this->format;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }

	/**
	 * 设置隐私信息
	 *
	 * @param array $params array('comment' => int|string 谁可以评论当前用户的微博 0所有 1我关注的人 默认0,
	 *								'message' => int|string 谁可以给当前用户发私信 0所有 1我关注的人 默认0,
	 *								'realname' => int|string 是否允许别人通过真实名称搜索到我 0允许 1不允许 默认1,
	 *								'geo' => int|string 发布微博 是否允许微博保存并显示所处的地理位置 0允许 1不允许
	 *								默认0,
	 *								'badge' => int|string 勋章展现状态，值—1私密状态，0公开状态，默认值0)
	 * @return array|string
	 */
	 function updatePrivacy($params)
	 {
		$url = WEIBO_API_URL.'account/update_privacy.'.$this->format;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }

	/**
	 * 获取隐私信息
	 *
	 * @return array|string
	 */
	 function getPrivacy()
	 {
		$url = WEIBO_API_URL.'account/get_privacy.'.$this->format;

		$params = array();
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }

   /**
	* 更新用户提醒设置
	*
	* @param array $params array('comment' => int|string 新评论提醒，0—不提醒，1—提醒，默认值1,
	*							'follower' => int|string 新粉丝提醒，0—不提醒，1—提醒，默认值1,
	*							'dm' => int|string 新私信提醒，0—不提醒，1—提醒，默认值1,
	*							'mention' => int|string @提到我提醒，0—不提醒，1—提醒，默认值1,
	*							'from_user' => int|string 设置哪些提到我的微博计入提醒数，微博作者身份，0--所有人，1—关注的人,
	*							'status_type' => int|string 设置哪些提到我的微博计入提醒数，微博类型，0—所有微博，1—原创的微博)
	* @return array|string
	*/
	function updateNotice($params)
	{
	   $url = WEIBO_API_URL.'account/update_notice.'.$this->format;

	   $response = $this->oAuthRequest($url, 'post', $params);

	   return $response;
	}


   /**
	* 获取用户提醒设置
	*
	* @return array|string
	*/
	function getNotice()
	{
	   $url = WEIBO_API_URL.'account/get_notice.'.$this->format;

	   $params = array();
	   $response = $this->oAuthRequest($url, 'get', $params);

	   return $response;
	}


	 //收藏接口

	/**
	 * 获取当前用户的收藏列表
	 *
	 * @param int $page 页码数
	 * @return array
	 */
	 function getFavorites($page = null)
	 {
		$url = WEIBO_API_URL.'favorites.'.$this->format;

		$params = array();
		if ($page) {
			$params['page'] = $page;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	 }


	/**
	 * 添加收藏
	 *
	 * @param int|string 微博id
	 * @return array
	 */
	 function createFavorite($id)
	 {
		$url = WEIBO_API_URL.'favorites/create.'.$this->format;

		$params = array();
		$params['id'] = $id;
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	/**
	 * 删除当前用户收藏的微博信息
	 *
	 * @param int $id 微博id
	 * @return array
	 */
	 function deleteFavorite($id)
	 {
		$url = WEIBO_API_URL.'favorites/destroy/'.$id.'.'.$this->format;

		$params = array();
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	 }


	 //oauth

    /**
     * 获取request token
     *
	 * @param string $oauth_callback
     * @return array a key/value array containing oauth_token and oauth_token_secret
     */
    function getRequestToken($oauth_callback = NULL)
	{
        $parameters = array();
        if (!empty($oauth_callback)) {
            $parameters['oauth_callback'] = $oauth_callback;
        }

		$this->token = null;
        $request = $this->oAuthRequest(WEIBO_API_URL.'oauth/request_token', 'GET', $parameters, false, true);
        $token = OAuthUtil::parse_parameters($request);
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        return RST($token);
    }

    /**
     * Get the authorize URL
     *
	 * @param array|string $token
	 * @param bool $sign_in_with_Weibo
	 * @param string $url
     * @return string
     */
    function getAuthorizeURL($token, $sign_in_with_Weibo = TRUE , $url)
	{
        if (is_array($token)) {
            $token = $token['oauth_token'];
        }
        if (empty($sign_in_with_Weibo)) {
            return WEIBO_API_URL.'oauth/authorize' . "?oauth_token={$token}&oauth_callback=" . urlencode($url);
        } else {
            return WEIBO_API_URL.'oauth/authenticate' . "?oauth_token={$token}&oauth_callback=". urlencode($url);
        }
    }

	/**
	 * Get the authorize Token
	 *
	 * @param string $token
	 * @param string $user
	 * @param string $password
	 *
	 * @return array
	 */
	function getAuthorizeToken($token, $user, $password)
	{
        if (is_array($token)) {
            $token = $token['oauth_token'];
        }

		$url = WEIBO_API_URL.'oauth/authorize';
		$params = array();
		$params['oauth_token'] = $token;
		$params['oauth_callback'] = $this->format;
		$params['display'] = 'web';
		$params['userId'] = $user;
		$params['passwd'] = $password;

		$this->http->setUrl($url);
		$this->http->setData($params);
		$response = $this->http->request();

		$code = $this->http->getState();
		if ($code != 200) {
			if ($code == 0) {
				$response = array("error_code" => "40002", "error" => "Access Timeout or Access denied");
				return RST($response);
			}
			return $this->throwException($response);
		}

		$response = json_decode($response);
		return RST($response);
	}

    /**
     * Exchange the request token and secret for an access token and
     * secret, to sign API calls.
     *
	 * @param string $oauth_verifier
	 * @param array $oauth_token
     * @return array array("oauth_token" => the access token,
     *                "oauth_token_secret" => the access secret)
     */
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false)
	{
        $parameters = array();
        if (!empty($oauth_verifier)) {
            $parameters['oauth_verifier'] = $oauth_verifier;
        }

		$auth_token = $oauth_token ? $oauth_token : USER::getOAuthKey(false);
		$this->token = new OAuthConsumer($auth_token['oauth_token'], $auth_token['oauth_token_secret']);
        $request = $this->oAuthRequest(WEIBO_API_URL.'oauth/access_token', 'GET', $parameters, false, true);
        $token = OAuthUtil::parse_parameters($request);
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        return RST($token);
    }

    /**
     * Format and sign an OAuth / API request
	 *
	 * @param string $url
	 * @param string $method
	 * @param array $parameters
	 * @param bool $multi
     * @return array
     */
    function oAuthRequest($url, $method, $parameters, $multi = false, $userType = false)
	{
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
        $request->sign_request($this->sha1_method, $this->consumer, $this->token);
		$method = strtoupper($method);

		$this->http->base_string = $request->base_string;
		$this->http->key_string = $request->key_string;
        switch ($method) {
        case 'GET':
			//echo $request->to_url();
			$this->http->setUrl($request->to_url());
			$result = $this->http->request();
			break;

		case 'DELETE':
			$this->http->setUrl($request->get_normalized_http_url());
			$this->http->setData($request->to_postdata($multi));
			$result = $this->http->request('delete');
			break;

        default:
			$this->http->setUrl($request->get_normalized_http_url());
			$this->http->setData($request->to_postdata($multi));
			if ($multi) {
				$header_array = array();
				$header_array2=array();
				$header_array2 = array("Content-Type: multipart/form-data; boundary=" . $GLOBALS['___xwbStData']['boundary'] , "Expect: ");
				foreach($header_array as $k => $v) {
					array_push($header_array2,$k.': '.$v);
				}
				$config = array('http_header' => $header_array2);
				$this->http->setConfig($config);
			}

			$result = $this->http->request('post');
			break;
        }

		$code = $this->http->getState();
		$http_url = $this->http->getUrl();
		if (200 != $code) {
			//log
			APP::LOG('url: '.$http_url." \r\ncode: ".$code." \r\nret: ".$result . "\r\nerror: " . $this->http->getError()."\r\nbase_string:: ".$request->base_string."\r\nkey_string: ".$request->key_string);
			if (0 == $code) {
				return RST('', '1040002', 'Access Timeout or Access denied', 0);
			}
			return $this->throwException($result);
		}
		if ($userType === true) {
			return $result;
		}
		$result = json_decode($result, true);
		//$result = json_decode(preg_replace('#(?<=[,\{\[])\s*("\w+"):(\d{6,})(?=\s*[,\]\}])#si', '${1}:"${2}"', $result), true);
		return RST($result);
    }

	/**
	 * 使用source方式访问api
	 *
	 * @param string $url
	 * @param string $method
	 * @param array $parameters
	 * @return array
	 */
	function sourceRequest($url, $method, $parameters)
	{
		$parameters['source'] = WB_AKEY;
		$method = strtoupper($method);
        switch ($method) {
		case 'GET':
			if (strpos($url, '?') === false) {
				$url = $url.'?'.http_build_query($parameters);
			} else {
				$url = $url.'&'.http_build_query($parameters);
			}
			$this->http->setUrl($url);
			$result = $this->http->request();
			$code = $this->http->getState();
			if (200 != $code) {
				//log
				APP::LOG('url: '.$this->http->getUrl()." \r\ncode: ".$code." \r\nret: ".$result. "\r\nerror: " . $this->http->getError());
				if (0 == $code) {
					return RST('', '1040002', 'Access Timeout or Access denied', 0);
				}
				return $this->throwException($result);
			}

			$result = json_decode($result, true);
			//$result = json_decode(preg_replace('#(?<=[,\{\[])\s*("\w+"):(\d{6,})(?=\s*[,\]\}])#si', '${1}:"${2}"', $result), true);
			return RST($result);
        }
    }


// search user

	/**
	 * 搜索微博用户
	 *
	 * @param array $params array('base_app' => string 是否只搜索该应用数据 true false 默认为false
	 *								'q' => string 关键字,
	 *								'snick' => int|string 是否包含昵称 0不包含 1包含,
	 *								'sdomain' => int|sting 是否包含个性域名 0不包含 1包含,
	 *								'sintro' => int|string 是否包含简介 同上,
	 *								'province' => int|string 省份id,
	 *								'city' => int|string 城市id,
	 *								'gender' => string 性别 m为男 f为女,
	 *								'comorsch' => string 公司学校名称,
	 *								'sort' => int|sting 排序方式 1按更新时间 2按粉丝数,
	 *								'page' => int 页码数,
	 *								'count' => int 获取条数,
	 *								'callback' => string)
	 * @param bool $oauth
	 * @return array
	 */
	function searchUser($params, $oauth = true)
	{
		$url = WEIBO_API_URL.'users/search.'.$this->format;

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}
		return $response;
	}


	/**
	 * 搜索微博文章
	 *
	 * @param array $param array('base_app' => string,
	 *								'q' => string 关键字,
	 *								'page' => int 页码数,
	 *								'rpp' => int 获取条数,
	 *								'callback' => string,
	 *								'geocode' => string)
	 * @param bool $oauth
	 * @return array
	 */
	function search($params, $oauth = true)
	{
		$url = WEIBO_API_URL.'search.'.$this->format;

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}
		return $response;
	}


	/**
	 * 搜索微博文章
	 *
	 * @param array $params array('q' => string 关键字,
	 *								'filter_ori' => int|string 过滤器 是否原创 0为全部 5为原创 4为转发 默认为0,
	 *								'filter_pic' => int|string 过滤器 是否包含图片 0为全部 1为包含图 2为不含图,
	 *								'province' => int|string 省份id,
	 *								'city' => int|string 城市id,
	 *								'starttime' => string 开始时间(时间戳),
	 *								'endtime' => string 截止时间(时间戳),
	 *								'page' => int 页码数,
	 *								'count' => int 获取条数,
	 *								'callback' => string,
	 *								'needcount' => string 是否需要搜索总数, 'true' 是需要)
	 * @param bool $oauth
	 * @return array
	 */
	function searchStatuse($params, $oauth = true)
	{
		$url = WEIBO_API_URL.'statuses/search.'.$this->format;

		if ($oauth === false) {
			$response = $this->sourceRequest($url, 'get', $params);
		} else {
			$response = $this->oAuthRequest($url, 'get', $params);
		}
		return $response;
	}


	/**
	 * 获取省份及城市编码ID与文字对应
	 *
	 * @return array
	 */
	function getProvinces()
	{
		$url = WEIBO_API_URL.'provinces.'.$this->format;
		$params = array();

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 设置某个用户某个新消息的未读数为0
	 *
	 * @param string $type 1--评论数，2--@数，3--私信数，4--关注我的数
	 * @return array
	 */
	function resetCount($type = 1)
	{
		$url = WEIBO_API_URL.'statuses/reset_count.'.$this->format;
		$params = array();

		$params['type'] = $type;
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * weibo表情
	 *
	 * @return array
	 */
	function emotions()
	{
		$url = WEIBO_API_URL.'emotions.'.$this->format;
		$params = array();

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}


	//热门话题

	/**
	 * 获取某人的话题
	 *
	 * @param string $user_id 用户id
 	 * @param int $page 页码数
	 * @param int $count 获取条数
	 * @return array
	 */
	function getTrendsByUser($user_id, $page = null, $count = null)
	{
		$url = WEIBO_API_URL.'trends.'.$this->format;
		$params = array();

		$params['user_id'] = $user_id;
		if ($page) {
			$params['page'] = $page;
		}
		if ($count) {
			$params['count'] = $count;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;

	}

	/**
	 * 获取某一话题的微博(目前最多返回20条)
	 *
	 * @param string $trend_name 话题
	 * @return array
	 */
	function getTrendStatuses($trend_name)
	{
		$url = WEIBO_API_URL.'trends/statuses.'.$this->format;
		$params = array();

		$params['trend_name'] = $trend_name;
		if ($page) {
			$params['page'] = $page;
		}
		if ($count) {
			$params['count'] = $count;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 关注某话题
	 *
	 * @param string $trend_name 话题
	 * @return array
	 */
	function createTrendsFollow($trend_name)
	{
		$url = WEIBO_API_URL.'trends/follow.'.$this->format;
		$params = array();

		$params['trend_name'] = $trend_name;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 获取按小时返回的热门话题
	 *
	 * @param int|string $hour 返回几个小时前的话题
	 * @param bool $base_app 是否基于当前应用来获取数据
	 * @param string $exclude 从话题列表中排除的话题
	 * @return array
	 */
	function getTrendsHourly($hour = false, $base_app = '0', $exclude = null)
	{
		$url = WEIBO_API_URL.'trends/hourly.'.$this->format;
		$params = array();

		if ($hour) {
			$params['hour'] = $hour;
		}
		$params['base_app'] = $base_app;

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

   /**
	* 获取按日期返回的热门话题
	*
	* @param int|string $date 返回此日期前的话题
	* @param bool $base_app 是否基于当前应用来获取数据
	* @param string $exclude 从话题列表中排除的话题
	* @return array
	*/
	function getTrendsDaily($date = false, $base_app = '0', $exclude = null)
	{
		$url = WEIBO_API_URL.'trends/daily.'.$this->format;
		$params = array();

		if ($date) {
			$params['date'] = $date;
		}
	   $params['base_app'] = $base_app;

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

   /**
	* 获取按周的热门话题
	*
	* @param int|string $date 返回此日期前的话题
	* @param bool $base_app 是否基于当前应用来获取数据
	* @param string $exclude 从话题列表中排除的话题
	* @return array
	*/
	function getTrendsWeekly($date = false, $base_app = '0', $exclude = null)
	{
		$url = WEIBO_API_URL.'trends/weekly.'.$this->format;
		$params = array();

		if ($date) {
			$params['date'] = $date;
		}
		$params['base_app'] = $base_app;

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

   /**
	* 获取按日的热门转发
	*
	* @param int $count 获取返回的条数
	* @return array
	*/
	function getHotRepostDaily($count = null, $base_app = '0')
	{
	   $url = WEIBO_API_URL.'statuses/hot/repost_daily.'.$this->format;
	   $params = array();

	   if ($count) {
		   $params['count'] = $count;
	   }
	   $params['base_app'] = $base_app;
	   $response = $this->oAuthRequest($url, 'get', $params);

	   return $response;
	}

   /**
	* 获取按周的热门转发
	*
	* @param int $count 返回条数
	* @return array
	*/
	function getHotRepostWeekly($count = null)
	{
	   $url = WEIBO_API_URL.'statuses/hot/repost_weekly.'.$this->format;
	   $params = array();

	   if ($count) {
		   $params['count'] = $count;
	   }
	   $response = $this->oAuthRequest($url, 'get', $params);

	   return $response;
	}

   /**
	* 获取按日的热门评论
	*
	* @param int $count 返回条数
	* @return array
	*/
	function getHotCommentsDaily($count = null, $base_app = '0')
	{
	   $url = WEIBO_API_URL.'statuses/hot/comments_daily.'.$this->format;
	   $params = array();

	   if ($count) {
		   $params['count'] = $count;
	   }
	   $params['base_app'] = $base_app;
	   $response = $this->oAuthRequest($url, 'get', $params);

	   return $response;
	}

   /**
	* 获取按周的热门评论
	*
	* @param int $count 返回条数
	* @return array
	*/
	function getHotCommentsWeekly($count = null)
	{
	   $url = WEIBO_API_URL.'statuses/hot/comments_weekly.'.$this->format;
	   $params = array();

	   if ($count) {
		   $params['count'] = $count;
	   }
	   $response = $this->oAuthRequest($url, 'get', $params);

	   return $response;
	}

	//标签

	/**
	 * 获取标签列表
	 *
	 * @param int|string $user_id 用户user id
	 * @param int $count 获取条数 默认20
	 * @param int $page 页码数
	 * @return array
	 */
	function getTagsList($user_id, $count = null, $page = null)
	{
		$url = WEIBO_API_URL.'tags.'.$this->format;
		$params = array();

		$params['user_id'] = $user_id;
		if ($count) {
			$params['count'] = $count;
		}
		if ($page) {
			$params['page'] = $page;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 创建标签
	 *
	 * @param string $tags 标签 多个用逗号隔开
	 * @return array
	 */
	function createTags($tags)
	{
		$url = WEIBO_API_URL.'tags/create.'.$this->format;
		$params = array();

		$params['tags'] = $tags;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 获取用户感兴趣的标签
	 *
	 * @param int $page 获取页码数
	 * @param int $count 获取条数 默认为10
	 * @return array
	 */
	function getTagsSuggestions($page = null, $count = null)
	{
		$url = WEIBO_API_URL.'tags/suggestions.'.$this->format;
		$params = array();

		if ($page) {
			$params['page'] = $page;
		}
		if ($count) {
			$params['count'] = $count;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 删除标签
	 *
	 * @param int|string $tag_id 标签id
	 * @return array
	 */
	function deleteTags($tag_id)
	{
		$url = WEIBO_API_URL.'tags/destroy.'.$this->format;
		$params = array();

		$params['tag_id'] = $tag_id;

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}


	//lists 订阅

	/**
	 * 创建新的订阅分类
	 *
	 * @param int|string $id 用户id
	 * @param string $name 分类名称
	 * @param string $mode 其值（values）可以是public或private，默认为public
     * @param	$description
	 * @return array
	 */
	function createUserLists($id, $name, $mode = null, $description = null)
	{
		$url = WEIBO_API_URL.$id.'/lists.'.$this->format;
		$params = array();

		$params['name'] = $name;
		if ($mode) {
			$params['mode'] = $mode;
		}
		if ($description) {
			$params['description'] = $description;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 列出用户的所有订阅分类 每页返回20个list
	 *
	 * @param int|string $id 用户id
	 * @param int|string $cursor 分页位置 默认从-1开始
	 * @return array
	 */
	function getUserLists($id, $cursor = null)
	{
		$url = WEIBO_API_URL.$id.'/lists.'.$this->format;
		$params = array();

		if ($cursor) {
			$params['cursor'] = $cursor;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 更新指定的订阅分类
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id 订阅分类id
	 * @param string $name 用户昵称
	 * @param string $mode 其值（values）可以是public或private，默认为public
     * @param	$description
	 * @return array
	 */
	function updateUserLists($id, $list_id, $name, $mode = null, $description = null)
	{
		$url = WEIBO_API_URL.$id.'/lists/'.$list_id.'.'.$this->format;
		$params = array();

		$params['name'] = $name;
		if ($mode) {
			$params['mode'] = $mode;
		}
		if ($description) {
			$params['description'] = $description;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 展示特定订阅分类的信息
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id 订阅分类id
	 * @return array
	 */
	function getUserListId($id, $list_id)
	{
		$url = WEIBO_API_URL.$id.'/lists/'.$list_id.'.'.$this->format;
		$params = array();

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 删除指定的订阅分类
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id 订阅分类id
	 * @return array
	 */
	function deleteUserListId($id, $list_id)
	{
		$url = WEIBO_API_URL.$id.'/lists/'.$list_id.'.'.$this->format;
		$params = array();

		$response = $this->oAuthRequest($url, 'delete', $params);

		return $response;
	}

	/**
	 * 展示list成员的最新微博信息
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id list id
	 * @param int $per_page 获取条数
	 * @param int $page 获取页码数
	 * @param int $since_id 返回带有比指定list id大的id
	 * @param int $max_id 返回带一个小于或等于指定list id的id结果
	 * @return array
	 */
	function getUserListIdStatuses($id, $list_id, $per_page = null, $page = null, $since_id = null, $max_id = null)
	{
		$url = WEIBO_API_URL.$id.'/lists/'.$list_id.'/statuses.'.$this->format;
		$params = array();

		if ($per_page) {
			$params['per_page'] = $per_page;
		}
		if ($page) {
			$params['page'] = $page;
		}
		if ($since_id) {
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$params['max_id'] = $max_id;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 列出用户作为成员的所有list列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $cursor 分页位置 默认从-1开始 每页包含20个list
	 * @return array
	 */
	function getUserListsMemberships($id, $cursor = null)
	{
		$url = WEIBO_API_URL.$id.'/lists/memberships.'.$this->format;
		$params = array();

		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 获取用户的lists、subscriptions、listed数量
	 *
	 * @param int|string $id 用户id
	 * @return array
	 */
	function getUserListsCounts($id)
	{
		$url = WEIBO_API_URL.$id.'/lists/counts.'.$this->format;
		$params = array();

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 列出用户订阅的所有list列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $cursor 分页位置 默认从-1开始 每页包含20个list
	 * @return array
	 */
	function getUserListsSubscriptions($id, $cursor = null)
	{
		$url = WEIBO_API_URL.$id.'/lists/subscriptions.'.$this->format;
		$params = array();

		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 返回list中所有的成员
	 *
	 * @param int|string $id 用户id
	 * @param int|string $cursor 分页位置 默认从-1开始 每页包含20个list
	 * @return array
	 */
	function getUserListsMember($id, $list_id, $cursor = null)
	{
		$url = WEIBO_API_URL.$id.'/'.$list_id.'/members.'.$this->format;
		$params = array();

		if ($cursor) {
			$params['cursor'] = $cursor;
		}
		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 将用户添加到list中。用户只能将其他用户添加到自己创建的list中。每个list最多拥有500个用户。
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id 订阅list ID
	 * @param int $user_id 要添加的用户id
	 * @return array
	 */
	function createUserListsMember($id, $list_id, $user_id)
	{
		$url = WEIBO_API_URL.$id.'/'.$list_id.'/members.'.$this->format;
		$params = array();

		$params['id'] = $user_id;
		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 将用户从list中删除
	 *
	 * @param int|string $id 用户id
	 * @param int|string $list_id 订阅list ID
	 * @param int $user_id 要删除的用户id
	 * @return array
	 */
	function deleteUserListsMember($id, $list_id, $user_id)
	{
		$url = WEIBO_API_URL.$id.'/'.$list_id.'/members.'.$this->format;
		$params = array();

		$params['id'] = $user_id;
		$response = $this->oAuthRequest($url, 'delete', $params);

		return $response;
	}


	//黑名单

	/**
	 * 将用户加入黑名单
	 *
	 * @param int|string $user_id 用户id
	 * @param string $screen_name 用户昵称
	 * @return array
	 */
	function createBlocks($user_id = null, $screen_name = null)
	{
		$url = WEIBO_API_URL.'blocks/create.'.$this->format;
		$params = array();

		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($screen_name) {
			$params['screen_name'] = $screen_name;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 删除黑名单
	 *
	 * @param int|string $user_id 用户id
	 * @param string $screen_name 用户昵称
	 * @return array
	 */
	function deleteBlocks($user_id = null, $screen_name = null)
	{
		$url = WEIBO_API_URL.'blocks/destroy.'.$this->format;
		$params = array();

		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($screen_name) {
			$params['screen_name'] = $screen_name;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}

	/**
	 * 检测是否是黑名单用户
	 *
	 * @param int|string $user_id 用户id
	 * @param string $screen_name 用户昵称
	 * @return array
	 */
	function existsBlocks($user_id = null, $screen_name = null)
	{
		$url = WEIBO_API_URL.'blocks/exists.'.$this->format;
		$params = array();

		if ($user_id) {
			$params['user_id'] = $user_id;
		}
		if ($screen_name) {
			$params['screen_name'] = $screen_name;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 分页黑名单用户(输出用户详细信息)
	 *
	 * @param int $page 页码数
	 * @param int $count 获取条数
	 * @return array
	 */
	function getBlocks($page = null, $count = null, $with_addtime=1)
	{
		$url = WEIBO_API_URL.'blocks/blocking.'.$this->format;
		$params = array();

		if ($page) {
			$params['page'] = $page;
		}
		if ($count) {
			$params['count'] = $count;
		}
		if ($with_addtime) {
			$params['with_addtime'] = $with_addtime;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 分页黑名单用户（只输出id）
	 *
	 * @param int $page 页码数
	 * @param int $count 获取条数
	 * @return array
	 */
	function getBlocksIds($page = null, $count = null)
	{
		$url = WEIBO_API_URL.'blocks/blocking/ids.'.$this->format;
		$params = array();

		if ($page) {
			$params['page'] = $page;
		}
		if ($count) {
			$params['count'] = $count;
		}

		$response = $this->oAuthRequest($url, 'get', $params);

		return $response;
	}

	/**
	 * 举报某条信息
	 *
	 * @param string $content 举报的内容
	 * @param string $url 举报的url
	 * @param int|string $status_id 举报的微博id 为可选参数。当status_id不为空时，允许url参数为空
	 * @return array
	 */
	function report_spam($content, $url = null, $status_id = null)
	{
		$url = WEIBO_API_URL.'report_spam.'.$this->format;
		$params = array();

		$params['ip'] = F('get_client_ip');
		$params['content'] = $content;
		if ($url) {
			$params['url'] = $url;
		}
		if ($status_id) {
			$params['status_id'] = $status_id;
		}

		$response = $this->oAuthRequest($url, 'post', $params);

		return $response;
	}
}


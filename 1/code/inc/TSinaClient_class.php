<?php
include_once('oAuth_class.php');

class TSinaClient extends oAuthClient {
    /** 
     * ���캯�� 
     *  
     * @access public 
     * @param mixed $akey ΢������ƽ̨Ӧ��APP KEY 
     * @param mixed $skey ΢������ƽ̨Ӧ��APP SECRET 
     * @param mixed $accecss_token OAuth��֤���ص�token 
     * @param mixed $accecss_token_secret OAuth��֤���ص�token secret 
     * @return void 
     */ 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    {        
        $this->host='http://api.t.sina.com.cn/';
		$this->oauth = new TSinaoAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } 
}



/** 
 * ����΢�� OAuth ��֤�� 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class TSinaoAuth extends oAuth { 
    /** 
     * construct TSina object 
     */ 
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
		
		$this->host="http://api.t.sina.com.cn/";
		
		//parent::__construct($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    } 

} 

/** 
 * ����΢�������� 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
abstract class oAuthClient { 
	public $host='';
	public $oauth=null;
	
    /** 
     * ���캯�� 
     *  
     * @access public 
     * @param mixed $akey ΢������ƽ̨Ӧ��APP KEY 
     * @param mixed $skey ΢������ƽ̨Ӧ��APP SECRET 
     * @param mixed $accecss_token OAuth��֤���ص�token 
     * @param mixed $accecss_token_secret OAuth��֤���ص�token secret 
     * @return void 
     */ 
    /*function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    { 
        $this->oauth = new TSina( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } */

    /** 
     * ���¹���΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function public_timeline() 
    { 
        return $this->oauth->get($this->host.'statuses/public_timeline.json'); 
    } 

    /** 
     * ���¹�ע��΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function friends_timeline() 
    { 
        return $this->home_timeline(); 
    } 

    /** 
     * ���¹�ע��΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function home_timeline() 
    { 
        return $this->oauth->get($this->host.'statuses/home_timeline.json'); 
    } 

    /** 
     * ���� @�û��� 
     *  
     * @access public 
     * @param int $page ���ؽ����ҳ��š� 
     * @param int $count ÿ�η��ص�����¼������ҳ���С����������200��Ĭ��Ϊ20�� 
     * @return array 
     */ 
    function mentions( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( $this->host.'statuses/mentions.json' , $page , $count ); 
    } 


    /** 
     * ����΢�� 
     *  
     * @access public 
     * @param mixed $text Ҫ���µ�΢����Ϣ�� 
     * @return array 
     */ 
    function update( $text ) 
    { 
        //  http://api.t.sina.com.cn/statuses/update.json 
        $param = array(); 
        $param['status'] = $text; 

        return $this->oauth->post( $this->host.'statuses/update.json' , $param ); 
    }
    
    /** 
     * ����ͼƬ΢�� 
     *  
     * @access public 
     * @param string $text Ҫ���µ�΢����Ϣ�� 
     * @param string $text Ҫ������ͼƬ·��,֧��url��[ֻ֧��png/jpg/gif���ָ�ʽ,���Ӹ�ʽ���޸�get_image_mime����] 
     * @return array 
     */ 
    function upload( $text , $pic_path ) 
    { 
        //  http://api.t.sina.com.cn/statuses/update.json 
        $param = array(); 
        $param['status'] = $text; 
        $param['pic'] = '@'.$pic_path;
        
        return $this->oauth->post( $this->host.'statuses/upload.json' , $param , true ); 
    } 

    /** 
     * ��ȡ����΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫ��ȡ�ѷ����΢��ID 
     * @return array 
     */ 
    function show_status( $sid ) 
    { 
        return $this->oauth->get( $this->host.'statuses/show/' . $sid . '.json' ); 
    } 

    /** 
     * ɾ��΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ����΢��ID 
     * @return array 
     */ 
    function delete( $sid ) 
    { 
        return $this->destroy( $sid ); 
    } 

    /** 
     * ɾ��΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ����΢��ID 
     * @return array 
     */ 
    function destroy( $sid ) 
    { 
        return $this->oauth->post( $this->host.'statuses/destroy/' . $sid . '.json' ); 
    } 

    /** 
     * �������� 
     *  
     * @access public 
     * @param mixed $uid_or_name �û�UID��΢���ǳơ� 
     * @return array 
     */ 
    function show_user( $uid_or_name = null ) 
    { 
        return $this->request_with_uid( $this->host.'users/show.json' ,  $uid_or_name ); 
    } 

    /** 
     * ��ע���б� 
     *  
     * @access public 
     * @param bool $cursor ��ҳֻ�ܰ���100����ע�б�Ϊ�˻�ȡ������cursorĬ�ϴ�-1��ʼ��ͨ�����ӻ����cursor����ȡ����Ĺ�ע�б� 
     * @param bool $count ÿ�η��ص�����¼������ҳ���С����������200,Ĭ�Ϸ���20 
     * @param mixed $uid_or_name Ҫ��ȡ�� UID��΢���ǳ� 
     * @return array 
     */ 
    function friends( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( $this->host.'statuses/friends.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * ��˿�б� 
     *  
     * @access public 
     * @param bool $cursor ��ҳֻ�ܰ���100����˿�б�Ϊ�˻�ȡ������cursorĬ�ϴ�-1��ʼ��ͨ�����ӻ����cursor����ȡ����ķ�˿�б� 
     * @param bool $count ÿ�η��ص�����¼������ҳ���С����������200,Ĭ�Ϸ���20�� 
     * @param mixed $uid_or_name  Ҫ��ȡ�� UID��΢���ǳ� 
     * @return array 
     */ 
    function followers( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( $this->host.'statuses/followers.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * ��עһ���û� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫ��ע���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function follow( $uid_or_name ) 
    { 
        return $this->request_with_uid( $this->host.'friendships/create.json' ,  $uid_or_name ,  false , false , false , true  ); 
    } 

    /** 
     * ȡ����עĳ�û� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫȡ����ע���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function unfollow( $uid_or_name ) 
    { 
        return $this->request_with_uid( $this->host.'friendships/destroy.json' ,  $uid_or_name ,  false , false , false , true); 
    } 

    /** 
     * ���������û���ϵ����ϸ��� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫ�жϵ��û�UID 
     * @return array 
     */ 
    function is_followed( $uid_or_name ) 
    { 
        $param = array(); 
        if( is_numeric( $uid_or_name ) ) $param['target_id'] = $uid_or_name; 
        else $param['target_screen_name'] = $uid_or_name; 

        return $this->oauth->get( $this->host.'friendships/show.json' , $param ); 
    } 

    /** 
     * �û�����΢���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @param mixed $uid_or_name ָ���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function user_timeline( $page = 1 , $count = 20 , $uid_or_name = null ) 
    { 
        if( !is_numeric( $page ) ) 
            return $this->request_with_uid( $this->host.'statuses/user_timeline.json' ,  $page ); 
        else 
            return $this->request_with_uid( $this->host.'statuses/user_timeline.json' ,  $uid_or_name , $page , $count ); 
    } 

    /** 
     * ��ȡ˽���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function list_dm( $page = 1 , $count = 20  ) 
    { 
        return $this->request_with_pager( $this->host.'direct_messages.json' , $page , $count ); 
    } 

    /** 
     * ���͵�˽���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function list_dm_sent( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( $this->host.'direct_messages/sent.json' , $page , $count ); 
    } 

    /** 
     * ����˽�� 
     *  
     * @access public 
     * @param mixed $uid_or_name UID��΢���ǳ� 
     * @param mixed $text Ҫ��������Ϣ���ݣ��ı���С����С��300�����֡� 
     * @return array 
     */ 
    function send_dm( $uid_or_name , $text ) 
    { 
        $param = array(); 
        $param['text'] = $text; 

        if( is_numeric( $uid_or_name ) ) $param['user_id'] = $uid_or_name; 
        else $param['screen_name'] = $uid_or_name; 

        return $this->oauth->post( $this->host.'direct_messages/new.json' , $param  ); 
    } 

    /** 
     * ɾ��һ��˽�� 
     *  
     * @access public 
     * @param mixed $did Ҫɾ����˽������ID 
     * @return array 
     */ 
    function delete_dm( $did ) 
    { 
        return $this->oauth->post( $this->host.'direct_messages/destroy/' . $did . '.json' ); 
    } 

    /** 
     * ת��һ��΢����Ϣ�� 
     *  
     * @access public 
     * @param mixed $sid ת����΢��ID 
     * @param bool $text ��ӵ�ת����Ϣ�� 
     * @return array 
     */ 
    function repost( $sid , $text = false ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        if( $text ) $param['status'] = $text; 

        return $this->oauth->post( $this->host.'statuses/repost.json' , $param  ); 
    } 

    /** 
     * ��һ��΢����Ϣ�������� 
     *  
     * @access public 
     * @param mixed $sid Ҫ���۵�΢��id 
     * @param mixed $text �������� 
     * @param bool $cid Ҫ���۵�����id 
     * @return array 
     */ 
    function send_comment( $sid , $text , $cid = false ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        $param['comment'] = $text; 
        if( $cid ) $param['cid '] = $cid; 

        return $this->oauth->post( $this->host.'statuses/comment.json' , $param  ); 

    } 

    /** 
     * ���������� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function comments_by_me( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( $this->host.'statuses/comments_by_me.json' , $page , $count ); 
    } 

    /** 
     * ��������(��ʱ��) 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function comments_timeline( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( $this->host.'statuses/comments_timeline.json' , $page , $count ); 
    } 

    /** 
     * ���������б�(��΢��) 
     *  
     * @access public 
     * @param mixed $sid ָ����΢��ID 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function get_comments_by_sid( $sid , $page = 1 , $count = 20 ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get($this->host.'statuses/comments.json' , $param ); 

    } 

    /** 
     * ����ͳ��΢������������ת������һ����������ȡ100���� 
     *  
     * @access public 
     * @param mixed $sids ΢��ID���б��ö��Ÿ��� 
     * @return array 
     */ 
    function get_count_info_by_ids( $sids ) 
    { 
        $param = array(); 
        $param['ids'] = $sids; 

        return $this->oauth->get( $this->host.'statuses/counts.json' , $param ); 
    } 

    /** 
     * ��һ��΢��������Ϣ���лظ��� 
     *  
     * @access public 
     * @param mixed $sid ΢��id 
     * @param mixed $text �������ݡ� 
     * @param mixed $cid ����id 
     * @return array 
     */ 
    function reply( $sid , $text , $cid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        $param['comment'] = $text; 
        $param['cid '] = $cid; 

        return $this->oauth->post( $this->host.'statuses/reply.json' , $param  ); 

    } 

    /** 
     * �����û��ķ��������20���ղ���Ϣ�����û��ղ�ҳ�淵��������һ�µġ� 
     *  
     * @access public 
     * @param bool $page ���ؽ����ҳ��š� 
     * @return array 
     */ 
    function get_favorites( $page = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 

        return $this->oauth->get( $this->host.'favorites.json' , $param ); 
    } 

    /** 
     * �ղ�һ��΢����Ϣ 
     *  
     * @access public 
     * @param mixed $sid �ղص�΢��id 
     * @return array 
     */ 
    function add_to_favorites( $sid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 

        return $this->oauth->post( $this->host.'favorites/create.json' , $param ); 
    } 

    /** 
     * ɾ��΢���ղء� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ�����ղ�΢����ϢID. 
     * @return array 
     */ 
    function remove_from_favorites( $sid ) 
    { 
        return $this->oauth->post( $this->host.'favorites/destroy/' . $sid . '.json'  ); 
    } 


    /** 
     * ��ǰ��¼���û�����Ϣ�� 
     *  
     * @access public 
     * @return array ��ǰ��¼���û�����Ϣ
     */ 
	function getUserInfo(){
		$url=$this->host.'account/verify_credentials.json';
   		return $this->oauth->get($url );
	}
	
	function rate_limit_status(){
		$url=$this->host.'account/rate_limit_status.json';
   		return $this->oauth->get($url );
	}		
	function end_session(){
		$url=$this->host.'account/end_session.json';
   		return $this->oauth->get($url );
	}
    // ========================================= 

    /** 
     * @ignore 
     */ 
    protected function request_with_pager( $url , $page = false , $count = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get($url , $param ); 
    } 

    /** 
     * @ignore 
     */ 
    protected function request_with_uid( $url , $uid_or_name , $page = false , $count = false , $cursor = false , $post = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 
        if( $cursor )$param['cursor'] =  $cursor; 

        if( $post ) $method = 'post'; 
        else $method = 'get'; 

        if( is_numeric( $uid_or_name ) ) 
        { 
            $param['user_id'] = $uid_or_name; 
            return $this->oauth->$method($url , $param ); 

        }elseif( $uid_or_name !== null ) 
        { 
            $param['screen_name'] = $uid_or_name; 
            return $this->oauth->$method($url , $param ); 
        } 
        else 
        { 
            return $this->oauth->$method($url , $param ); 
        } 

    } 

} 

?>
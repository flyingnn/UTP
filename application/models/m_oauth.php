<?php

class M_oauth extends CI_Model{

        protected $user_table = '';
        public $open_id = '';
        public $access_token = '';

	function __construct()
	{
                date_default_timezone_set('Asia/Shanghai');
		parent::__construct();
		require_once 'oauth2.0/curl.php';
		require_once 'oauth2.0/curl_response.php';
                $this->user_table = $this->db->dbprefix('user');
                if (!isset($_SESSION))
                        session_start();
	}

	/**
	 * 获得access_token
	 *
	 */
	function get_access_token($code){
                if($_REQUEST['state'] == $_SESSION['state']) 
                {
                        $this->config->load('oauth_qq');
                        $curl = new Curl();
                        $postdata = array ('grant_type' => 'authorization_code',
                                'code' => $code,
                                'client_id' => $this->config->item('api_key'),
                                'client_secret' => $this->config->item('api_key_secret'),
                                'redirect_uri' => $this->config->item('callback_url'),
                                'state' => '1'
                                 );


                        $curl->options['CURLOPT_SSL_VERIFYPEER'] = false;
                        $response = $curl->post($this->config->item('access_token_url'),$postdata);


                        preg_match('/^access_token=.*&/', $response, $matches);
                        $access_token = $matches[0];
                        $access_token = substr($access_token,13,-1);
                        //echo $access_token;
                        $this->access_token = $access_token;
                        return $access_token;
                }
                else
                {
                        return false;
                        //return "The state does not match. You may be a victim of CSRF.";
                }
	}

	/**
	 * 获得open_id
	 *
	 */
	function get_open_id($access_token){
		$this->config->load('oauth_qq');
		$curl = new Curl();

		$postdata = array ('access_token' => $access_token);

		$response = $curl->get('https://graph.qq.com/oauth2.0/me',$postdata);

		preg_match('/openid.*}/', $response, $matches);
		$open_id = $matches[0];
		$open_id = substr($open_id,9,-2);
                $this->open_id = $open_id;
		return $open_id;
	}
        

	/**
	 * 获得用户信息
	 *
	 */
	public function get_user_info($code){
		$this->config->load('oauth_qq');
		$curl = new Curl();
		$access_token = $this->get_access_token($code);
                if ($access_token)
                {
                        $open_id = $this->get_open_id($access_token);

                        $url = 'https://graph.qq.com/user/get_user_info';
                        $postdata = array ('access_token' => $access_token,
                                'oauth_consumer_key' => $this->config->item('api_key'),
                                'openid' => $open_id,
                        );

                        $response = $curl->get($url,$postdata);
                        return $response;
                }
                else    return false;
                //{"ret":100016,"msg":"access token check failed"}
	}
        
        /**
         * 判断用户是否存在
         *
         */
         
        function UserExist($OpenID){
                $data = array(
                              'open_id' => $OpenID
                           );
               $query = $this->db->get_where('user', $data);
                if($query->num_rows() > 0){
                    return true;
                }else {
                    return false;
                }
        }
        
        /**
         * 新加用户
         *
         */
        function add_new_user($nick,$avatar_url,$qq_gender,$qq_vip,$qq_vip_level,$qq_is_yellow_year_vip,$ip){
		$data = array(
                       'nick' => $nick,
                       'avatar_url' => $avatar_url,
                       'open_id' =>  $this->open_id,
                       'access_token' => $this->access_token,
                        'qq_gender' => $qq_gender,
                        'qq_vip' => $qq_vip,
                       'qq_vip_level' => $qq_vip_level,
                       'qq_is_yellow_year_vip' => $qq_is_yellow_year_vip,
                       'uid' => substr(md5(time()),0,5).substr(md5(uniqid()),0,7),
                       'umask' => substr(md5(time()),0,20).substr(md5(uniqid()),0,20),
                       'ip' => $ip,
                       'login_time' => date("Y-m-d H:i:s"),
                       'last_active_time' => date("Y-m-d H:i:s"),
                       'login_times' => '1'
                );
                $this->db->insert('user', $data);
                if ($this->db->affected_rows() > 0)
                        return true;
                else return false;
	}
        
        /**
         * 更新用户信息
         *
         */
        function update_user($open_id,$nick,$avatar_url,$qq_gender,$qq_vip,$qq_vip_level,$qq_is_yellow_year_vip,$ip,$email = '', $qq='', $wangwang = ''){
		$data = array(
                       'nick' => $nick,
                       'avatar_url' => $avatar_url,
                       'ip' => $ip,
                       'login_time' => date("Y-m-d H:i:s"),
                       'last_active_time' => date("Y-m-d H:i:s"),
                       'qq_gender' => $qq_gender,
                       'qq_vip' => $qq_vip,
                       'qq_vip_level' => $qq_vip_level,
                       'qq_is_yellow_year_vip' => $qq_is_yellow_year_vip
                );
                if ($email != '')  $data['email'] = $email;
                if ($qq != '')  $data['qq'] = $qq;
                if ($wangwang != '')  $data['wangwang'] = $wangwang;
                $this->db->update('user', $data,"open_id = '".$open_id."'");
                if ($this->db->affected_rows() > 0)
                        return true;
                else return false;
	}
        
        /**
         * 更新用户联系信息
         *
         */
        function update_user_contact($uid,$ip,$email = '', $qq='', $wangwang = '',$alipay = ''){
		$data = array(
                       'ip' => $ip,
                       'last_active_time' => date("Y-m-d H:i:s"),
                       'qq' => $qq,
                       'wangwang' => $wangwang,
                       'alipay' => $alipay,
                       'email' => $email
                );

                $this->db->update('user', $data,"uid = '".$uid."'");
                if ($this->db->affected_rows() > 0)
                        return true;
                else return false;
	}
        
        /**
         * 更新用户登录时间
         *
         */
        function update_user_login_time($uid = '',$open_id = ''){
		$data = array(
                        'login_time' => date("Y-m-d H:i:s")
                );
                if ($uid != '')
                        $this->db->update('user', $data,"uid = '".$uid."'");
                if ($open_id != '')
                        $this->db->update('user', $data,"open_id = '".$open_id."'");
                
	}
        
        /**
         * 更新用户最后活动时间
         *
         */
        function update_user_active_time($uid = '',$open_id = ''){
		$data = array(
                        'last_active_time' => date("Y-m-d H:i:s")
                );
                if ($uid != '')
                        $this->db->update('user', $data,"uid = '".$uid."'");
                if ($open_id != '')
                        $this->db->update('user', $data,"open_id = '".$open_id."'");

	}
        
        /**
         * 由openid取得用户信息
         *
         */
        function get_user_by_openid($open_id){
		$sql = "SELECT * FROM ".$this->user_table." WHERE open_id='".$open_id."'";
                $query=$this->db->query($sql);

                if ($query->num_rows() > 0)
                {
                   $row = $query->row();
                   return $row;
                }else{
                        return false;
                }

	}
        
        /**
         * 由uid取得用户信息
         *
         */
        function get_user_by_uid($uid,$umask){
                $this->db->select('*')->from('user')->where('uid', $uid);
                $this->db->where('umask', $umask);
                $query = $this->db->get();

                if ($query->num_rows() > 0)
                {
                   $row = $query->row();
                   return $row;
                }else{
                        return false;
                }

	}
        
        
        
        
        
        
        /*
	 * 增加登录次数
	 *  */
	function add_login_count($uid = '',$open_id = ''){
                if ($uid != '')
                        $sql_query = "UPDATE ".$this->user_table." SET login_times = login_times+1 WHERE uid ='".$uid."'";
                if ($open_id != '')
                        $sql_query = "UPDATE ".$this->user_table." SET login_times = login_times+1 WHERE open_id ='".$open_id."'";
                $this->db->query($sql_query);
		return $uid;
	}
    
}
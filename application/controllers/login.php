<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

        protected $User = '';

	function __construct(){
        
                parent::__construct();
                $this->load->model('M_oauth');
                if (!isset($_SESSION))
                        session_start();
        }

	/**
	 * 登录的默认视图
	 *
	 */
	function index(){
		$this->load->view('login/login_view');
	}

	/**
	 * 使用第三方登录OAuth2.0
	 *
	 */
	function oauth_qq(){
                $_SESSION['state'] = md5(uniqid(rand(), TRUE));
		$this->config->load('oauth_qq');
		$url = $this->config->item('authorize_url')."?client_id=".$this->config->item('api_key')."&response_type=code&redirect_uri=".$this->config->item('callback_url')."&state=".$_SESSION['state'] ;
		header('location:'.$url);
	}

	/**
	 * OAuth回调认证
	 *
	 */
	function oauth_access_qq(){
		//$this->load->model('M_oauth');
		$code =$this->input->get('code');
                
                $oauth = new M_oauth();
                $user_info = $oauth->get_user_info($code);
                if ($user_info)
                {
                        $user = json_decode($user_info);
                        if ($user->ret == 0)
                        {
                                // echo 'ok'.$user->nickname;
                                // echo '--'.$oauth->open_id;
                                // echo '--'.$oauth->access_token;
                                if (!$oauth->UserExist($oauth->open_id))        //新加用户
                                {
                                        if ( $oauth->add_new_user($user->nickname,$user->figureurl_1,$user->gender,$user->vip,$user->level,$user->is_yellow_year_vip,$this->input->ip_address()) )
                                        {
                                                $this->User = $oauth->get_user_by_openid($oauth->open_id); 
                                                //echo "add User OK!";
                                                //var_dump($this->User);
                                                $this->input->set_cookie('uid', $this->User->uid, 60*60*24*7);
                                                $this->input->set_cookie('umask', $this->User->umask, 60*60*24*7);
                                                $this->input->set_cookie('unick', $this->User->nick, 60*60*24*7);
                                                $this->input->set_cookie('uurl', $this->User->avatar_url, 60*60*24*7);
                                                $_SESSION["new_user"] = '1';
                                                $this->set_user();
                                        }
                                        else
                                        {
                                                echo "add User Fail!";
                                                Header("HTTP/1.1 303 See Other");
                                                Header("Location: ".site_url('login/oauth_qq'));
                                                exit;
                                        }
                                }
                                else    //已经存在用户,更新信息
                                {
                                        $oauth->update_user($oauth->open_id,$user->nickname,$user->figureurl_1,$user->gender,$user->vip,$user->level,$user->is_yellow_year_vip,$this->input->ip_address());
                                        $oauth->add_login_count('',$oauth->open_id);    //add login times;
                                        $this->User = $oauth->get_user_by_openid($oauth->open_id);
                                        //echo "Login OK!";
                                        // $this->input->set_cookie('uid', $this->User->uid, 60*60*24*7);
                                        // $this->input->set_cookie('umask', $this->User->umask, 60*60*24*7);
                                        // $this->input->set_cookie('unick', $this->User->nick, 60*60*24*7);
                                        // $this->input->set_cookie('uurl', $this->User->avatar_url, 60*60*24*7);
                                        // Header("HTTP/1.1 303 See Other");
                                        // Header("Location: ".site_url());
                                        // exit; 
                                        $this->set_user();
                                }
                        }
                        else
                        {
                                echo "抱歉!暂时无法登录,请过1-5分钟再重试!";
                                Header("HTTP/1.1 303 See Other");
                                Header("Location: ".site_url());
                                exit;
                        }
                }

		//echo $user_info;
               

	}
        
        /**
	 * 更新用户信息
	 *
	 */
	function update_user(){

                $oauth = new M_oauth();
                if ($_SESSION['Log'] == 1)
                {
                        if ( $oauth->update_user_contact($this->input->cookie('uid',true),$this->input->ip_address(),$this->input->post("email"),$this->input->post("qq"),$this->input->post("wangwang"),$this->input->post("alipay")) )
                                echo 'true';
                        else
                                echo 'false';
                
                }

	}
        
        /**
	 * 取得用户信息
	 *
	 */
	function get_user_contact(){

                $oauth = new M_oauth();
                if ($_SESSION['Log'] == 1)
                {
                        $user = $oauth->get_user_by_uid($this->input->cookie('uid',true),$this->input->cookie("umask"));
                        if ($user)
                        {
                                $arr = array ('qq'=>$user->qq,'wangwang'=>$user->wangwang,'alipay'=>$user->alipay,'email'=>$user->email);
                                echo json_encode($arr);
                        }
                        else
                                echo 'false';
                
                }

	}
        
        /**
	 * 退出登录
	 *
	 */
	public function logout(){
		$this->input->set_cookie('uid', '', time()-60*60*24*7);
                $this->input->set_cookie('umask', '', time()-60*60*24*7);
                $this->input->set_cookie('unick', '', time()-60*60*24*7);
                $this->input->set_cookie('uurl', '', time()-60*60*24*7);
                $_SESSION['Log'] = 0;
                Header("HTTP/1.1 303 See Other");
                Header("Location: ".site_url());
                exit;
	}
        
        /**
	 * 登录动作
	 *
	 */
	public function set_user(){
		$this->input->set_cookie('uid', $this->User->uid, 60*60*24*7);
                $this->input->set_cookie('umask', $this->User->umask, 60*60*24*7);
                $this->input->set_cookie('unick', $this->User->nick, 60*60*24*7);
                $this->input->set_cookie('uurl', $this->User->avatar_url, 60*60*24*7);
                $_SESSION['Log'] = 1;
                Header("HTTP/1.1 303 See Other");
                Header("Location: ".site_url());
                exit;      
	}

	/**
	 * 初次安装
	 *
	 */
	public function install(){
		$this->load->model('M_login');
		$data = $this->M_login->init();
		$this->load->view('login/install_view',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
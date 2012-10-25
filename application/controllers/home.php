<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_item');
		$this->load->library('pagination');
		$this->load->model('M_cat');
                $this->load->model('M_oauth');
                $this->config->load('site_info');
                if (!isset($_SESSION))
                        session_start();
                //检查用户登录状态
                $this->check_user();
                //记录用户最后活动时间
                if (!isset($_SESSION["active_time"]))
                        $_SESSION["active_time"] = time();
                else if (time() - intval($_SESSION["active_time"]) > 10)
                {
                        $_SESSION["active_time"] = time();
                        if ($_SESSION['Log'] == 1)
                        {
                                $oauth = new M_oauth();
                                $oauth->update_user_active_time($this->input->cookie("uid",true));
                        }
                }
                        
	}

        /**
        * 首页控制器
        *
        */
        public function index(){

                $this->page();
        }

        /**
        * 翻页控制器
        *
        * @param integer $page 第几页
        */
	public function page($page = 1)
	{
		
                //$this->output->cache(10);

		$limit=40;
		//每页显示数目

		$config['base_url'] = site_url('/home/page');
		//site_url可以防止换域名代码错误。

		$config['total_rows'] = $this->M_item->count_items();
		//这是模型里面的方法，获得总数。
                $config['use_page_numbers'] = TRUE;
                $config['first_url'] = site_url('/home');
		$config['per_page'] = $limit;
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['num_links']=10;
		//上面是自定义文字以及左右的连接数

		$this->pagination->initialize($config);
		//初始化配置

		$data['limit']=$limit;
		$data['offset']=($page-1)*$limit;
		$data['pagination']=$this->pagination->create_links();
                $data['tbjssdk']=$this->config->item('TBJSSDK');
		//通过数组传递参数
		//以上是重点


		$query = $this->M_cat->get_all_cat();
		$data['cat'] = $query;

		//站点信息
		$data['site_name'] = $this->config->item('site_name');
                $this->load->view('home_head_view',$data);
		$this->load->view('home',$data);
	}

        /**
	 * 检查用户登录
	 *
	 */
	function check_user(){
                $_SESSION['Log'] = 0;
                if($this->input->cookie('uid', TRUE) && $this->input->cookie('umask', TRUE))
                {
                        $oauth = new M_oauth();
                        if ($oauth->get_user_by_uid($this->input->cookie('uid', TRUE),$this->input->cookie('umask', TRUE)))
                        {
                                $_SESSION['Log'] = 1;
                                return true; 
                        }
                }
                return false;

	}
        
	/**
	 * 跳转函数，同时记录点击数量
	 *
	 * 点击记数要排除机器访问
	 */
	function redirect($item_id){

                $this->load->library('user_agent');
                if(!$this->agent->is_robot()){
                    $this->M_item->add_click_count($item_id);
                }

                Header("HTTP/1.1 303 See Other");
                if ($this->input->cookie('uid',true))
                        Header("Location: ".$this->M_item->get_item_clickurl($item_id)."&unid=".$this->input->cookie('uid',true));
                else
                        Header("Location: ".$this->M_item->get_item_clickurl($item_id));
                exit;
	}

	/**
	 * 按URL查询商品有无做推广
	 *
	 * 
	 */
	function search(){
                $preg = "/^http:\/\/".$this->input->server("SERVER_NAME")."/";
                if ( (!preg_match($preg, $this->input->server("HTTP_REFERER")) || $this->input->server("HTTP_X_REQUESTED_WITH") != "XMLHttpRequest") )
                {
                        echo "1";
                        return;
                }
                if ($_SESSION['Log'] == 1)
                {
                        $url = $this->input->post("url");
                        $iid = $this->get_iid($url);
                        if ($this->input->cookie('uid',true))
                                $outer_id = $this->input->cookie('uid',true);
                        else    $outer_id = '';
                        if ($iid)
                        {
                                if (isset($_SESSION["s_time"]) )
                                {
                                        if ( (time() - intval($_SESSION["s_time"]) ) < 3)
                                        {
                                                echo "1";
                                                return;
                                        }
                                }
                                $_SESSION["s_time"] = time();
                                $this->load->model('M_taobaoapi');
                                $resp = $this->M_taobaoapi->getItemDetail($iid, $outer_id);
                                if (intval($resp->total_results) > 0 )
                                        echo $resp->taobaoke_item_details->taobaoke_item_detail->click_url;
                                else
                                        echo "False";

                        }
                        else
                                echo "False";
                }
                else
                        echo "0";
                
	}
        
        /**
	 * 按URL查询商品ID
	 *
	 * 
	 */
        function get_iid($url){
                if (preg_match("/[\?&]+id=(\d+)/i",$url, $matches))
                {
                        $iid = $matches[1];
                        return $iid;
                }
                else
                    return false;    
        }
        
       /**
	 * 显示报表
	 *
	 */
	public function report(){
                $data['site_name'] = $this->config->item('site_name');
                $data['tbjssdk']=$this->config->item('TBJSSDK');
                if ($_SESSION['Log'] == 1)
                {
                        $this->load->model('M_report');
                        if ($this->input->get("date_e"))
                                $date_s = $this->check_date($this->input->get("date_s"));
                        if ($this->input->get("date_e"))
                                $date_e = $this->check_date($this->input->get("date_e"));
                        if ($this->input->get("payed"))
                                $payed = intval($this->input->get("payed"));
                        if (isset($date_s) && isset($date_e) && isset($payed))
                                $data["report"] = $this->M_report->get_report($date_s,$date_e,$payed,$this->input->cookie('uid',true));
                        if (isset($date_s) && isset($date_e) && !isset($payed))
                                $data["report"] = $this->M_report->get_report($date_s,$date_e,'',$this->input->cookie('uid',true));
                        if (!isset($date_s) && !isset($date_e) && isset($payed))
                                $data["report"] = $this->M_report->get_report('','',$payed,$this->input->cookie('uid',true));
                        if (!isset($date_s) && !isset($date_e) && !isset($payed))
                                $data["report"] = $this->M_report->get_report('','','',$this->input->cookie('uid',true));
                        $data["Login"] = 1;
                        
                }
                else
                {
                        $data["Login"] = 0;
                     
                }
                $this->load->view('home_head_view',$data);
                $this->load->view('report_user_view',$data);
	}
        
        private function check_date($date)
        {
                return date("Y-m-d",strtotime($date));
        }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
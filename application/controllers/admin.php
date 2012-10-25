<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * 后台Admin class的构造器
	 *
	 * 载入条目、话题、类别模型
	 * inclue_login 载入session判断视图
	 * include_header 载入顶部统一视图
	 */
	function __construct()
		{
			parent::__construct();
			$this->load->model('M_item');
			$this->load->model('M_cat');
			$this->load->view('admin/include_login'); //检查cookie
                        date_default_timezone_set('Asia/Shanghai');
		}

	/**
	 * 后台首页
	 *
	 */
	public function index()
	{
		$this->load->view('admin/include_header');
		$this->load->view('admin/index_view');
	}

	/**
	 * 登出
	 *
	 */
	public function logout()
	{
                $this->input->set_cookie('user_email','',0);
                $this->input->set_cookie('user_password','',0);
		//跳转
		Header("HTTP/1.1 303 See Other");
		Header("Location: ".site_url('login'));
		exit;
	}

	/**
	 * 搜索结果页
	 *
	 */
	public function search(){
                $this->load->model('M_taobaoapi');
                $data['cat'] = $this->M_cat->get_all_cat();

                 //获取搜索关键词
                $keyword = $this->input->get('keyword');

                /* cid是类别id */
                $cid = '0';
                //if(!empty($this->input->get('cat_select'))){
                if($this->input->get('cat_select')){
                    $cid = intval($this->input->get('cat_select'));
                }
                
                if($this->input->get('price_s')){
                    $price_s = intval($this->input->get('price_s'));
                }
                else $price_s = '';
                
                if($this->input->get('price_e')){
                    $price_e = intval($this->input->get('price_e'));
                }
                else $price_e = '';
                
                if($this->input->get('credit_s')){
                    $credit_s = $this->input->get('credit_s');
                }
                else $credit_s = '';
                
                if($this->input->get('credit_e')){
                    $credit_e = $this->input->get('credit_e');
                }
                else $credit_e = '';
                
                if($this->input->get('totalnum_s')){
                    $totalnum_s = intval($this->input->get('totalnum_s'));
                }
                else $totalnum_s = '';
                
                if($this->input->get('totalnum_e')){
                    $totalnum_e = intval($this->input->get('totalnum_e'));
                }
                else $totalnum_e = '';
                
                if($this->input->get('CommissionRate_s')){
                    $CommissionRate_s = intval($this->input->get('CommissionRate_s'));
                }
                else $CommissionRate_s = '';
                
                if($this->input->get('CommissionRate_e')){
                    $CommissionRate_e = intval($this->input->get('CommissionRate_e'));
                }
                else $CommissionRate_e = '';
                
                if($this->input->get('sort')){
                    $sort = $this->input->get('sort');
                }
                else $sort = '';
                
                if($this->input->get('pages')){
                    $pages = intval($this->input->get('pages'));
                }
                else $pages = 30;
                
                if($this->input->get('pageNo')){
                    $pageNo = intval($this->input->get('pageNo'));
                }
                else $pageNo = 1;
                
                if($this->input->get('mall')){
                    $mall = ($this->input->get('mall') == 'true' ? 'true' : '');
                }
                else $mall = '';


                $data['resp'] = $this->M_taobaoapi->searchItem($keyword, $cid, $price_s, $price_e, $credit_s , $credit_e , $sort, $totalnum_s, $totalnum_e, $CommissionRate_s, $CommissionRate_e, $pages, $pageNo, $mall);
                $data['keyword'] =  $this->input->get('keyword');

		$this->load->view('admin/include_header');
		$this->load->view('admin/search_view',$data);
	}

	/**
	 * 统计页
	 *
	 * @param string stattype 可以是items/shops/cats
	 * @param integer offset 数据库偏移量
	 *
	 */
	public function status($stattype,$offset = 0){
		//按条目
		if($stattype == 'items'){
			$this->load->library('pagination');

			$limit=40;
			//每页显示数目

			$config['base_url'] = site_url('/admin/status/items');
			//site_url可以防止换域名代码错误。

			$config['total_rows'] = $this->M_item->count_items();
			//这是模型里面的方法，获得总数。

			$config['per_page'] = $limit;
			$config['first_link'] = '首页';
			$config['last_link'] = '尾页';
			$config['num_links']=10;
			//上面是自定义文字以及左右的连接数

			$this->pagination->initialize($config);
			//初始化配置

			$data['limit']=$limit;
			$data['offset']=$offset;
			$data['pagination']=$this->pagination->create_links();
			//通过数组传递参数
			//以上是重点


			$query = $this->M_cat->get_all_cat();
			$data['cat'] = $query;
			$this->load->view('admin/include_header');
			$this->load->view('admin/status/items_view',$data);
		}

		//如果是按店铺查看
		else if($stattype == 'shops'){
                        $data['query'] = $this->M_item->query_shops();
                        $data['click_count_sum'] = $this->M_cat->click_count_by_cid();
                        $data['item_count_sum'] = $this->M_item->count_items();
                        $this->load->view('admin/include_header');
                        $this->load->view('admin/status/shops_view',$data);
		}

		//如果是按类别查看
		else if($stattype == 'cats'){
                        $data['query'] = $this->M_cat->query_cats();
                        $data['click_count_sum'] = $this->M_cat->click_count_by_cid();
                        $data['item_count_sum'] = $this->M_item->count_items();
                        $this->load->view('admin/include_header');
                        $this->load->view('admin/status/cats_view',$data);
		}
	}


	/**
	 * 管理类目
	 */
	public function cat(){
		$data['cat'] = $this->M_cat->get_all_cat();
		$data['cat_saved'] = false;
		$this->load->view('admin/include_header');
		$this->load->view('admin/cat_view',$data);
	}

    /**
     * 增加类目
     *
     * @param string $parentid 可选的参数
     */
	public function catadd($parentid = '0'){
                $this->load->model('M_taobaoapi');
                $data['resp'] = $this->M_taobaoapi->getCats($parentid);
		$this->load->view('admin/include_header');
		$this->load->view('admin/catadd_view',$data);
	}

	public function catupdate_op(){
		$this->M_cat->update_cat();
		$data['cat_saved'] = true;
                $data['cat'] = $this->M_cat->get_all_cat();
                $this->load->view('admin/include_header');
                $this->load->view('admin/cat_view',$data);
	}


	public function catadd_op(){
                $this->M_cat->add_cat();
                $data['cat'] = $this->M_cat->get_all_cat();
		$data['cat_saved'] = false;
                $this->load->view('admin/include_header');
                $this->load->view('admin/cat_view',$data);
	}

	/**
	 * 删除条目
	 */
	public function delete_item(){
		$this->M_item->delete_item();
	}

    /**
     * 获得条目信息
     *
     * @return string $resp json字符串，包含所有的相关图片
     */
	public function getiteminfo(){
                $this->load->model('M_taobaoapi');
                $item_id = $this->input->get('item_id');
                $resp = $this->M_taobaoapi->getiteminfo($item_id);

                $img_url_array =array();

                if($resp->item->item_imgs){
                    foreach($resp->item->item_imgs->item_img as $item_img){
                        array_push($img_url_array,(string)$item_img->url);
                    }
                }

                if($resp->item->prop_imgs){
                    foreach($resp->item->prop_imgs->prop_img as $prop_img){
                        array_push($img_url_array,(string)$prop_img->url);
                    }
                }

                $item_info_array = array();
                $item_info_array['imgs'] = $img_url_array;

                echo json_encode($item_info_array);

	}

	/**
	 * 设置条目信息
	 *
	 */
	public function setitem(){
		$data['state'] = $this->M_item->set_item();
	}

	/**
	 * 保存图片
	 *
	 * 抓取远程图片，保存到本地，尺寸为230px
	 */
	public function saveimage(){
                $image_source_url = $this->input->post('img_source_url');
                $image_new_name = $this->input->post('img_new_name');
                try{
                         $this->M_item->save_image($image_source_url,$image_new_name);
                }
                catch(Exception $e)
                {
                       //输出500错误表示保存图片失败
                      header('HTTP/1.1 500 '.$e->getMessage());
                          die();
                }
	}

        /**
        * get_item
        *
        * @param
        * @return
        */
        public function getitem($item_id){
                $itemExist = $this->M_item->itemExist($item_id);
                echo $itemExist;
        }
    
    
        /**
	 * 取得报表
	 *
	 */
	public function get_report($date_a = ''){
                $this->load->model('M_taobaoapi');
                $this->load->model('M_report');
                if($this->input->get('pages')){
                    $pages = intval($this->input->get('pages'));
                }
                else $pages = 100;
                
                if($this->input->get('pageNo')){
                    $pageNo = intval($this->input->get('pageNo'));
                }
                else $pageNo = 1;
                
                if($this->input->get('date')){
                    $date = $this->input->get('date');
                    $date = date("Ymd",strtotime($date));
                    
                }
                else $date = date("Ymd");
                if ($date_a != '')
                        $date = date("Ymd",strtotime($date_a));

                $report = $this->M_taobaoapi->get_report($date, $pageNo, $pages);
                $row = $this->M_report->save_report($report,$date);
                if ($row)
                        echo $row;
                else
                        echo "false";
                
                
	}
        
        /**
	 * 显示报表
	 *
	 */
	public function report(){
                $this->load->model('M_report');
                if ($this->input->get("date_e"))
                        $date_s = $this->check_date($this->input->get("date_s"));
                if ($this->input->get("date_e"))
                        $date_e = $this->check_date($this->input->get("date_e"));
                if ($this->input->get("payed"))
                        $payed = intval($this->input->get("payed"));
                if (isset($date_s) && isset($date_e) && isset($payed))
                        $data["report"] = $this->M_report->get_report($date_s,$date_e,$payed);
                if (isset($date_s) && isset($date_e) && !isset($payed))
                        $data["report"] = $this->M_report->get_report($date_s,$date_e);
                if (!isset($date_s) && !isset($date_e) && isset($payed))
                        $data["report"] = $this->M_report->get_report('','',$payed);
                if (!isset($date_s) && !isset($date_e) && !isset($payed))
                        $data["report"] = $this->M_report->get_report();
                $this->load->view('admin/include_header');
                $this->load->view('report_view',$data);
	}
        
        private function check_date($date)
        {
                return date("Y-m-d",strtotime($date));
        }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
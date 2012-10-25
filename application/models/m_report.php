<?php

class M_report extends CI_Model{

        var $report_table = '';

	function __construct()
	{
		parent::__construct();
                $this->report_table = $this->db->dbprefix('report');  
	}


	//
	function save_report($report,$date){
                $row = 0;
                $this->db->where('pay_date', date("Y-m-d",strtotime($date))); 
                $query = $this->db->get('report');
                if ($query->num_rows() < 1)
                {
                        if($report->taobaoke_report->total_results > 0)
                        {
                                foreach($report->taobaoke_report->taobaoke_report_members->taobaoke_report_member as $taobaoke_report)
                                {
                                        //echo $taobaoke_report->category_name;
                                        $data[] = array(
                                                'trade_parent_id' => strval($taobaoke_report->trade_parent_id),
                                                'trade_id' => strval($taobaoke_report->trade_id),
                                                'real_pay_fee' => strval($taobaoke_report->real_pay_fee),
                                                'commission_rate' => strval($taobaoke_report->commission_rate),
                                                'commission' => strval($taobaoke_report->commission),
                                                'outer_code' => strval($taobaoke_report->outer_code),
                                                'pay_time' => strval($taobaoke_report->pay_time),
                                                'pay_date' => strval(substr($taobaoke_report->pay_time,0,10)),
                                                'pay_price' => strval($taobaoke_report->pay_price),
                                                'num_iid' => strval($taobaoke_report->num_iid),
                                                'item_title' => strval($taobaoke_report->item_title),
                                                'item_num' => strval($taobaoke_report->item_num),
                                                'category_id' => strval($taobaoke_report->category_id),
                                                'category_name' => strval($taobaoke_report->category_name),
                                                'shop_title' => strval($taobaoke_report->shop_title),
                                                'seller_nick' => strval($taobaoke_report->seller_nick));
                                }
                        
                        
                                $this->db->insert_batch('report', $data);
                                $row = $this->db->affected_rows();
                        }
                }
                if ($row > 0)
                {
                        $this->update_report_log(date("Y-m-d",strtotime($date)));
                        return $row;
                }
                else    return false;
	}
        
        function update_report_log($date)
        {
                $this->db->where('log_type="report_last_date"');
                $query = $this->db->get('log');
                if ($query->num_rows() < 1)
                        $this->db->insert('log', array("log_type" => "report_last_date")); 
                $data = array(
                       'log_value' => strval($date),
                       'log_date' => strval(date("Y-m-d H:i:s"))
                );
                $this->db->update('log', $data,"log_type='report_last_date'");
                
        }






	//取得报表数据
	//
	//
	function get_report($date_s = '',$date_e = '', $payed = '', $uid = '')
	{
        
		if($date_s !='' && $date_e !='')
                {
                        $this->db->where('pay_date <=', strval($date_e)); 
                        $this->db->where('pay_date >=', strval($date_s));
			
                }
                if ($uid != '')
                        $this->db->where('outer_code =', strval($uid)); 
                if ($payed != '')
                        $this->db->where('payed =', strval($payed)); 
                $this->db->order_by("report_id", "desc"); 
                $query = $this->db->get('report');


		return $query;
	}




}

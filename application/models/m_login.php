<?php

class M_login extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 初始化函数
	 *
	 * 在已经创建数据库的情况下，初始化数据库表信息
	 * 之后接受输入管理员邮箱密码，保存到数据库
	 */
	function init()
	{
		$this->load->dbutil();
		$this->load->dbforge();
		$this->load->database();
		$data['text'] = '';


		$fields_item = array(
			'id' => array(
				'type' => 'BIGINT',
				'constraint' => '15',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			),
			'title' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'cid' => array(
				 'type' => 'INT',
				 'constraint' => '10',
                        ),
                        'num_iid' => array(
				 'type' => 'BIGINT',
				 'constraint' => '15',
                        ),
			'click_count' => array(
				 'type' => 'INT',
				 'constraint' => '10',
                        ),
			'click_url' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'img_url' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'price' => array(
				 'type' => 'FLOAT'
                        ),
			'sellernick' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
                        'seller_credit' => array(
				 'type' => 'int',
				 'constraint' => '6',
                        ),
                        'shop_type' => array(
				 'type' => 'varchar',
				 'constraint' => '6',
                        ),
                        'item_location' => array(
				 'type' => 'varchar',
				 'constraint' => '20',
                        ),
                        'uuid' => array(
				 'type' => 'CHAR',
				 'constraint' => '15',
                        ),
		);

		$this->dbforge->add_field($fields_item);
		$this->dbforge->add_key('id');

		//创建表item，如果不存在
                if($this->dbforge->create_table('item', TRUE))
                {
                   $data['text'] .=  '<p>表item已经被创建!</p>';
                }
                
                if ($this->input->get("action"))
                {
                        if ($this->input->get("action") == 'update')
                        {
                                // modify_column add_column
                                $field_c = array_chunk($fields_item,1,true);
                                for ($i = 0; $i < count($field_c); $i++)
                                {
                                        if ($i == 3 || $i > 8)      //3,9,10,11,12
                                                $this->dbforge->add_column('item', $field_c[$i]);
                                        $this->dbforge->modify_column('item', $field_c[$i]);
                                }
                        }
                }
                

		$fields_user = array(
			'id' => array(
				'type' => 'BIGINT',
				'constraint' => '15',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			),
			'nick' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'avatar_url' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'email' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
                        ),
			'open_id' => array(
				 'type' => 'VARCHAR',
                                 'constraint' => '100',
                        ),
			'access_token' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
                        ),
                        'uid' => array(
				 'type' => 'CHAR',
				 'constraint' => '12',
                        ),
                        'umask' => array(
				 'type' => 'CHAR',
				 'constraint' => '40',
                        ),
                        'ip' => array(
				 'type' => 'CHAR',
				 'constraint' => '15',
                        ),
                        'login_time' => array(
				 'type' => 'datetime',
                        ),
                        'last_active_time' => array(
				 'type' => 'datetime',
                        ),
                        'login_times' => array(
				 'type' => 'INT',
				 'constraint' => '10',
                        ),
                        'qq' => array(
				 'type' => 'BIGINT',
				 'constraint' => '15',
                        ),
                        'wangwang' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
                        ),
                        'alipay' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
                        ),
                        'qq_vip' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '10',
                        ),
                        'qq_gender' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '10',
                        ),
                        'qq_vip_level' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '10',
                        ),
                        'qq_is_yellow_year_vip' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '10',
                        ),
		);

		$this->dbforge->add_field($fields_user);
		$this->dbforge->add_key('id');

		//创建表user，如果不存在
                if($this->dbforge->create_table('user', TRUE))
                {
                   $data['text'] .=  '<p>表user已经被创建!</p>';
                }
                
                
                if ($this->input->get("action"))
                {
                        if ($this->input->get("action") == 'update')
                        {
                                // modify_column add_column
                                $field_c = array_chunk($fields_user,1,true);
                                for ($i = 0; $i < count($field_c); $i++)
                                {
                                        if ($i > 5)
                                                $this->dbforge->add_column('user', $field_c[$i]);
                                        $this->dbforge->modify_column('user', $field_c[$i]);
                                }
                        }
                }



		$fields_cat = array(
			'cat_id' => array(
				 'type' => 'INT',
				 'constraint' => '10',
                        ),
			'cat_name' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
			'cat_slug' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        )
		);

		$this->dbforge->add_field($fields_cat);
		$this->dbforge->add_key('cat_id',TRUE);

		//创建表cat，如果不存在
                if($this->dbforge->create_table('cat', TRUE))
                {
                   $data['text'] .=  '<p>表cat已经被创建!</p>';
                }
                
                $fields_report = array(
			'report_id' => array(
				 'type' => 'INT',
				 'constraint' => '10',
                                 'unsigned' => TRUE,
				 'auto_increment' => TRUE,
                        ),
			'pay_date' => array(
				 'type' => 'date',
				 
                        ),
                        'trade_parent_id' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
                        ),
			'trade_id' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
                        ),
                        'real_pay_fee' => array(
				 'type' => 'float',
                        ),
                        'commission_rate' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
                        ),
                        'commission' => array(
				 'type' => 'float',

                        ),
                        'outer_code' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
                        ),
                        'pay_time' => array(
				 'type' => 'DATETIME',

                        ),
                        'pay_price' => array(
				 'type' => 'float',

                        ),
                        'num_iid' => array(
				 'type' => 'BIGINT',
				 'constraint' => '15',
                        ),
                        'item_title' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
                        ),
                         'item_num' => array(
				 'type' => 'INT',
				 'constraint' => '3',
                        ),
                         'category_id' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
                        ),
                         'category_name' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
                        ),
                         'shop_title' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
                        ),
                         'seller_nick' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
                        ),
                         'payed' => array(
				'type' => 'INT',
				'constraint' => '1',
                                'default' => '0',
                        ),
                         'mypay_date' => array(
				'type' => 'date',
				
                        ),
                         'mypay_to' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
                        ),
		);
                
                
                //ALTER TABLE `bibishopp_report` ADD UNIQUE (`trade_parent_id`)

		$this->dbforge->add_field($fields_report);
		$this->dbforge->add_key('report_id',TRUE);
              

		//创建表report，如果不存在
                if($this->dbforge->create_table('report', TRUE))
                {
                   $data['text'] .=  '<p>表报report已经被创建!</p>';
                }
                $sql = "ALTER TABLE `".$this->db->dbprefix('report')."` ADD INDEX ( `outer_code` ) ";
                $this->db->query($sql);
                

                
                $fields_log = array(
			'log_type' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '128',
                        ),
			'log_value' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '128',
                        ),
			'log_date' => array(
				 'type' => 'datetime',

                        )
		);

		$this->dbforge->add_field($fields_log);
		$this->dbforge->add_key('log_type',TRUE);

		//创建表log，如果不存在
                if($this->dbforge->create_table('log', TRUE))
                {
                   $data['text'] .=  '<p>表log已经被创建!</p>';
                }
                

		$fields_admin = array(
			'user_email' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '128',
                        ),
			'user_name' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '128',
                        ),
			'user_password' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '128',
                        )
		);

		$this->dbforge->add_field($fields_admin);
		$this->dbforge->add_key('user_email',TRUE);

		//创建表admin，如果不存在
                if($this->dbforge->create_table('admin', TRUE))
                {
                   $data['text'] .=  '<p>表admin已经被创建!</p>';
                   $data['text'] .=  '<p>请输入管理员帐号信息!</p>';
                }

		//检查是否已经存在一个admin
		$data['is_installed'] = $this->db->get('admin')->num_rows();

		return $data;

	}



}

/*


trade_parent_id 	Number 	否 	151968033182758 	淘宝父交易号
trade_id 	Number 	是 	12 	淘宝交易号
real_pay_fee 	Price 	否 	123.12 	实际支付金额
commission_rate 	String 	否 	1200 	佣金比率。比如：0.01代表1%
commission 	Price 	是 	12.15 	用户获得的佣金
app_key 	String 	否 	12 	应用授权码
outer_code 	String 	否 	12 	推广渠道
pay_time 	Date 	是 	2000-01-01 00:00:00 	成交时间
pay_price 	Price 	是 	12.15 	成交价格
num_iid 	Number 	是 	12 	商品ID
item_title 	String 	否 	好 	商品标题
item_num 	Number 	是 	12 	商品成交数量
category_id 	Number 	是 	12 	所购买商品的类目ID
category_name 	String 	是 	12 	所购买商品的类目名称
shop_title 	String 	是 	很好 	店铺名称
seller_nick 	String 	否 	jayzhou 	卖家昵称

*/
<?php

class M_taobaoapi extends CI_Model{

	function __construct()
	{
		parent::__construct();
		$this->config->load('site_info');
                define('APPKEY',    $this->config->item('appkey'));
                define('SECRETKEY',    $this->config->item('secretkey'));
                define('SESSIONKEY',    $this->config->item('sessionkey'));

                        //淘宝客PID请在application/config/site_info中设置
                define('PID',    $this->config->item('taobaoke_pid'));

                include "taobaoapi/TopSdk.php";
	}


    /**
     * 搜索条目
     *
     * @param string $keyword  搜索关键词
     * @param integer $cid  淘宝的后台类目ID
     * @return String $resp XML字符串
     */
    function searchItem($keyword, $cid, $price_s = 0, $price_e = 0, $credit_s = '', $credit_e = '', $sort = '', $totalnum_s = 0, $totalnum_e = 0, $CommissionRate_s = 0, $CommissionRate_e = 0, $pages = 30, $pageNo = 1, $mall = ''){

    	//实例化TopClient类
    	$c = new TopClient;
    	$c->appkey = APPKEY;
    	$c->secretKey = SECRETKEY;

    	$req = new TaobaokeItemsGetRequest;
    	$req->setFields("num_iid,title,click_url,pic_url,price,commission,commission_num,volume,nick,seller_credit_score,item_location");
    	$req->setPid(PID);

    	$req->setCid($cid);
    	$req->setKeyword($keyword);
    //	$req->setSort("commissionVolume_desc");
        
        if ($sort == '')
                $req->setSort("credit_desc");
        else
                $req->setSort($sort);
    	$req->setGuarantee("true");             //是否查询消保卖家
        if ($CommissionRate_s > 0 && $CommissionRate_e > 0)
        {
                $req->setStartCommissionRate($CommissionRate_s);    //佣金比率下限，如：1234表示12.34% 
                $req->setEndCommissionRate($CommissionRate_e);
        }
        else
        {
                $req->setStartCommissionRate("500");    //佣金比率下限，如：1234表示12.34% 
                $req->setEndCommissionRate("5000");

        }
        if ($totalnum_s > 0 && $totalnum_e > 0)
        {
                $req->setStartTotalnum($totalnum_s);     
                $req->setENDTotalnum($totalnum_e);
        }
        if ($price_s > 0 && $price_e > 0)
        {
                $req->setStartPrice($price_s);     
                $req->setENDPrice($price_e);
        }
        if ($credit_s <> '' && $credit_e <> '')
        {
                $req->setStartCredit($credit_s);     
                $req->setEndCredit($credit_e);
        }
        if ($mall == 'true')
                $req->setMallItem("true");      //是否商城的商品，设置为true表示该商品是属于淘宝商城的商品，设置为false或不设置表示不判断这个属性 
    	$req->setPageNo($pageNo);             //结果页数.1~10 ,第几页
        if ($pages == 0)
                $req->setPageSize(30);          //每页返回结果数.最大每页40 
        else
                $req->setPageSize($pages);          //每页返回结果数.最大每页40 
    	//$req->setOuterCode("abc");      //自定义输入串.格式:英文和数字组成;长度不能大于12个字符,区分不同的推广渠道,如:bbs,表示bbs为推广渠道;blog,表示blog为推广渠道. 
        //price start_price     end_price 
        //start_credit  end_credit
        //卖家信用: 1heart(一心) 2heart (两心) 3heart(三心) 4heart(四心) 5heart(五心) 1diamond(一钻) 2diamond(两钻) 3diamond(三钻) 4diamond(四钻) 5diamond(五钻) 1crown(一冠) 2crown(两冠) 3crown(三冠) 4crown(四冠) 5crown(五冠) 1goldencrown(一黄冠) 2goldencrown(二黄冠) 3goldencrown(三黄冠) 4goldencrown(四黄冠) 5goldencrown(五黄冠) 
        //sort  默认排序:default price_desc(价格从高到低) price_asc(价格从低到高) credit_desc(信用等级从高到低) commissionRate_desc(佣金比率从高到低) commissionRate_asc(佣金比率从低到高) commissionNum_desc(成交量成高到低) commissionNum_asc(成交量从低到高) commissionVolume_desc(总支出佣金从高到低) commissionVolume_asc(总支出佣金从低到高) delistTime_desc(商品下架时间从高到低) delistTime_asc(商品下架时间从低到高) 
        //start_totalnum end_totalnum   商品总成交量（与返回字段volume对应）下限。
        
        
    	//执行API请求并打印结果
    	$resp = $c->execute($req);
    	return $resp;
    }
    
    
     /**
     * 获取报表
     *
     * 
     * @return String $resp XML字符串
     */
    function get_report($date, $pageNo = 1, $PageSize = 100){

    	//实例化TopClient类
    	$c = new TopClient;
    	$c->appkey = APPKEY;
    	$c->secretKey = SECRETKEY;

    	$req = new TaobaokeReportGetRequest;
    	$req->setFields("trade_parent_id,trade_id,real_pay_fee,commission_rate,commission,app_key,outer_code,pay_time,pay_price,num_iid,item_title,item_num,category_id,category_name,shop_title,seller_nick");
    	$req->setDate($date);

    	$req->setPageNo($pageNo);       //1-499

        $req->setPageSize($PageSize);          //每页返回结果数.最大每页100,默认40
        
    	//执行API请求并打印结果
    	$resp = $c->execute($req,SESSIONKEY);
    	return $resp;
    }

    /**
     * 根据条目ID获取更详细的信息，包括图片列表
     *
     * @param integer $item_id  条目ID
     * @return string $resp 包含图片列表的XML
     */
    function getItemInfo($item_id){
        $c = new TopClient;
        $c->appkey = APPKEY;
        $c->secretKey = SECRETKEY;
        $req = new ItemGetRequest;
        //prop_imgs 选择颜色的时候出现的图
        //item_imgs->item_img->url 所有的大图
        //desc 好像很厉害的样子
        $req->setFields("prop_img.url,item_img.url,nick");
        //      num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume
        //	$req->setFields("detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual");
        $req->setNumIid($item_id);
        $resp = $c->execute($req);


        return $resp;
    }
    
    /**
     * 根据条目ID获取推广更详细的信息
     *
     * @param integer $item_id  条目ID
     * @return string $resp 
     */
    function getItemDetail($item_id, $outer_id = ''){
        $c = new TopClient;
        $c->appkey = APPKEY;
        $c->secretKey = SECRETKEY;
        $req = new TaobaokeItemsDetailGetRequest;
        if ($outer_id != '')
                $req->setOuterCode($outer_id);
        //prop_imgs 选择颜色的时候出现的图
        //item_imgs->item_img->url 所有的大图
        //desc 好像很厉害的样子
        $req->setFields("click_url, shop_click_url,seller_credit_score");
        //      num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume
        //	$req->setFields("detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual");
        $req->setNumIids($item_id);
        $resp = $c->execute($req);


        return $resp;
    }

    function getCats($parentid){
        $c = new TopClient;
        $c->appkey = APPKEY;
        $c->secretKey = SECRETKEY;

        $req = new ItemcatsGetRequest;
        $req->setFields("cid,parent_cid,name,is_parent");
        //50011740 男鞋
        //16 女装/女士精品
        //50006842 箱包皮具/热销女包/男包
        //50012029 运动鞋new
        //30 男装
        $req->setParentCid($parentid);
        return $c->execute($req);
    }
}
<?php


?>


<div id="page-items">

	<?php 
        if ($Login == 1)
        {
        if($report->num_rows()>0)
        
        { ?>

	<table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>淘宝交易号</th>
        <th>实际支付金额</th>
        <th>佣金比率</th>
        <th>获得的佣金</th>
        <th>成交时间</th>
        <th>成交价格</th>
        <th>商品标题</th>
        <th>成交数量</th>
        <th>佣金支付状态</th>
        <th>支付时间表</th>
        <th>支付账号</th>
      </tr>
    </thead>
    <tbody>
	<?php
	 foreach ($report->result() as $array):
	//条目开始
		?>
	<tr>
        <td><?php echo $array->trade_id;?></td>
        <td><?php echo $array->real_pay_fee;?></td>
        <td><?php echo $array->commission_rate;?></td>
        <td><?php echo $array->commission;?></td>
        <td><?php echo $array->pay_time;?></td>
        <td><?php echo $array->pay_price;?></td>
        <td><?php echo $array->item_title;?></td>
        <td><?php echo $array->item_num ?></td>
        <td><?php echo $array->seller_nick ?></td>
        <td><?php if ($array->payed == '0') echo "未支付"; else echo "已支付";?></td>
        <td><?php if($array->mypay_date != '0000-00-00') echo $array->mypay_date ?></td>
        <td><?php echo $array->mypay_to ?></td>

      </tr>
	<?php
    //条目结束
    endforeach;?>
		</tbody>
  </table>
	<div class="pagenav">
		
	</div>
	<?php }
                else { ?> 
                        <div class="hero-unit offset5 span5">
                        <h2>你好!</h2>
                        <p>加油哦!暂时还没有佣金!</p>
                        <p>
                        <a class="btn btn-primary btn-large" href="<?php echo site_url()?>" title="返回首页">
                        返回
                        </a>
                        </p>
                        </div>
                
                <?php } } 
                
        if ($Login == 0)
        { ?>
                    <div class="hero-unit offset5 span5">
                    <h2>你好!</h2>
                    <p>请先登录,然后从本网站的商品链接或是查询出的商品链接去购买并成交,才会有佣金哦!</p>
                    <p>
                    <a class="btn btn-primary btn-large" href="<?php echo site_url('login/oauth_qq')?>" title="使用QQ帐号登录<?php echo $site_name;?>">
                    登录
                    </a>
                    <a class="btn btn-primary btn-large" href="<?php echo site_url()?>" title="返回首页">
                        返回
                    </a>
                    </p>
                    </div>
        <?php } ?>
               
</div>



<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
//document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F247001c57f7d57d224b4e6bd2f1a86e5' type='text/javascript'%3E%3C/script%3E"));
</script>


<script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.js'></script>


</body>
</html>
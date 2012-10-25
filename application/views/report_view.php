<?php


?>


<div id="page-items">

	<?php if($report->num_rows()>0){ ?>

	<table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>成交日期</th>
        <th>淘宝父交易号</th>
        <th>淘宝交易号</th>
        <th>实际支付金额</th>
        <th>佣金比率</th>
        <th>获得的佣金</th>
        <th>推广渠道</th>
        <th>成交时间</th>
        <th>成交价格</th>
        <th>商品ID</th>
        <th>商品标题</th>
        <th>成交数量</th>
        <th>类目ID</th>
        <th>类目名称</th>
        <th>店铺名称</th>
        <th>卖家昵称</th>
        <th>支付状态</th>
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
        <th><?php echo $array->pay_date ?></th>
        <td><?php echo $array->trade_parent_id ?></td>
        <td><?php echo $array->trade_id;?></td>
        <td><?php echo $array->real_pay_fee;?></td>
        <td><?php echo $array->commission_rate;?></td>
        <td><?php echo $array->commission;?></td>
        <td><?php echo $array->outer_code;?></td>
        <td><?php echo $array->pay_time;?></td>
        <td><?php echo $array->pay_price;?></td>
        <td><?php echo $array->num_iid;?></td>
        <td><?php echo $array->item_title;?></td>
        <td><?php echo $array->item_num ?></td>
        <td><?php echo $array->category_id ?></td>
        <td><?php echo $array->category_name ?></td>
        <td><?php echo $array->shop_title ?></td>
        <td><?php echo $array->seller_nick ?></td>
        <td><?php echo $array->payed ?></td>
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
	<?php } ?>
</div>



<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
//document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F247001c57f7d57d224b4e6bd2f1a86e5' type='text/javascript'%3E%3C/script%3E"));
</script>


<script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.js'></script>


</body>
</html>
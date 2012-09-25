<style>
	.modal-header {display:none}
	.modal-footer {display:none}
	.modal-body {position:relative;min-height:250px;}
	.modal-body #img_thumb {position:absolute;left:15px;top:15px;}
	.modal-body .ml {margin-left:250px;}
</style>



<div id="search_input">
        <a href="<?php echo site_url('admin')?>" class="logo"></a>
        <form id="myForm" action="<?php echo site_url('admin/search')?>" method="get" class="form-search">
        <input type="text" value="<?php echo $keyword?>" name="keyword" class="span2" style="margin-bottom:0;">
        <select id="cat_select" name="cat_select" style="margin-bottom:0;" class="span2">
                <option value="0">全部</option>
                <?php
                        foreach($cat->result() as $row){
                        echo '<option value="'.$row->cat_id.'">'.$row->cat_name.'</option>';
                        }
                ?>
        </select>
        <select id="credit_s" name="credit_s" style="margin-bottom:0;" class="span2">
                <option value="">信用从</option>
                <option value="1heart">一心</option>
                <option value="2heart">二心</option>
                <option value="3heart">三心</option>
                <option value="4heart">四心</option>
                <option value="5heart">五心</option>
                <option value="1diamond">一钻</option>
                <option value="2diamond">二钻</option>
                <option value="3diamond">三钻</option>
                <option value="4diamond">四钻</option>
                <option value="5diamond">五钻</option>
                <option value="1crown">一冠</option>
                <option value="2crown">二冠</option>
                <option value="3crown">三冠</option>
                <option value="4crown">四冠</option>
                <option value="5crown">五冠</option>
                <option value="1goldencrown">一黄冠</option>
                <option value="2goldencrown">二黄冠</option>
                <option value="3goldencrown">三黄冠</option>
                <option value="4goldencrown">四黄冠</option>
                <option value="5goldencrown">五黄冠</option>
                
        </select>
        <select id="credit_e" name="credit_e" style="margin-bottom:0;" class="span2">
                <option value="">到信用</option>
                <option value="1heart">一心</option>
                <option value="2heart">二心</option>
                <option value="3heart">三心</option>
                <option value="4heart">四心</option>
                <option value="5heart">五心</option>
                <option value="1diamond">一钻</option>
                <option value="2diamond">二钻</option>
                <option value="3diamond">三钻</option>
                <option value="4diamond">四钻</option>
                <option value="5diamond">五钻</option>
                <option value="1crown">一冠</option>
                <option value="2crown">二冠</option>
                <option value="3crown">三冠</option>
                <option value="4crown">四冠</option>
                <option value="5crown">五冠</option>
                <option value="1goldencrown">一黄冠</option>
                <option value="2goldencrown">二黄冠</option>
                <option value="3goldencrown">三黄冠</option>
                <option value="4goldencrown">四黄冠</option>
                <option value="5goldencrown">五黄冠</option>
                
        </select>
        <select id="sort" name="sort" style="margin-bottom:0;" class="span2" >
                <option value="">排序</option>
                <option value="price_desc">价格从高到低</option>
                <option value="price_asc">价格从低到高</option>
                <option value="credit_desc">信用等级从高到低</option>
                <option value="commissionRate_desc">佣金比率从高到低</option>
                <option value="commissionRate_asc">佣金比率从低到高</option>
                <option value="commissionNum_desc">成交量成高到低</option>
                <option value="commissionNum_asc">成交量从低到高</option>
                <option value="commissionVolume_desc">总支出佣金从高到低</option>
                <option value="commissionVolume_asc">总支出佣金从低到高</option>
                <option value="delistTime_desc">商品下架时间从高到低</option>
                <option value="delistTime_asc">商品下架时间从低到高</option>
             
        </select>
        <br>
        
        价格由<input id="price_s" name="price_s" type="number" class="span1" />
        到<input id="price_e" name="price_e" type="number" class="span1" />
        30天商品总成交量由<input id="totalnum_s" name="totalnum_s" type="number" class="span1" />
        到<input id="totalnum_e" name="totalnum_e" type="number" class="span1" />
        佣金比率由<input id="CommissionRate_s" name="CommissionRate_s" type="number" class="span1" maxlength=4 placeholder="全数字"/>
        到<input id="CommissionRate_e" name="CommissionRate_e" type="number" class="span1"  maxlength=4 placeholder="后两位为小数点"/>
        <input id="mall" name="mall" type="checkbox" value="true" class="" />仅商城商品
        每页显示<input id="pages" name="pages" type="number" value=30 class="span1" />个
        <input id="pageNo" name="pageNo" type="hidden" value=1 />
        <input id="search_btn" type="submit" value="搜索" class="btn btn-success" />
        
        
</div><!-- .search_input -->

<?php

//var_dump($resp);
//打印XML中的条目信息
puPrintItem($resp);

function puPrintItem($resp){
	echo "<ul id='search-list'>";
		if($resp->total_results == 0){
			echo '没有找到条目，请修改关键词或者类别。';
		} else{ //var_dump($resp);
			foreach($resp->taobaoke_items->taobaoke_item as $taobaoke_item){
			?>
				<li>
					<a href='<?php echo $taobaoke_item->click_url ?>' data-taobaoke_id='<?php echo $taobaoke_item->num_iid ?>' 
                                                title='<?php echo strip_tags($taobaoke_item->title)?>' 
                                                data-price='<?php echo $taobaoke_item->price?>' 
                                                data-commission='<?php echo $taobaoke_item->commission ?>' 
                                                data-seller_credit_score='<?php echo $taobaoke_item->seller_credit_score ?>' 
                                                data-item_location='<?php echo $taobaoke_item->item_location ?>' 
                                                data-shop_type='<?php echo $taobaoke_item->coupon_rate  ?>' 
                                                data-sellernick='<?php echo $taobaoke_item->nick; ?>'>
					<img src="<?php echo $taobaoke_item->pic_url?>" alt="<?php echo $taobaoke_item->title?>"/>
					</a>
					<p><span class="right"><?php echo $taobaoke_item->volume ?>件/30天</span><span><?php echo $taobaoke_item->commission ?></span> / <span><?php echo $taobaoke_item->price?></span></p>
				</li>
			<?php
			}
		}
	echo "</ul>";
}
?>
<div id="pager" class="pager">
        <button type="button" class="btn btn-inverse" id="next_page" >下一页</button>
</div>


    
    
<div id="pop-pictures" class="modal hide">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <h3></h3>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
             <a href="" class="btn btn-primary"
		              id="btn-publish">发布条目</a>
            </div>
          </div>

<script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.js'></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/bootstrap-modal.js'></script>
<script type="text/javascript">
(function($) {
	var global_clickurl,global_title,global_price,global_nick,global_cid,global_credit,global_location,global_type;
	//搜索结果中的条目点击
	$('#search-list li a').click(
                function(event){
                        event.preventDefault();


                        //设置一些当前选中条目的公共信息
                        global_clickurl = $(this).attr('href');
                        global_title = htmlEncode($(this).attr('title'));
                        global_price = $(this).data('price');
                        global_sellernick = $(this).data('sellernick');
                        global_commission = $(this).data('commission');
                        global_itemid = $(this).data('taobaoke_id');
                        global_credit = $(this).data('seller_credit_score');
                        global_location = $(this).data('item_location');
                        global_type = $(this).data('shop_type');

                        $('#pop-pictures').modal();

                        $('#pop-pictures .modal-body').html('等下……');
                        $.get('<?php echo site_url("admin/getiteminfo")?>',{item_id:global_itemid},
                                function(data) {
                                        $('#pop-pictures .modal-body').html('<ul></ul>');
                                        $.each(data['imgs'],function(k,v){
                                                $('<li><img src="'+v+'"></li>').insertAfter('#pop-pictures .modal-body ul');
                                        });
                                },'json');
		}
	);

	//弹出窗口中的图片再点击
	$('#pop-pictures li img').live('click',
                function(event){
                        var $img_url = $(this).attr('src');
                        var $item = {};
                        $('#pop-pictures .modal-body').html('<p>正在保存图片……</p>');
                        $.ajax({
                                type: "POST",
                                url:'<?php echo site_url("admin/saveimage/") ?>',
                                data:({
                                        img_source_url: $img_url,
                                        img_new_name: global_itemid
                                })
                        })
                        .fail(function(data){
                                alert("保存失败！"+data.statusText);
                                $('.modal').modal('hide');
                        })
                        .done(function(data){
                                $('#pop-pictures .modal-body').append('<p>图片保存成功……</p>');
                                $item.img_url = data;
                                $item.sellernick = global_sellernick;
                                $item.title = global_title;
                                $item.price = global_price;
                                $item.click_url = global_clickurl;
                                $item.cid = global_cid;
                        })
                        .done(function(){
                                $('#pop-pictures .modal-body').append('<p>保存条目……</p>');
                                console.log($item);
                                $.post('<?php echo site_url("admin/setitem/")?>',
                                           { img_url: $item.img_url,
                                                title: $item.title,
                                                cid: $item.cid,
                                                sellernick: $item.sellernick,
                                                click_url: $item.click_url,
                                                price: $item.price,
                                                iid: global_itemid,
                                                credit: global_credit,
                                                item_location: global_location,
                                                shop_type: global_type
                                                
                                           },
                                           function(data) {
                                                 $('#pop-pictures .modal-body').html('成功！');
                                                 $('.modal').modal('hide');
                                                });

                                event.preventDefault();
                        });

                }
        );

	function htmlEncode(value){
                return $('<div/>').text(value).html();
	}

	function htmlDecode(value){
                return $('<div/>').html(value).text();
	}

	<?php if($this->input->get('cat_select')){
		$cid = intval($this->input->get('cat_select'));
	}else {
                $cid = 0;
        }
	?>
	var cat_select = "<?php echo $cid ?>";
        var credit_s = "<?php echo $this->input->get('credit_s') ?>";
        var credit_e = "<?php echo $this->input->get('credit_e') ?>";
        var sort = "<?php echo $this->input->get('sort') ?>";
        var mall = "<?php echo $this->input->get('mall') ?>";
        var price_s = "<?php echo $this->input->get('price_s') ?>";
        var price_e = "<?php echo $this->input->get('price_e') ?>";
        var totalnum_s = "<?php echo $this->input->get('totalnum_s') ?>";
        var totalnum_e = "<?php echo $this->input->get('totalnum_e') ?>";
        var CommissionRate_s = "<?php echo $this->input->get('CommissionRate_s') ?>";
        var CommissionRate_e = "<?php echo $this->input->get('CommissionRate_e') ?>";
        var pages = "<?php echo $this->input->get('pages') ?>";
        global_cid = cat_select;
	$("#cat_select option").filter(function() {

		//may want to use $.trim in here
		return $(this).val() == $.trim(cat_select);
	}).attr('selected', true);
        
        $("#credit_s option").filter(function() {
		return $(this).val() == $.trim(credit_s);
	}).attr('selected', true);
        
        $("#credit_e option").filter(function() {
		return $(this).val() == $.trim(credit_e);
	}).attr('selected', true);
        
        $("#sort option").filter(function() {
		return $(this).val() == $.trim(sort);
	}).attr('selected', true);
        
        $("#mall").filter(function() {
		return $(this).val() == $.trim(mall);
	}).attr('checked', true);
        
        $("#price_s").val(price_s);
        $("#price_e").val(price_e);
        $("#totalnum_s").val(totalnum_s);
        $("#totalnum_e").val(totalnum_e);
        $("#CommissionRate_s").val(CommissionRate_s);
        $("#CommissionRate_e").val(CommissionRate_e);
        $("#pages").val(pages);
        
        //下一页商品信息,这个方法不太好,应该重写后端,只返回所需的商品信息.现在是用JQ过滤出商品信息.
        $('#next_page').click(
                function(event){
                        event.preventDefault();
                        $("#pageNo").val(parseInt($("#pageNo").val()) + 1);
                        $.get('<?php echo site_url("admin/search")?>',$("#myForm").serialize(),
                                function(data){
                                        var h = $(data).filter('#search-list').html();
                                        //alert(h);
                                        $('#search-list').html(h);

                                }
                        );
		}
	);
        $('#search_btn').click( function(){ $("#pageNo").val(1); });
        

})(jQuery);
</script>
</body>
<html>
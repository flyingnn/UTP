<?php

/*
  * 判断url中是否包含slug
  */
if(!empty($cat_slug)){
	$query=$this->M_item->get_all_item($limit,$offset,$cat_slug);
}else{
	$query=$this->M_item->get_all_item($limit,$offset);
}

$new_user = 0;
if (isset($_SESSION['new_user']) ) 
{  
        if ($_SESSION['new_user'] == 1)
                $new_user = 1;
                
}

?>

<nav class="main_nav">
        <div>
                <ul class="menu">
                        <?php
                                $is_home = '';
                                if(empty($cat_slug)){
                                        $is_home = 'current-menu-item';
                                }
                                ?>
                        <li class="<?php echo $is_home;?>"><a href="<?php echo site_url()?>">全部</a></li>
                        <?php
                           foreach($cat->result() as $row){
                                        $is_current = '';
                                        if(!empty($cat_slug) && $row->cat_slug == $cat_slug){
                                                $is_current = 'current-menu-item';
                                        }
                                   echo '<li class="'.$is_current.'"><a href="'.site_url('cat/'.$row->cat_slug).'">'.$row->cat_name.'</a></li>';
                                }
                         ?>
                </ul>
        </div>
</nav>

<div id="goods_search" class="row pagination-centered">
        <div class="">
                <form id="FormUrl" class="form-inline">
                        商品网址:<input id="url" name="url" type="url" class="span5" placeholder="查看这个商品有无做推广"/>
                        <input id="search_btn" type="submit" value="查询" class="btn btn-success" />
                        <input id="user_id" name="user_id" type="hidden" value="<?php echo $this->input->cookie('uid',true); ?>"  />
                </form>
        </div>
</div>

<div id="search_result" class="modal hide fade">
        <div class="modal-header">
              <a data-dismiss="modal" class="close">×</a>
              <h2>推广商品,点击"去购买"并成交有佣金返点哦!</h2>
        </div>
        <div class="modal-body">
                <div class=" ">
                        <a href="#" id="search_buy" title="去购买" class="btn btn-success" target="_blank">去购买</a>
                </div>
        </div>
        <div class="modal-footer">
              <a data-dismiss="modal" class="btn" href="#">关闭</a>
        </div>

</div>

<div id="user_data" class="modal hide fade">
        <div class="modal-header">
              <a data-dismiss="modal" class="close">×</a>
              <h3>请填写你的联系方式:</h3>
        </div>
        <div class="modal-body">
                <div>
                        <form id="form_data" class="form-horizontal">
                        <fieldset>
                                <div class="control-group">
                                        <label class="control-label" >QQ号码:</label>
                                        <div class="controls">
                                                <input id="qq" name="qq" type="number" class="span2" placeholder=""/>
                                                <span class="help-inline">选填</span>
                                        </div>
                                </div>
                                <div class="control-group">
                                        <label class="control-label" >旺旺账号:</label>
                                        <div class="controls">
                                                <input id="wangwang" name="wangwang" type="text" class="span2" placeholder=""/>
                                                <span class="help-inline">选填</span>
                                        </div>
                                </div>
                                <div class="control-group">
                                        <label class="control-label" >支付宝账号:</label>
                                        <div class="controls">
                                                <input id="alipay" name="alipay" type="email" class="span3" placeholder=""/>
                                                <span class="help-inline">选填</span>
                                        </div>
                                </div>
                                <div class="control-group">
                                        <label class="control-label" >邮箱:</label>
                                        <div class="controls">
                                                <input id="email" name="email" type="email" class="span3" placeholder=""/>
                                                <span class="help-inline">选填</span>
                                        </div>
                                </div>                                
                        
                        </fieldset>
                </form>
                <span class="offset2">也可以先不填,直接点关闭,以后再填写.</span>
                </div>
                <div class="offset1">
                        <a href="#" id="data_save" title="保存资料" class="btn btn-success" target="_blank">保存</a>
                </div>
        </div>
        <div class="modal-footer">
              <a data-dismiss="modal" class="btn" href="#">关闭</a>
        </div>

</div>

<div id="searching" class="modal hide fade">
         <h3>正在查询,请稍候!</h3>
        <div class="progress progress-striped active">
               
                <div class="bar" style="width: 100%;"></div>
        </div>
</div>
        
<div id="wrapper">


	<?php if($query->num_rows()>0){ ?>
	<div class="goods-all transitions-enabled masonry">
	<?php foreach ($query->result() as $array):
	//条目
		?>

		<article class="goods">
			<div class="entry-content">
			<div class="goods-pic">
				<img src="<?php echo $array->img_url ?>" class="thumbnail" alt="" title="<?php echo $array->title ?>">

			</div>
				<div class="op"><div class="desc"><?php echo $array->sellernick ?>   / <strong>RMB<?php echo $array->price ?></strong></div>
				<div class="buttonline">
					<a href="<?php echo site_url('home/redirect').'/'.((strlen($array->uuid) > 5) ? $array->uuid : $array->id) ?>" title="去购买" class="btn btn-success" target="_blank">去购买</a>
				</div></div>
			</div>
		</article>
	<?php endforeach;?>
	</div>
        <div class="pagenav_wrapper">
            <div class="pagenav">
            		<?=$pagination;?>
            	</div>
        </div><!-- .pagenav_wrapper -->

    	<?php } ?>
</div>

<footer id="ft" class="main-footer" role="contentinfo">
		<p><a href="<?php echo site_url();?>" title="<?php echo $site_name;?>"><?php echo $site_name;?></a><span id="power" class="power"> ©   • Powered by <a href="https://github.com/yuguo/33pu" title="Powered by 33号铺, 一个开源的购物推荐系统">33号铺</a></span></p>
</footer>

<script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.js'></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/bootstrap-modal.js'></script>
<script type="text/javascript">
(function($) {
        $('#search_result').modal({
                backdrop:true,
                keyboard:true,
                show:false
                });
        $('#search_btn').click(
                function(event){
                        event.preventDefault();
                        $('#searching').modal("show");
                        $.post('<?php echo site_url("home/search")?>',$("#FormUrl").serialize(),
                                function(data){
                                        var reg = /^http:\/\//;
                                        $('#searching').modal("hide");
                                        $("#search_buy").removeClass(); 
                                        var t = '';
                                        if (data == '0')
                                        {
                                                alert("请先登录!");
                                                return;
                                        }
                                        else if (data == '1')
                                        {
                                                alert("查询太频繁了!");
                                                return;
                                        }
                                        else if (data.match(reg))
                                        {
                                                t = data;
                                                $("#search_buy").attr("href",t);
                                                $("#search_buy").addClass("btn btn-success");
                                                $('#search_result H2').text('推广商品,点击"去购买"并成交有佣金返点哦!');
                                                $('#search_result').modal("show");
                                        }
                                        else if (data == 'False')
                                        {
                                                t = '此商品没有做推广!';
                                                $('#search_result H2').text("此商品没有做推广,购买没有佣金返点.");
                                                $("#search_buy").attr("href",$("#url").val());
                                                $("#search_buy").addClass("btn btn-inverse");
                                                $('#search_result').modal("show");
                                                //alert($("#url").val());
                                        }
                                        else
                                        {
                                                alert("哦!查询出错了,请稍候再试!");
                                                
                                        }
                                }
                        );
		}
                
	);
        
        $('#data_save').click(
                function(event){
                        event.preventDefault();
                        $.post('<?php echo site_url("login/update_user")?>',$("#form_data").serialize(),
                                function(data){
                                        
                                        if (data == 'true')
                                        {
                                                alert("保存成功!");
                                                $('#user_data').modal("hide");
                                                return;
                                        }
                                        else if (data == 'false')
                                        {
                                                alert("糟糕!保存失败,请再重试,或者换个时间再填写吧!");
                                                return;
                                        }
                                        else
                                        {
                                                alert("哦!保存出错了,换个时间再填吧!");
                                                
                                        }
                                }
                        );
		}
                
	);
        $('#data_modify').click(
                function(event){
                        event.preventDefault();
                        $.post('<?php echo site_url("login/get_user_contact")?>','',
                                function(data){
                                        
                                        if (data == 'false')
                                        {
                                                alert("糟糕!读用户信息出错!请再重试,或者换个时间再填写吧!");
                                                return;
                                        }
                                        else
                                        {
                                                var d = jQuery.parseJSON(data);
                                                if (typeof(d.qq) === 'undefined')
                                                        alert("糟糕!读用户信息出错!请再重试,或者换个时间再填写吧!");
                                                else
                                                {
                                                        $('#qq').val(d.qq);
                                                        $('#wangwang').val(d.wangwang);
                                                        $('#alipay').val(d.alipay);
                                                        $('#email').val(d.email);
                                                        $('#user_data').modal("show");
                                                }
                                                
                                        }

                                }
                        );
		}
                
	);
        
        $('#search_buy').click(
                function(){ $('#search_result').modal("hide");
        });
        <?php if ($new_user) { ?>
        
        $('#user_data').modal({
                backdrop:true,
                keyboard:true,
                show:true
                });
        
        <?php } $_SESSION["new_user"] = '0'; unset($new_user); ?>
        
})(jQuery);
</script>
<script type="text/javascript">
//PUT YOUR Baidu or Google analytics code
</script>

</body>
</html>

<?php

/*
  * 判断url中是否包含slug
  */
if(!empty($cat_slug)){
	$query=$this->M_item->get_all_item($limit,$offset,$cat_slug);
}else{
	$query=$this->M_item->get_all_item($limit,$offset);
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="zh-CN">
<head>
<meta charset="UTF-8" />
	<title><?php echo $site_name;?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url()?>assets/bootstrap.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url()?>assets/index.css?d=20120705" />
	<!--[if lt IE 9]>
	<script src="<?php echo base_url()?>assets/js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>

<header id="branding" role="banner">
    <div id="site-title">
        <h1>
            <a href="<?php echo site_url();?>" title="<?php echo $site_name;?>" rel="home" class="logo"><?php echo $site_name;?></a>
        </h1>
			<div id="site-op" class="hide">
				<a href="<?php echo site_url('login/oauth_qq')?>" title="使用QQ帐号登录33号铺" class="qq-login">QQ登录</a>
			</div>
    </div>

</header>

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

<div id="goods_search" class="row">
        <div class="span8 offset4">
                <form id="FormUrl" class="form-inline">
                        商品网址:<input id="url" name="url" type="url" class="span5" placeholder="查看这个商品有无做推广"/>
                        <input id="search_btn" type="submit" value="查询" class="btn btn-success" />
                        <input id="user_id" name="user_id" type="hidden" value=""  />
                </form>
        </div>
</div>

<div id="search_result" class="modal hide fade">
        <div class="modal-header">
              <a data-dismiss="modal" class="close">×</a>
              <h2>推广商品</h2>
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
		<p><a href="<?php echo site_url();?>" title="<?php echo $site_name;?>"><?php echo $site_name;?></a> ©   • Powered by <a href="https://github.com/yuguo/33pu" title="Powered by 33号铺, 一个开源的购物推荐系统">33号铺</a></p>
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
                        $.get('<?php echo site_url("home/search")?>',$("#FormUrl").serialize(),
                                function(data){
                                        var t = '';
                                        if (data != 'False')
                                        {
                                                t = data;
                                                $("#search_buy").attr("href",t);
                                                $('#search_result').modal("show");
                                        }
                                        else
                                        {
                                                t = '此商品没有做推广!';
                                                alert(t);
                                        }
                                }
                        );
		}
	);
        
        $('#search_buy').click(
                function(){ $('#search_result').modal("hide");
        });
})(jQuery);
</script>
<script type="text/javascript">
//PUT YOUR Baidu or Google analytics code
</script>

</body>
</html>

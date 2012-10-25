<?php

/*
*/

if (!isset($_SESSION))
        session_start();
$login = 0;
if (isset($_SESSION['Log']) ) 
{  
        if ($_SESSION['Log'] == 1)
                $login = 1;
                
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
<?php echo $tbjssdk; ?>
</head>
<body>

<header id="branding" role="banner">
    <div id="site-title">
        <h1>
            <a href="<?php echo site_url();?>" title="<?php echo $site_name;?>" rel="home" class="logo"><?php echo $site_name;?></a>
        </h1>
                <div id="site-op" class="">
                        <?php if ($login == 1) { ?>
                                <img src="<?php echo $this->input->cookie('uurl',true) ?>">
                                欢迎你,<a href="#" id="data_modify" title="修改联系信息" class=""><?php echo $this->input->cookie('unick',true) ?>  联系信息</a>|
                                <a href="<?php echo site_url('login/logout')?>" title="退出登录<?php echo $site_name;?>" class="qq-login">退出登录</a>
                        <?php } else { ?>
                                <a href="<?php echo site_url('login/oauth_qq')?>" title="使用QQ帐号登录<?php echo $site_name;?>" class="qq-login"><img src="<?php echo base_url()?>assets/img/Connect_logo_2.png" title='QQ登录'></a>
                        <?php } ?>
                        <a href="<?php echo site_url('home/report')?>" title="佣金报表" class="">佣金报表</a>
                </div>
    </div>

</header>
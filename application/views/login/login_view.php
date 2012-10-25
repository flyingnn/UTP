<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url()?>assets/bootstrap.css" />


</head>
<body>
<div class="" style="width:400px;margin:100px auto;">

<?php
if (!isset($_SESSION))
        session_start();
if ($this->input->post('user_email'))
{
        if ($_SESSION['captcha'] != $this->input->post('captcha'))
        {
              echo "验证码不正确!";  
        }

        //验证登录用户名和密码
        else
        if($this->input->post('user_email') && $this->input->post('user_password')){
                $user_email = $this->input->post('user_email');
                $user_password = md5($this->input->post('user_password'));

                $query = $this->db->get_where('admin', array('user_email' => $user_email,'user_password' => $user_password));
                $result = $query->result();

                if(empty($result)){
                        echo '用户密码错误！';
                        echo site_url();
                }else{
                $this->input->set_cookie('user_email', $user_email, 60*60*24*365);
                $this->input->set_cookie('user_password', $user_password, 60*60*24*365);
                    //跳转
                        Header("HTTP/1.1 303 See Other");
                        Header("Location: ".site_url('admin'));
                        exit;
                }
        }
}
?>

<form action="<?php echo site_url('login')?>" method="post">
<p><input name="user_email" type="text" placeholder="Email" /></p>
<p><input name="user_password" type="password" placeholder="密码" /></p>
<p>
<img src="../../../captcha/captcha.php" id="captcha" />
<a href="#" onclick="
document.getElementById('captcha').src='../../../captcha/captcha.php?'+Math.random();
document.getElementById('captcha-form').focus();"
id="change-image"><br/>看不清?换一张.</a><br/><br/>
<b>验证码:</b><br/>
<input type="text" name="captcha" id="captcha-form" />
</p>
<p><input type="submit" value="登录" class="btn btn-success"></p>
</form>

</div>
</body>
<html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Best Price Vietnam - Login</title>
	
	<?=get_core_theme()?>
	
	<link rel="shortcut icon" href="<?=get_static_resources('/media/favicon.21082013.ico')?>">
	
	<style>
		.form-signin
		{
		    max-width: 330px;
		    padding: 15px;
		    margin: 0 auto;
		}
		.form-signin .form-signin-heading, .form-signin .checkbox
		{
		    margin-bottom: 10px;
		}
		.form-signin .checkbox
		{
		    font-weight: normal;
		}
		.form-signin .form-control
		{
		    position: relative;
		    font-size: 16px;
		    height: auto;
		    padding: 10px;
		    -webkit-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    box-sizing: border-box;
		    border-radius: 2px
		}
		.form-signin .form-control:focus
		{
		    z-index: 2;
		}
		.form-signin input[type="text"]
		{
		    margin-bottom: -1px;
		    border-bottom-left-radius: 0;
		    border-bottom-right-radius: 0;
		}
		.form-signin input[type="password"]
		{
		    margin-bottom: 10px;
		    border-top-left-radius: 0;
		    border-top-right-radius: 0;
		}
		.account-wall
		{
		    margin-top: 20px;
		    padding: 40px 0px 20px 0px;
		    background-color: #f7f7f7;
		    -moz-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
		    -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
		    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
		    border-radius: 2px;
		}
		.login-title
		{
		    color: #555;
		    font-size: 18px;
		    font-weight: 400;
		    display: block;
		    margin-top: 60px;
		}
		.login-logo
		{
		    margin: 0 auto 10px;
		    display: block;
		    font-size:48px;
		    width:100%;
		    text-align:center;
		    font-family: 'Maven Pro', sans-serif;
		}
		.need-help
		{
		    margin-top: 10px;
		}
		.new-account
		{
		    display: block;
		    margin-top: 10px;
		}
		.btn-primary {
			border: 1px solid #0071cd;
			background-color: #0071cd;
			border-radius: 4px;
			padding: 8px 0;
			font-size: 16px
		}
		.btn-primary:hover {
			background-color: #0071cd;
		}

	</style>
</head>
<body>
	<div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title">Sign in</h1>
                <div class="account-wall">
                    <div class="login-logo">
                    	<img alt="Best Price Vietnam" src="<?=site_url('/media/bestpricevn-logo.png')?>">
                    </div>
                    <form class="form-signin" role="form" method="post">
                    
                    <input type="text" class="form-control" name="username" placeholder="Username" id="username" required autofocus maxlength="20">
                    <input type="password" class="form-control" name="password" placeholder="Password" required maxlength="12" id="password">
                    
                    <?php if(!empty($message)):?>
					<div class="errors"><?php echo $message;?></div>
					<?php endif;?>
                    
                    <button class="btn btn-lg btn-primary btn-block" id="btn-login" type="submit">Sign in</button>
                    
                    <label class="checkbox pull-left">
                        <input type="checkbox" id="remember_me"/>
                        Remember me
                    </label>
                    <span class="clearfix"></span>
                    </form>
                </div>
                <a href="http://www.Bestviettravel.xyz/gioi-thieu.html" target="_blank" class="text-center new-account">About Best Price Vietnam</a>
            </div>
        </div>
    </div>
</body>
</html>
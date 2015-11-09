<?php
	session_start();
	
	if(isset($_SESSION["userId"])){
		$type = $_SESSION["usertype"];
		header("Location: $type");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=9"/>
	<meta name="description" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/icons/icon.png" />
	<title>Login</title>
	<script type="text/javascript" src="assets/scripts/jquery-1.11.1.min.js"></script>
	<style>
		body {
			font-family: verdana;
			font-size: 11px;
			background-color: #F6F6F6;
			margin-top: 20px;
		}
		a {
			color: #497B90;
			text-decoration: none;
		}
		a:hover {
			text-decoration: underline;
		}
		.footer {
			color: #777;
		}
		.login-box {
			width: 500px;
			margin: 10px auto;
			border: 1px solid #ccc;
			background: #fff;
			border-radius: 5px;
			box-shadow: 0px 0px 5px #ccc;
			padding: 20px;
		}
		.login-img {
			height: 100%;
			width: auto;
			float: left;
			border-right: 1px solid #dfdfdf;
			padding-right: 10px;
		}
		.login-img div {
			margin-top: 10%;
			font-weight: bold;
			color: #777;
			font-size: 14px;
		}
        .login-img div span {
            font-size: 11px;
        }

		.login-input {
			margin-left: 210px;
			text-align: left;
		}
		.login-input span {
			margin: 20px 0px 3px 0px;
			color: #999;
			font-weight: bold;
			display: block;
		}
		input[type=text], input[type=password] {
			border-radius: 4px;
			padding: 4px;
			border: 1px solid silver;
			/*font-size: 18px;*/
		}
		#message {
			color: red;
			font-size: 12px;
		}
	</style>
	<script>
		function login () {
			var $username = $('#username');
            var $password = $('#password');
			$.ajax({
				url:'checkuser.php',
				type:'post',
				data: {username: $("#username").val(), password: $("#password").val() },
				success: function (out) {
					if(out==1)
						document.location = "index.php";
					else{
						$('#message').html('Incorrect Login or Password');
						$username.focus();
						$password.val('');
					}
				}
			});
		}
	</script>
</head>
<body>
	<br>
	<div style="text-align: center;">

		<br><br>
		<br><br>
		<br><br>

		<div class="login-box">
			<div class="login-img">
				<div> <img src = "assets/images/logo.png" width = "180" height = "150"></div>
			</div>
			<div class="login-input">
				<span style="color: #000000;">Login</span>
				<input type="text" id="username" size = "25" autofocus onkeypress="$('#message').html('&nbsp;'); if (event.keyCode == 13) login();">

				<span style="color: #000000;">Password</span>
				<input type="password" id="password" size = "25" onkeypress="$('#message').html('&nbsp;'); if (event.keyCode == 13) login();">

				<div style="height: 25px; font-size: 1px;">&nbsp;</div>
				<input type="button" value="Login" style="width: 60px;" onclick="login()">
			</div>
			<div style="text-align: center; padding-top: 10px;">
			</div>
		</div>

		<div id="message">&nbsp;</div>
		<div style="height: 25px; font-size: 1px;">&nbsp;</div>
		<!--div class="footer">
			Copyright &copy;<a href="javascript:void(0)" target="_blank"> Abo,<a href="javascript:void(0)" target="_blank"> Balana,<a href="javascript:void(0)" target="_blank"> Rafer</a> 2015
		</div-->
	</div>
</body>
</html>
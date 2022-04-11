
<style type="text/css">
#login-container
{
	width: 30%;
	margin: 15% auto;
}

#login-container form
{


	
	margin-top: 50px;
}
</style> 

<?php
	require "classes/User.php";
	User::login();
	
?>

<div class="container">	
	<div id="login-container">
		
		<h2 class="text-center">Login</h2>

         <?php 
				if(Session::session_IsExist('login_errer')){
					echo "<div>". Session::get_session("login_errer")."</div>";
				}else{
					Session::set_session('login_errer',null);
				}
		 ?>
		


		<form autocomplete="off" method="POST">
			<div class="form-group">
				<input required type="email" name="email" class="form-control" placeholder="Email Address">
			</div>
			<div class="form-group">
				<input required type="password" name="password" class="form-control" placeholder="Password">
			</div>
			<input type="submit" class="btn btn-primary form-control" name="login" value="Login">
			<p><a href="?register=1">register</a> if not register yet</p>
		</form>
            
	</div>

</div>
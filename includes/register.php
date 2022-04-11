
<style type="text/css">
#register-container
{
	width: 30%;
	margin: 15% auto;
}

#register-container form
{
	margin-top: 50px;
}
.uplode{
    position: relative;
    left: 2px;
    top: 17px;
    background: #fff;
    width: 133px;
    height: 30px;
    padding: 3px;
    border-radius: 5px;
}
.uplode_iput{
	margin-bottom: 10px;
    position: relative;
    left: -1px;
    top: -2px;
    cursor: pointer;
}
</style>

<?php
	require "classes/User.php";
	User::register();
?>

<div class="container">	
	<div id="register-container">
		<h2 class="text-center">Register</h2>

		<?php 
				if(Session::session_IsExist('register_errer')){
					echo "<div>" . Session::get_session("register_errer") ."</div>";
				}else{
					Session::set_session('register_errer',null);
				}
		 ?>

		<form autocomplete="off" method="POST" enctype="multipart/form-data">

			<div class="form-group">
				<input type="text" name="username" class="form-control" placeholder="Username">
			</div>
			<div class="form-group">
				<input type="email" name="email" class="form-control" placeholder="Email Address">
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="Password">
			</div>

			<div class="form-group">
				<input type="password" name="confirm_password" class="form-control" placeholder="Password Confirmation">
			</div>
            <textarea name="about" placeholder="writ something here about yourself most be between 10 and 100 litter" class="form-control" style="margin-bottom: 10px;"></textarea>
             
			 <input type="file" name="upload" class="uplode_iput"   />

			<input type="submit" class="btn btn-primary form-control" name="register" value="Register">
		</form>
		<p><a href="?login=1" style="margin-bottom: 20px;">login</a> if you already have account</p>

	</div>

</div>
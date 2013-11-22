<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/18/13
 * Time: 8:21 PM
 */
?>

<?php include "views/header.php"; ?>
<?php
	require 'functions/FormSecure.php';
	$formKey = new FormSecure();
?>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a href="/">Chat</a>
				</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<?php
							if (isset($_GET['up']) && $_GET['up'] == 'false') {
								echo '<div class="alert alert-danger">Registration failed. Please try again</div>';
							}
							if (isset($_GET['in']) && $_GET['in'] == 'false') {
								echo '<div class="alert alert-danger">Login failed. Please try again</div>';
							}

							$secret = $formKey->outputKey();
							$secret_field = "<input type='hidden' name='form_key' id='form_key' value='" . $secret . "' />";

						?>

					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 login-box text-center">
						<form role="form" method="post" action="check.php">
							<h3>Sign In</h3>

							<?php echo $secret_field; ?>

							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
								<input type="text" class="form-control" placeholder="Username" required
									   name="username"/>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input type="password" class="form-control" placeholder="Password" required
									   name="password"/>
							</div>
							<input type="hidden" name="type" value="in">
							<button type="submit" class="btn btn-labeled btn-success" name="submit">
								<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Sing in
							</button>
						</form>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 login-box text-center">
						<form role="form" method="post" action="check.php">
							<h3>Sign Up</h3>

							<?php echo $secret_field; ?>

							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
								<input type="text" class="form-control" placeholder="Username" required autofocus
									   name="username"/>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input type="password" class="form-control" placeholder="Password" required
									   name="password"/>
							</div>
							<input type="hidden" name="type" value="up">
							<button type="submit" class="btn btn-labeled btn-success" name="submit">
								<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Sing up
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include "views/footer.php"; ?>

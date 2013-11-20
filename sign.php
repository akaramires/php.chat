<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/18/13
 * Time: 8:21 PM
 */
?>

<?php include "views/header.php"; ?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Chat</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 login-box text-center">
							<form role="form" method="post" action="check.php">
								<h3>Sign in</h3>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
									<input type="text" class="form-control" placeholder="Username" required name="username" />
								</div>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
									<input type="password" class="form-control" placeholder="Password" required name="password" />
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
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
									<input type="text" class="form-control" placeholder="Username" required autofocus name="username" />
								</div>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
									<input type="password" class="form-control" placeholder="Password" required name="password" />
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

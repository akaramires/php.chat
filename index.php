<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/18/13
 * Time: 8:21 PM
 */
?>

<?php include "functions/Chat.php"; ?>
<?php include "views/header.php"; ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="glyphicon glyphicon-comment"></span> Chat
				<a href="logout.php" class="pull-right"><span class="glyphicon glyphicon-log-out"></span></a>
			</div>
			<div class="panel-body chat-msgs">
				<ul class="chat">
					<?php
					$chat = new Chat();
					$list = $chat->get_list();
					for ($i = 0; $i < count($list); $i++) {
						?>
						<li class="left clearfix">
							<div class="chat-body clearfix">
								<div class="header">
									<strong class="primary-font"><?php echo $list[$i]['username']; ?></strong>
									<small class="pull-right text-muted">
										<span class="glyphicon glyphicon-time"></span><?php echo $list[$i]['time']; ?>
									</small>
								</div>
								<p>
									<?php echo $list[$i]['body']; ?>
								</p>
							</div>
						</li>
					<?php
					}
					?>

				</ul>
			</div>
			<div class="panel-footer">
				<div class="input-group">
					<input id="input-msg" type="text" class="form-control input-sm"
						   placeholder="Type your message here..."/>
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
								Send
							</button>
                        </span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include "views/footer.php"; ?>

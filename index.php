<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/18/13
 * Time: 8:21 PM
 */
?>

<?php include "views/header.php"; ?>
<?php include "functions/Chat.php"; ?>

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
					echo $chat->get_list_html();
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
				<div class="input-group online-users">
					<?php
						$users = $chat->get_users_current();
						for($i = 0; $i < count($users); $i++) {
							echo '<span class="label label-success">'.$users[$i].'</span>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include "views/footer.php"; ?>

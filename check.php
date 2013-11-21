<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 12:31 AM
 */

session_start();

include "functions/User.php";
include "functions/Chat.php";

if (!empty($_POST['type'])) {

	switch ($_POST['type']) {

		case "in":
			$User = new User($_POST['username'], $_POST['password']);

			if ($user = $User->login()) {
				$_SESSION['username'] = $_POST['username'];
				header('Location: /');
			} else {
				header('Location: /sign.php?in=false');
			}

			break;

		case "up":
			$User = new User($_POST['username'], $_POST['password']);

			if ($user = $User->register()) {
				$_SESSION['username'] = $_POST['username'];
				header('Location: /');
			} else {
				header('Location: /sign.php?up=false');
			}

			break;

		case "msg":
			$chat = new Chat();

			echo !$chat->insert_msg($_POST['text'], $_SESSION['username']);

			break;

		case "all":
			$chat = new Chat();

			echo $chat->get_list_html();

			break;
	}

}
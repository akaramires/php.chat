<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 12:31 AM
 */

session_start();

include "functions/User.php";
include "functions/Chat.php";
include "functions/FormSecure.php";

$formKey = new FormSecure();

if (!empty($_POST['type'])) {

	switch ($_POST['type']) {

		case "in":
			$User = new User($_POST['username'], $_POST['password']);

			if ($user = $User->login() && $formKey->validate()) {
				$_SESSION['username'] = $_POST['username'];
				header('Location: /');
			} else {
				header('Location: /sign.php?in=false');
			}

			break;

		case "up":
			$User = new User($_POST['username'], $_POST['password']);

			if ($user = $User->register() && $formKey->validate()) {
				$_SESSION['username'] = $_POST['username'];
				header('Location: /');
			} else {
				header('Location: /sign.php?up=false');
			}

			break;

		case "msg":
			$chat = new Chat();

			echo !$chat->insert_msg(strip_tags(urldecode($_POST['text'])), $_SESSION['username']);

			break;

		case "all":
			$chat = new Chat();

			$info = array();
			$info['msg'] = $chat->get_list_html();
			$info['users'] = $chat->get_users_current();
			echo json_encode($info);

			break;
	}

}
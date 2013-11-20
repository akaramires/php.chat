<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 12:07 AM
 */

session_start();
$curFile = basename($_SERVER['SCRIPT_FILENAME']);
if (empty($_SESSION['username']) && $curFile != 'sign.php') 
	header( 'Location: /sign.php' );
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Chat</title>

	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="../css/init.css" rel="stylesheet">
</head>

<body>
<div class="container">

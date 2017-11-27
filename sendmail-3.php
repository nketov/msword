<?php
$name    = $_POST["name-2"];
$phone = $_POST["mobile-2"];
$emailfrom = "info@grey-fox.ru";
$charset = "utf-8";
$formtype = "html";
	$subject = "Заявка на обратный звонок";
	$message = '
	<html>
		<head>
			<title>Заявка на обратный звонок</title>
		</head>
		<body>
			<p>Имя: '.$name.'</p>
			<p>Телефон: '.$phone.'</p>
		</body>
	</html>';

$headers = "Return-Path: <".$emailfrom.">\r\n";
$headers .= "From: ".$name." <".$emailfrom.">\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "Reply-To: ".$name." <".$emailfrom.">\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/" . $formtype . "; charset=\"" . $charset . "\"\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$email_to.="ab@grey-fox.ru, stgevorkov@gmail.com";
mail($email_to, $subject, $message, $headers); 
?>

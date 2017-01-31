<?php
$from = "test@htmlclass.com";
$to = "halsoi@mail.ru";
$user = Trim(stripslashes($_POST['user']));
$phone = Trim(stripslashes($_POST['phone']));
$textletter = Trim(stripslashes($_POST['textletter']));

$subject ="Отправка письма с сайта ".$_SERVER['SERVER_NAME'];
$body ="";
$body .="Имя: ";
$body .=$user;
$body .="\n";
$body .="Телефон: ";
$body .=$phone;
$body .="\n";
$body .="Сообщение: ";
$body .=$textletter;
$body .="\n";

$go = mail($to, $subject, $body, "From:<$from>");
if($go){
	print("Success!");
}
else{
	print("Unable to send!!");
}
?>
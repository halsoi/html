<?php

$mailto = "readdness@mail.ru";

if(count($_POST))
{
	//user_info
	//order_info
	//fio
	//phone
	//mail
	//adress
	//upfile
	$title = "Заявка с сайта ".$_SERVER['SERVER_NAME'];
	$msg = "";

	if(strlen($_POST['callback_fio']))        { $msg .= "<b>Имя / Компания: </b>".$_POST['callback_fio']."<br>"; unset($_POST['callback_fio']); }
if(strlen($_POST['callback_phone']))      { $msg .= "<b>Телефон (обратный звонок): </b>".$_POST['callback_phone']."<br>"; unset($_POST['callback_phone']); }
if(strlen($_POST['name']))        { $msg .= "<b>Имя / Компания: </b>".$_POST['name']."<br>"; unset($_POST['name']); }
if(strlen($_POST['phone']))      { $msg .= "<b>Телефон: </b>".$_POST['phone']."<br>"; unset($_POST['phone']); }
if(strlen($_POST['mail']))       { $msg .= "<b>Почта: </b>".$_POST['mail']."<br>"; unset($_POST['mail']); }
if(strlen($_POST['que']))       { $msg .= "<b>Вопрос: </b>".$_POST['que']."<br>"; unset($_POST['que']); }
if(strlen($_POST['adress']))     { $msg .= "<b>Адрес: </b>".$_POST['adress']."<br>"; unset($_POST['adress']); }
if(strlen($_POST['user_info']))  { $msg .= "<b>Ключевой запрос: </b>".$_POST['user_info']."<br>"; unset($_POST['user_info']); }
if(strlen($_POST['order_info'])) { $msg .= "<b>Информация о заказе: </b>".$_POST['order_info']."<br>"; unset($_POST['order_info']); }
if(strlen($_POST['refer'])) { $msg .= "<b>Источник: </b>".$_POST['refer']."<br>"; unset($_POST['refer']); }
if(strlen($_POST['utm_source'])) { $msg .= "<b>Источник: </b>".$_POST['utm_source']."<br>"; unset($_POST['utm_source']); }
if(strlen($_POST['utm_medium'])) { $msg .= "<b>Идентификатор канала: </b>".$_POST['utm_medium']."<br>"; unset($_POST['utm_medium']); }
if(strlen($_POST['utm_campaign'])) { $msg .= "<b>Название кампании: </b>".$_POST['utm_campaign']."<br>"; unset($_POST['utm_campaign']); }
if(strlen($_POST['utm_content'])) { $msg .= "<b>Объявление: </b>".$_POST['utm_content']."<br>"; unset($_POST['utm_content']); }
if(strlen($_POST['utm_term'])) { $msg .= "<b>Ключевое слово: </b>".$_POST['utm_term']."<br>"; unset($_POST['utm_term']); }

//Параметры для Яндекс.Директ
if(strlen($_POST['type'])) { $msg .= "<b>Тип площадки, на которой произведён показ объявления (search – поисковая площадка, context – тематическая): </b>".$_POST['type']."<br>"; unset($_POST['type']); }
if(strlen($_POST['source'])) { $msg .= "<b>Название площадки РСЯ (домен площадки — при показе на сайте РСЯ, none — при показе на поиске Яндекса): </b>".$_POST['source']."<br>"; unset($_POST['source']); }
if(strlen($_POST['added'])) { $msg .= "<b>Инициирован ли этот показ «дополнительными релевантными фразами»: </b>".$_POST['added']."<br>"; unset($_POST['added']); }
if(strlen($_POST['block'])) { $msg .= "<b>Тип блока, если показ произошёл на странице с результатами поиска Яндекса (premium – спецразмещение, other – блок внизу, none – блок не на поиске Яндекса): </b>".$_POST['block']."<br>"; unset($_POST['block']); }
if(strlen($_POST['pos'])) { $msg .= "<b>Точная позиция объявления в блоке (номер позиции в блоке, 0 – если объявление было показано на тематической площадке РСЯ): </b>".$_POST['pos']."<br>"; unset($_POST['pos']); }
if(strlen($_POST['key'])) { $msg .= "<b>Ключевое слово: </b>".$_POST['key']."<br>"; unset($_POST['key']); }
if(strlen($_POST['campaign'])) { $msg .= "<b>Номер (ID) рекламной кампании: </b>".$_POST['campaign']."<br>"; unset($_POST['campaign']); }
if(strlen($_POST['ad'])) { $msg .= "<b>Номер (ID) объявления: </b>".$_POST['ad']."<br>"; unset($_POST['ad']); }
if(strlen($_POST['phrase'])) { $msg .= "<b>Номер (ID) ключевой фразы: </b>".$_POST['phrase']."<br>"; unset($_POST['phrase']); }


$msg .= "<br><br>";
foreach($_POST as $key => $val)
{
if(strlen($val) > 0)
$msg .= "<b>".$key.": </b>".$val."<br>";
}

if(strlen($msg)>0)
{
reset($_FILES);
$kfile = key($_FILES);
if(strlen($_FILES[$kfile]['tmp_name'])>0) {
MailTo($title, $msg, true);
}
else {
MailTo($title, $msg);
}
}
}
//Время хранения куков
$storage = 12;
$dta = getdate(time());
if($dta['mon']>11-$storage) { $dta['mon'] -= (11-$storage); $dta['year'] += 1; }
else { $dta['mon'] += $storage; }
$dts = mktime(0,0,0,$dta['mon'],$dta['mday'],$dta['year']);

$note = "submit";

setcookie("form", $note, $dts);
$url = explode("?", $_SERVER['HTTP_REFERER']);
header("Location: ".$url[0]."?send=1");


function MailTo($title, $msg, $multipart=false)
{
global $mailto;

$error = "";
$mailfrom = "INFO@".$_SERVER['SERVER_NAME'];

if($multipart)
{ //Для очень больших писем или с прикрепленными файлами
reset($_FILES);
$kfile = key($_FILES);
if(!empty($_FILES[$kfile]['tmp_name']))
{
// Закачиваем файл
$path = 'files/'.$_FILES[$kfile]['name'];
if((strpos($path,".exe")>0) || (strpos($path,".bat")>0)) { $error = "Недопустимое формат файла."; }
else { copy($_FILES[$kfile]['tmp_name'], $path); }
}
if($path)
{
$fp = fopen($path,"rb");
if (!$fp) { $error = "Не удалось загрузить файл"; }
else { $file = fread($fp, filesize($path)); }
fclose($fp);
}

$EOL = "\r\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём

$name = htmlspecialchars($_FILES[$kfile]['name']);
$name = "=?utf-8?B?" . base64_encode($_FILES[$kfile]['name']) . "?=";
$from = "=?utf-8?B?" . base64_encode($mailfrom) . "?=";
$subject = "=?utf-8?B?" . base64_encode($title) . "?=";
$boundary = "--".md5(uniqid(time()));  // рандомная строка-разделитель

$header  = "MIME-Version: 1.0;$EOL";
$header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
$header .= "From: ".$from." \r\n";
$header .= "Subject: ".$subject." \r\n";

$multipart  = "--$boundary$EOL";
$multipart .= "Content-Type: text/html; charset=utf-8$EOL";
$multipart .= "Content-Transfer-Encoding: base64$EOL";
$multipart .= $EOL; // раздел между заголовками и телом html-части
$multipart .= chunk_split(base64_encode($msg));

$multipart .=  "$EOL--$boundary$EOL";
$multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
$multipart .= "Content-Transfer-Encoding: base64$EOL";
$multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
$multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла
$multipart .= chunk_split(base64_encode($file));

$multipart .= "$EOL--$boundary--$EOL";

mail($mailto, $subject, $multipart, $header);
}
else
{
$subject = "=?UTF-8?B?" . base64_encode(trim($title)) . "?="; //Разобраться

//с msg ничего не делать

$header="Content-type: text/html; charset=\"utf-8\" \r\n";
$header.="From: ".$mailfrom." \r\n";
$header.="Subject: ".$title." \r\n";
$header.="Content-type: text/html; charset=\"utf-8\" \r\n";

mail($mailto, $title, $msg, $header);
}

return $error;
}

header('Location: /tp.html');
exit;

?>
<?php

//$mail="mailbox@eteryalliance.ru";
//$telefon = 123;
//$mob = 321;
//$mess = "Имя: "."1"."<BR>";
//$mess .= "Организация: "."2"."<BR>";
//$mess .= "Адрес: "."3"."<BR>";
//$mess .= "Способ оплаты: "."4"."<BR>";
//$mess .= "e-mail: ".$mail."<BR>";
//$mess .= "Домашний телефон: ".$telefon."<BR>";
//$mess .= "Мобильный телефон: ".$mob."<BR>";
//$mess .= "Дополнительная информация: ".$dop."<BR><BR><BR>";
//$header = "Content-Type: text/plain; charset=windows-1251\r\n";
//$header .= "From: ".$mail."\r\n";
//
//if(mail("nketov@bigmir.net", "Письмо от  - ".$mail, $mess, $header))
//    echo "OK";
//else
//    echo ":(";


function mailWithFile($emailAddress, $subject, $text, $mailFile)
{
    $file = fopen($mailFile, "r");

    if (!$file) {
        print "Файл $mailFile не может быть прочитан";
        exit();
    }

    $file = fread($file, filesize($mailFile));

    $boundary = "--" . md5(uniqid(time()));
    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    $multipart = "--$boundary\n";
    $kod = 'utf-8';
    $multipart .= "Content-Type: text/html; charset=$kod\n";
    $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
    $multipart .= "$text\n\n";

    $message_part = "--$boundary\n";
    $message_part .= "Content-Type: application/octet-stream\n";
    $message_part .= "Content-Transfer-Encoding: base64\n";
    $message_part .= "Content-Disposition: attachment; filename = \"" . $mailFile . "\"\n\n";
    $message_part .= chunk_split(base64_encode($file)) . "\n";
    $multipart .= $message_part . "--$boundary--\n";

    if (!mail($emailAddress, $subject, $multipart, $headers)) {
        echo "К сожалению, письмо не отправлено";
        exit();
    }
    return true;
}


mailWithFile("nketov@bigmir.net", "Тест", "фаил", "result/pattern.docx");

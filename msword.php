<?php
//(Отказ действует в срок до "___"__________  ____ г.)
//Указываем путь до подготовленного документа


$patternFile = 'pattern/pattern.docx';

$docxFile = 'mails/'.$_POST['contractID'].'.docx';




if (file_exists($docxFile)) unlink($docxFile);

if (!copy($patternFile, $docxFile)) {
    echo "не удалось скопировать $file...\n";
}





$Month_r = array(
    "01" => "января",
    "02" => "февраля",
    "03" => "марта",
    "04" => "апреля",
    "05" => "мая",
    "06" => "июня",
    "07" => "июля",
    "08" => "августа",
    "09" => "сентября",
    "10" => "октября",
    "11" => "ноября",
    "12" => "декабря");




$date=explode('/',$_POST['date']);
$date1=explode('/',$_POST['date1']);
$denial=explode('/',$_POST['denial']);


 
//Список параметров
$params = array(

    '{{CREDITOR}}'    => $_POST['creditor'] ? $_POST['creditor'] : " " ,
    '{{CREDITOR_ADRESS}}'    => $_POST['creditorAdress']? $_POST['creditorAdress'] : " " ,

    '{{DEBITOR}}'    => $_POST['debitor']? $_POST['debitor'] : " " ,
    '{{DEBITOR_ADRESS}}'    => $_POST['debitorAdress']? $_POST['debitorAdress'] : " " ,
    '{{PHONE}}'    => $_POST['phone']? $_POST['phone'] : " " ,
    '{{EMAIL}}'    => $_POST['emailAddress']? $_POST['emailAddress'] : " " ,
    '{{NUMBER}}'    => $_POST['contractID']? $_POST['contractID'] : " " ,

    '{D}'    => $date[0] ,
    '{MONTH}'    => "_".$Month_r[$date[1]]."_" ,
    '{YEAR}'    => $date[2],

    '{D1}'    => $date1[0] ,
    '{MONTH1}'    => "_".$Month_r[$date1[1]]."_" ,
    '{YEAR1}'    => $date1[2],

    '{{DENIAL}}'    => ($_POST['ch']==="on") ? '(Отказ действует в срок до '.$denial[0] .' '.$Month_r[$denial[1]].' '. $denial[2].'г.)' : " " ,






);
 
if (!file_exists($docxFile)) {
    die('File not found.');
}
 
$zip = new ZipArchive();
 
if (!$zip->open($docxFile)) {
    die('File not open.');
}
 
$documentXml = $zip->getFromName('word/document.xml');
 
//Заменяем все найденные переменные в файле на значения
$documentXml = str_replace(array_keys($params), array_values($params), $documentXml);
 
$zip->deleteName('word/document.xml');
$zip->addFromString('word/document.xml', $documentXml);
 
//Закрываем и сохраняем архив
$zip->close();
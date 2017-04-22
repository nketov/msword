<?php
//(Отказ действует в срок до "___"__________  ____ г.)
//Указываем путь до подготовленного документа
$patternFile = 'pattern/pattern.docx';
$name='Александр Валентинович';
$docxFile = 'mails/'.$name.'.docx';


if (!copy($patternFile, $docxFile)) {
    echo "не удалось скопировать $file...\n";
}


 
//Список параметров
$params = array(
    '{{NAME1}}'    => $name."111"."Турчинов",
    '{{NAME2}}'    => $name."222"."Турчинов",
    '{{NAME3}}'    => $name."333"."Турчинов",
    '{{NAME4}}'    => $name."444"."Турчинов",
    '{{NAME5}}'    => $name."555"."Турчинов",

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
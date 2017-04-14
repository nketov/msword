<?php
//Указываем путь до подготовленного документа
$patternFile = 'pattern/pattern.docx';
$name='Александр Валентинович';
$docxFile = 'result/'.$name.'.docx';


if (!copy($patternFile, $docxFile)) {
    echo "не удалось скопировать $file...\n";
}


 
//Список параметров
$params = array(
    'NAME'    => $name,
    'SUR' => 'Турчинов'
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
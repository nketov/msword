<?PHP


if (strtoupper($_POST["Success"]) == true) {
    header('Content-Type: text/html; charset=utf-8');

    $text = file_get_contents('pattern/Instruction.htm');

    $tranzaction = $_POST["OrderId"];


    $docxFile = 'pattern/mails/' . $tranzaction . '.docx';

    $email_file=fopen("pattern/mails/" . $tranzaction . ".eml","r");
    $email = fgets($email_file);
    $description = fgets($email_file);
    fclose($email_file);

  
  
        //mail@collectoramnet.ru
    if (mailWithFile("nketov@bigmir.net", $description, $description, 'pattern/mails/'.$tranzaction.'.docx')) {
        //ab@grey-fox.ru
        mailWithFile("nketov@bigmir.net", $description, $description, 'pattern/mails/'.$tranzaction.'.docx');

        echo "OK";

        mailWithFile($email,  'Заявление на отключение коллекторов', $text, 'pattern/mails/'.$tranzaction.'.docx');

        unlink("pattern/mails/" . $tranzaction . ".docx");
        unlink("pattern/mails/" . $tranzaction . ".eml");


    }
}


if ($_GET['contractID']) {

    $tr=md5($_GET['contractID'].time());
    makeDocx($tr);

    ?>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff.js"></script>


    </head>


    <form id="PayForm"  style="display: none" name="TinkoffPayForm" onsubmit="pay(this); return false;">
        <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="1503653019618DEMO">
        <input class="tinkoffPayRow" type="hidden" name="frame" value="true">
        <input class="tinkoffPayRow" type="hidden" name="language" value="ru">
        <input class="tinkoffPayRow" type="text" placeholder="Сумма заказа" name="amount" value="400.00" >
        <input class="tinkoffPayRow" type="text" placeholder="Номер заказа" name="order" value="<?php echo $tr ?>">
        <input class="tinkoffPayRow" type="text" placeholder="Описание заказа" name="description" value="<?php echo $_GET['contractID'] ?>">
        <input class="tinkoffPayRow" type="text" placeholder="ФИО плательщика" name="name" value="<?php echo $_GET['creditor']; ?>">
        <input class="tinkoffPayRow" type="text" placeholder="E-mail" name="email" value="<?php echo $_GET['emailAddress']; ?>">
        <input class="tinkoffPayRow" type="text" placeholder="Контактный телефон" name="phone" value="<?php echo str_replace(array(' ','(',')','-'),array('','','',''),$_GET['phone']) ?>">
        <input class="tinkoffPayRow" type="submit" value="Оплатить">
    </form>



    <script>
        $('#PayForm').submit();
    </script>


    <?php
}


function mailWithFile($emailAddress, $subject, $text, $mailFile)
{
    $file = fopen($mailFile, "r");

    if (!$file) {
        print "Файл $mailFile не может быть прочитан";
        exit();
    }

    $file = fread($file, filesize($mailFile));
    $mailFile = 'Заявление на отключение коллекторов.docx';

    $boundary = "--" . md5(uniqid(time()));
    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    $headers  .= "From: ЦЕНТР ПРАВОВОЙ ПОДДЕРЖКИ ЗАЕМЩИКОВ <mail@collectoramnet.ru> \r\n";
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

    mail($emailAddress, $subject, $multipart, $headers);

    return true;
}


function makeDocx($t)
{


    $patternFile = 'pattern/pattern.docx';

    $docxFile = 'pattern/mails/' . $t . '.docx';
    $emailFile = 'pattern/mails/' . $t. '.eml';

    if (!file_exists('pattern/mails/')) mkdir('pattern/mails/', 0755, true);
    if (file_exists($docxFile)) unlink($docxFile);
    if (file_exists($emailFile)) unlink($emailFile);

    file_put_contents($emailFile, $_GET['emailAddress']."\n".$_GET['contractID']);

    copy($patternFile, $docxFile);

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


    $date = explode('/', $_GET['date']);
    $date1 = explode('/', $_GET['date1']);
    $denial = explode('/', $_GET['denial']);


    if(empty($date1[0])){
        $date1[0]=date('d');
        $date1[1]=date('m');
        $date1[2]=date('Y');
    }
        

    $params = array(

        '{{CREDITOR}}' =>stripcslashes($_GET['creditor']),
        '{{CREDITOR_ADRESS}}' => stripcslashes($_GET['creditorAdress']),

        '{{DEBITOR}}' => stripcslashes($_GET['debitor']),
        '{{DEBITOR_ADRESS}}' => stripcslashes($_GET['debitorAdress']),
        '{{PHONE}}' => $_GET['phone'],
        '{{EMAIL}}' => $_GET['emailAddress'] ,
        '{{NUMBER}}' => $_GET['contractID'],

        '{D}' => $date[0],
        '{MONTH}' =>  $Month_r[$date[1]] ,
        '{YEAR}' => $date[2],
        
        
      
        '{D1}' => $date1[0],
        '{MONTH1}' =>  $Month_r[$date1[1]] ,
        '{YEAR1}' => $date1[2],

        '{{DENIAL}}' => !empty($denial[0]) ? '(Отказ действует в срок до ' . $denial[0] . ' ' . $Month_r[$denial[1]] . ' ' . $denial[2] . 'г.)' : "",

    );


    $zip = new ZipArchive();

    $zip->open($docxFile);

    $documentXml = $zip->getFromName('word/document.xml');

    $documentXml = str_replace(array_keys($params), array_values($params), $documentXml);

    $zip->deleteName('word/document.xml');
    $zip->addFromString('word/document.xml', $documentXml);

    $zip->close();

    return true;

}


?>
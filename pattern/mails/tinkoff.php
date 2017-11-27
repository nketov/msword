<?PHP


if (strtoupper($_POST["Success"]) == true) {
    header('Content-Type: text/html; charset=utf-8');

    $text = file_get_contents('pattern/Instruction.htm');

    $tranzaction = $_POST["WMI_PAYMENT_NO"];
    $description =$_POST["WMI_DESCRIPTION"];

    $docxFile = 'pattern/mails/' . $tranzaction . '.docx';
    $email = file_get_contents("pattern/mails/" . $tranzaction . ".eml");

  
  
        //mail@collectoramnet.ru
    if (mailWithFile("nketov@bigmir.net", $description, $description, 'pattern/mails/'.$tranzaction.'.docx')) {
        //ab@grey-fox.ru
        mailWithFile("nketov@bigmir.net", $description, $description, 'pattern/mails/'.$tranzaction.'.docx');

        echo "WMI_RESULT=OK";
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
























<!--        <script type="text/javascript">-->
<!--            function makePayment(amount, orderId, description, name, email, phone) {-->
<!--                var params = {-->
<!--                    //Код магазина (обязательный параметр), выдается банком.-->
<!--                    TerminalKey: "1503653019618DEMO",-->
<!--                    //Сумма заказа в копейках (обязательный параметр)-->
<!--                    Amount: amount,-->
<!--                    //Номер заказа (если не передан, принудительно устанавливается timestamp)-->
<!--                    OrderId: orderId,-->
<!--                    //Описание заказа (не обязательный параметр)-->
<!--                    Description: description,-->
<!--                    //Дополнительные параметры платежа-->
<!--                    DATA: "Email=" + email + "|Phone=" + phone + "|Name=" + name,-->
<!--                    //Флаг открытия платежной формы во фрейме: false - в отдельном окне, true - в текущем окне.-->
<!--                    Frame: true,-->
<!--                    //Язык формы оплаты: ru - русский язык, en - английский язык-->
<!--                    Language: "ru"-->
<!--                };-->
<!--                doPay(params);-->
<!--            }-->
<!--        </script>-->

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
//        makePayment(2 * 100,
//               <?php //echo $_GET['contractID'] ?>//,
//            <?php //echo $_GET['debitor'] ?>//,
//            <?php //echo $_GET['emailAddress'] ?>//,
//            <?php //echo str_replace(array(' ','(',')','-'),array('','','',''),$_GET['phone']) ?>//)"
    </script>


<!--    <form id="formW" method="post" action="https://wl.walletone.com/checkout/checkout/Index">-->
<!--        <input name="WMI_MERCHANT_ID" hidden value="196139710250"/>-->
<!--        <input name="WMI_PAYMENT_AMOUNT" hidden value="400.00"/>-->
<!--        <input name="WMI_CURRENCY_ID" hidden value="643"/>-->
<!--        <input name="WMI_CUSTOMER_PHONE" hidden value="--><?php //echo str_replace(array(' ','(',')','-'),array('','','',''),$_GET['phone']) ?><!--"/>-->
<!--        <input name="WMI_CUSTOMER_EMAIL" hidden value="--><?php //echo $_GET['emailAddress']; ?><!--"/>-->
<!--        <input name="WMI_DESCRIPTION" hidden value="--><?php //echo $_GET['contractID']; ?><!--"/>-->
<!--        <input name="WMI_PAYMENT_NO" hidden value="--><?php //echo $tr ?><!--"/>-->
<!--        <input name="WMI_SUCCESS_URL" hidden value="http://collectoramnet.ru/?action=paid"/>-->
<!--        <input name="WMI_FAIL_URL" hidden value="http://collectoramnet.ru"/>-->
<!---->
<!--    </form>-->




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

    file_put_contents($emailFile, $_GET['emailAddress']);

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
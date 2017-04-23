<?PHP


if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED") {


$contractNumber=$_POST["WMI_PAYMENT_NO"];


    if (mailWithFile("ketovnv@gmail.com", $contractNumber, $_POST["WMI_PAYMENT_NO"], "mails/".$_POST["WMI_PAYMENT_NO"].".docx")) {
        echo "WMI_RESULT=OK";
        mail(file_get_contents("mails/".$_POST["WMI_PAYMENT_NO"].".eml"), "Оплачено", "Оплачено");

    }
} else {

    if ($_GET['contractID']) {

        makeDocx();

        ?>
        <head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        </head>
        <form id="formW" method="post" action="https://wl.walletone.com/checkout/checkout/Index">
            <input name="WMI_MERCHANT_ID" hidden value="196139710250"/>
            <input name="WMI_PAYMENT_AMOUNT" hidden value="2.00"/>
            <input name="WMI_CURRENCY_ID" hidden value="643"/>
            <input name="WMI_DESCRIPTION" hidden value="Оплата по договору № <?php echo $_GET['contractID'];?>"/>
            <input name="WMI_PAYMENT_NO" hidden value="<?php echo $_GET['contractID'];?>"/>
            <input name="WMI_SUCCESS_URL" hidden value="http://eteryalliance.ru"/>
            <input name="WMI_FAIL_URL" hidden value="http://eteryalliance.ru"/>

        </form>


        <script>

            $("#formW").submit();
        </script>

        <?php
    }
}

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

    mail($emailAddress, $subject, $multipart, $headers);
  
    return true;
}


function makeDocx()
{


//foreach ($_GET as $get)
//{
//    echo iconv("UTF-8","windows-1251",$get);
//    echo '<br>';
//    echo mb_detect_encoding($get);
//    echo '<br>';}
//    exit;





    $patternFile = 'pattern/pattern.docx';

    $docxFile = 'mails/' . $_GET['contractID'] . '.docx';
    $emailFile = 'mails/' . $_GET['contractID'] . '.eml';


    if (!file_exists('mails')) mkdir('mails', 0755, true);
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


    $params = array(

        '{{CREDITOR}}' => $_GET['creditor'] ? $_GET['creditor'] : " ",
        '{{CREDITOR_ADRESS}}' => $_GET['creditorAdress'] ? $_GET['creditorAdress'] : " ",

        '{{DEBITOR}}' => $_GET['debitor'] ? $_GET['debitor'] : " ",
        '{{DEBITOR_ADRESS}}' => $_GET['debitorAdress'] ? $_GET['debitorAdress'] : " ",
        '{{PHONE}}' => $_GET['phone'] ? $_GET['phone'] : " ",
        '{{EMAIL}}' => $_GET['emailAddress'] ? $_GET['emailAddress'] : " ",
        '{{NUMBER}}' => $_GET['contractID'] ? $_GET['contractID'] : " ",

        '{D}' => $date[0],
        '{MONTH}' => "_" . $Month_r[$date[1]] . "_",
        '{YEAR}' => $date[2],

        '{D1}' => $date1[0],
        '{MONTH1}' => "_" . $Month_r[$date1[1]] . "_",
        '{YEAR1}' => $date1[2],

        '{{DENIAL}}' => ($_GET['ch'] === "on") ? '(Отказ действует в срок до ' . $denial[0] . ' ' . $Month_r[$denial[1]] . ' ' . $denial[2] . 'г.)' : " ",

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
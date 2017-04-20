<?PHP
header("Content-Type: text/html; charset=utf-8");

$text = "";
if ($_POST) {

    foreach ($_POST as $key => $value) {
        $text .= " " . $key . " = " . $value . " ";
    }


    if (mailWithFile("ketovnv@gmail.com", "Тест", $text, "result/pattern.docx")) {
        echo "WMI_RESULT=OK";
    }
} else {
    ?>


    <form method="post" action="https://wl.walletone.com/checkout/checkout/Index">
        <input name="WMI_MERCHANT_ID" value="196139710250"/>
        <input name="WMI_PAYMENT_AMOUNT" value="2.00"/>
        <input name="WMI_CURRENCY_ID" hidden value="643"/>
        <input name="WMI_DESCRIPTION" value="Оплата демонстрационного заказа"/>
        <input name="WMI_SUCCESS_URL" hidden value="http://eteryalliance.ru"/>
        <input name="WMI_FAIL_URL" hidden value="http://eteryalliance.ru"/>
        <input type="submit"/>
    </form>


<?php }

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


?>
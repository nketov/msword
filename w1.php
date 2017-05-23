<?PHP

$text=file_get_contents('Instruction.htm');

mailWithFile('ketovnv@gmail.com','Тест', $text, 'wallet.php');
exit;


if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED") {

$text='ИНСТРУКЦИЯ
Здравствуйте!
Благодарим Вас за использование нашего сервиса! Мы рады Вашему обращению и возможности помочь Вам!
Заявление теперь у Вас. Наши юристы еще раз проверят его на правильность адресов, формулировок и форм — чтобы оно было безупречным. Если найдем, то вышлем Вам отредактированное заявление в течение суток (обычно до конца дня). Если не выслали — значит Ваше заявление полностью корректно.
Итак, этапы:
1.	Вы получили это письмо с Заявлением и инструкцией;
2.	Вам необходимо подписать Заявление и направить кредитору/коллектору. Мы можете сделать это следующими способами:
a.	По почте заказным письмом с уведомлением. В этом случае доказательством направления письма будет квиток-уведомление, который Вам выдадут на почте. СОХРАНИТЕ ЕГО!
b.	Лично доставив его в отделение кредитора. В этом случае Вам необходимо будет сделать 2 экземпляра Заявления и, придя к кредитору, поставить отметку о вручении ему одного экземпляра (как правило, это делает Канцелярия, проставляя печать или написав «принято, дата, подпись» на втором, Вашем экземпляре. СОХРАНИТЕ ЕГО!
В обоих случаях очень важно сохранять квиток/второй экземпляр Заявления, так как если кредитор/коллектор не прекратит свою деятельность, этот документ поможет нам надавить на коллектора. Без документа любое давление будет бессмысленным.
3.	Будьте готовы выждать 2-3 дня. На практике звонки прекращаются на следующий день, однако в отдельных случаях могут быть исключения. По прошествии 5 рабочих дней — звоните нам, мы задействуем наши юридические ресурсы. Наш телефон +7 495 532-19-44. Будьте готовы выслать на нашу почту help@collectoramnet.ru отсканированные документы: подписанное заявление и (если направляли почтой) почтовый квиток с уведомлением о вручении.
4.	Да, мы сопровождаем процессы в суде для наших клиентов. Мы начинаем от суммы в 300 000 рублей и пока работаем только в Москве. Пожалуйста, звоните нам, и мы обо всем договоримся.
';
    $contractNumber = $_POST["WMI_PAYMENT_NO"];

    $docxFile = 'pattern/mails/' . (int)$contractNumber . '.docx';
    $email = file_get_contents("pattern/mails/" . (int)$contractNumber . ".eml");
    $dir = (int)$contractNumber . '/';
    if (!file_exists('pattern/mails/' . $dir)) mkdir('pattern/mails/' . $dir, 0755, true);
    copy($docxFile, 'pattern/mails/' . $dir . 'Заявление на отключение коллекторов.docx');


    if (mailWithFile("ab@grey-fox.ru", '№' . $contractNumber, 'Договор №' . $contractNumber . ' оплачен', 'pattern/mails/' . $dir . 'Заявление на отключение коллекторов.docx')) {
        echo "WMI_RESULT=OK";
        mailWithFile($email, '№' . $contractNumber, 'Договор №' . $contractNumber . ' оплачен, Ваше заявление в прикреплении', 'pattern/mails/' . $dir . 'Заявление на отключение коллекторов.docx');

        unlink("pattern/mails/" . (int)$contractNumber . ".docx");
        unlink("pattern/mails/" . (int)$contractNumber . ".eml");
        unlink('pattern/mails/' . $dir . 'Заявление на отключение коллекторов.docx');
        unlink('pattern/mails/' . (int)$contractNumber);
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
            <input name="WMI_PAYMENT_AMOUNT" hidden value="3.00"/>
            <input name="WMI_CURRENCY_ID" hidden value="643"/>
            <input name="WMI_DESCRIPTION" hidden value="Оплата по договору № <?php echo $_GET['contractID']; ?>"/>
            <input name="WMI_PAYMENT_NO" hidden value="<?php echo $_GET['contractID']; ?>"/>
            <input name="WMI_SUCCESS_URL" hidden value="http://collectoramnet.ru"/>
            <input name="WMI_FAIL_URL" hidden value="http://collectoramnet.ru"/>

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
    $mailFile=end(explode('/',$mailFile));

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


    $patternFile = 'pattern/pattern.docx';

    $docxFile = 'pattern/mails/' . (int)$_GET['contractID'] . '.docx';
    $emailFile = 'pattern/mails/' . (int)$_GET['contractID'] . '.eml';

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
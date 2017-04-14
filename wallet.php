<?PHP header("Content-Type: text/html; charset=utf-8");

$text="";
if ($_POST) {

  foreach ($_POST as $key => $value) {
    $text .= " " . $key . " = " . $value . " ";
  }

  mail('nketov@bigmir.net', "Teст", $text);
  echo "WMI_RESULT=OK";
}
else{
?>



<form method="post" action="https://wl.walletone.com/checkout/checkout/Index">
  <input name="WMI_MERCHANT_ID"    value="196139710250"/>
  <input name="WMI_PAYMENT_AMOUNT" value="1.00"/>
  <input name="WMI_CURRENCY_ID" hidden    value="643"/>
  <input name="WMI_DESCRIPTION"    value="Оплата демонстрационного заказа" />
  <input name="WMI_SUCCESS_URL"  hidden   value="https://myshop.com/w1/success.php"/>
  <input name="WMI_FAIL_URL"     hidden   value="https://myshop.ru/w1/fail.php"/>
  <input type="submit"/>
</form>


<?php }?>
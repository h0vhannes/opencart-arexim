<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<payment-avail-response>
<result>
<code><? echo $code?></code> <!-- код результата: магазин может принять платеж -->
<desc><? echo $desc?></desc>
</result>
<!-- идентификатор транзакции магазина. Используется магазином для связи двух фаз в одну транзакцию. -->
<merchant-trx><? echo $merchant_trx?></merchant-trx>
<purchase>
<!-- Краткое описание покупки. Длиной не более 30 символов -->
<shortDesc><? echo $shortDesc?></shortDesc>
<!-- Развернутое описание покупки. Длиной не более 125 символов -->
<longDesc><? echo $longDesc?></longDesc>
<!-- перечень счетов, на которые магазин может принять оплату -->
<account-amount>
<!-- идентификатор счета-->
<id><? echo $account_id?></id>
<!-- сумма по счету -->
<amount><? echo $amount?></amount>
<!-- код валюты (ISO 4217)-->

<currency><? echo $currency?></currency>
<!-- экспонента валюты (ISO 4217)-->
<exponent><? echo $exponent?></exponent>
</account-amount>
</purchase>
</payment-avail-response>
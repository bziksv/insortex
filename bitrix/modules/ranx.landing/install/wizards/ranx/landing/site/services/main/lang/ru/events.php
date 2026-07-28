<?php
$MESS['EVENT_NAME'] = 'Новое сообщение с формы';
$MESS['EVENT_DESCRIPTION'] = "#FORM_NAME# - Заголовок формы\n#FORM_DATA# - Данные с формы";
$MESS['EVENT_MESSAGE_SUBJECT'] = 'Новое сообщение с лендинга на сайте - #SITE_NAME#';
$MESS['EVENT_MESSAGE_MESSAGE'] = "На сайте #SERVER_NAME# было получено новое сообщение с формы #FORM_NAME#.<br><br>Данные:<br>#FORM_DATA#";

$MESS['SALE_ORDER_EVENT_NAME'] = 'Новый заказ на сайте';
$MESS['SALE_ORDER_EVENT_DESCRIPTION'] = "#NAME# - Имя\n#PHONE# - Телефон\n#EMAIL# - E-mail\n#COMPANY# - Название компании\n#COMMENT# - Комментарий к заказу\n#DELIVERY# - Способ доставки\n#ADDRESS# - Адрес доставки\n#DELIVERY_SUM# - Стомиость доставки\n#PRODUCTS# - Состав заказа\n#TOTAL# - Итого";
$MESS['SALE_ORDER_EVENT_MESSAGE_SUBJECT'] = 'Новый заказ на сайте - #SITE_NAME#';
$MESS['SALE_ORDER_EVENT_MESSAGE_MESSAGE'] = "На сайте #SERVER_NAME# был создан новый заказ.<br><br>
Имя: #NAME#<br>
Телефон: #PHONE#<br>
E-mail: #EMAIL#<br>
Название компании: #COMPANY#<br>
Комментарий к заказу: #COMMENT#<br>
Способ доставки: #DELIVERY#<br>
Адрес доставки: #ADDRESS#<br>
Стоимость доставки: #DELIVERY_SUM#<br>
Состав заказа: #PRODUCTS#<br>
<b>Итого: #TOTAL#</b><br>
<br>
Просмотр результата на сайте: <a href='http://#SERVER_NAME#/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=ranx_landing&lang=ru&ID=#ELEMENT_ID#&find_section_section=0&WF=Y' target='_blank'>http://#SERVER_NAME#/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=ranx_landing&lang=ru&ID=#ELEMENT_ID#&find_section_section=0&WF=Y</a>
";

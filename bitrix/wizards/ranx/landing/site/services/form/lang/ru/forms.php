<?php
// callback
$MESS['CALLBACK_EVENT_NAME'] = 'Новое сообщение с формы Заказать звонок';
$MESS['CALLBACK_EVENT_DESCRIPTION'] = "#NAME# - Имя\n#PHONE# - Телефон";
$MESS['CALLBACK_EVENT_MESSAGE_SUBJECT'] = 'Новая заявка на звонок с сайта - #SITE_NAME#';
$MESS['CALLBACK_EVENT_MESSAGE_MESSAGE'] = "На сайте #SERVER_NAME# было получено новое сообщение с формы \"Заказать звонок\".<br><br>
Имя: #NAME#<br>
Телефон: #PHONE#<br><br>
Запрос отправлен: #RS_DATE_CREATE#<br>
Просмотр результата на сайте: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#' target='_blank'>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
$MESS['CALLBACK_FORM_NAME'] = 'Заказать звонок';
$MESS['CALLBACK_BUTTON_NAME'] = 'Заказать';
$MESS['CALLBACK_FORM_DESCRIPTION'] = '';
$MESS['CALLBACK_FORM_QUESTION_1'] = 'Ваше имя';
$MESS['CALLBACK_FORM_QUESTION_2'] = 'Телефон';
$MESS['CALLBACK_FORM_QUESTION_3'] = 'Источник';

// order
$MESS['ORDER_EVENT_NAME'] = 'Новое сообщение с формы Оставить заявку';
$MESS['ORDER_EVENT_DESCRIPTION'] = "#NAME# - Имя\n#PHONE# - Телефон\n#EMAIL# - E-mail\n#SUBJECT# - Тема сообщения\n#QUESTION# - Ваш вопрос";
$MESS['ORDER_EVENT_MESSAGE_SUBJECT'] = 'Новая заявка с сайта - #SITE_NAME#';
$MESS['ORDER_EVENT_MESSAGE_MESSAGE'] = "На сайте #SERVER_NAME# было получено новое сообщение с формы \"Оставить заявку\".<br><br>
Имя: #NAME#<br>
Телефон: #PHONE#<br>
E-mail: #EMAIL#<br>
Тема сообщения: #SUBJECT#<br>
Вопрос: #QUESTION#<br><br>
Запрос отправлен: #RS_DATE_CREATE#<br>
Просмотр результата на сайте: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#' target='_blank'>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
$MESS['ORDER_FORM_NAME'] = 'Оставить заявку';
$MESS['ORDER_BUTTON_NAME'] = 'Отправить';
$MESS['ORDER_FORM_DESCRIPTION'] = '';
$MESS['ORDER_FORM_QUESTION_1'] = 'Ваше имя';
$MESS['ORDER_FORM_QUESTION_2'] = 'Телефон';
$MESS['ORDER_FORM_QUESTION_3'] = 'E-mail';
$MESS['ORDER_FORM_QUESTION_4'] = 'Тема сообщения';
$MESS['ORDER_FORM_QUESTION_5'] = 'Ваш вопрос';
$MESS['ORDER_FORM_QUESTION_6'] = 'Источник';

// sale_order
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
Запрос отправлен: #RS_DATE_CREATE#<br>
Просмотр результата на сайте: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#' target='_blank'>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
$MESS['SALE_ORDER_FORM_NAME'] = 'Заказы';
$MESS['SALE_ORDER_BUTTON_NAME'] = 'Отправить';
$MESS['SALE_ORDER_FORM_DESCRIPTION'] = '';
$MESS['SALE_ORDER_FORM_QUESTION_1'] = 'Ваше имя';
$MESS['SALE_ORDER_FORM_QUESTION_2'] = 'Телефон';
$MESS['SALE_ORDER_FORM_QUESTION_3'] = 'E-mail';
$MESS['SALE_ORDER_FORM_QUESTION_4'] = 'Название компании';
$MESS['SALE_ORDER_FORM_QUESTION_5'] = 'Комментарий к заказу';
$MESS['SALE_ORDER_FORM_QUESTION_6'] = 'Способ доставки';
$MESS['SALE_ORDER_FORM_QUESTION_7'] = 'Адрес доставки';
$MESS['SALE_ORDER_FORM_QUESTION_8'] = 'Стоимость доставки';
$MESS['SALE_ORDER_FORM_QUESTION_9'] = 'Состав заказа';
$MESS['SALE_ORDER_FORM_QUESTION_10'] = 'Итого';

// service form
$MESS['SERVICE_EVENT_NAME'] = 'Новое сообщение с формы Запись';
$MESS['SERVICE_EVENT_DESCRIPTION'] = "#WEEK_DAY# - День недели\n#TAB# - Вкладка\n#CATEGORY# - Для кого\n#YEARS# - Возраст\n#INTERVAL_TIME# - Время\n#PERSON_NAME# - Специалист\n#NAME# - Услуга\n#FIO# - Ваше имя\n#EMAIL# - E-mail\n#PHONE# - Телефон\n#COMMENT# - Комментарий";
$MESS['SERVICE_EVENT_MESSAGE_SUBJECT'] = 'Новая запись с сайта - #SITE_NAME#';
$MESS['SERVICE_EVENT_MESSAGE_MESSAGE'] = "На сайте #SERVER_NAME# было получено новое сообщение с формы \"Запись\"<br>
<br>
День недели: #WEEK_DAY#<br>
Вкладка: #TAB#<br>
Для кого: #CATEGORY#<br>
Возраст: #YEARS#<br>
Время: #INTERVAL_TIME#<br>
Специалист: #PERSON_NAME#<br>
Услуга: #NAME#<br>
Ваше имя: #FIO#<br>
E-mail: #EMAIL#<br>
Телефон: #PHONE#<br>
Комментарий: #COMMENT#<br>
<br>
Запрос отправлен: #RS_DATE_CREATE#<br>
Просмотр результата на сайте: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#' target='_blank'>http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#</a>";
$MESS['SERVICE_FORM_NAME'] = 'Запись';
$MESS['SERVICE_BUTTON_NAME'] = 'Записаться';
$MESS['SERVICE_FORM_DESCRIPTION'] = '';
$MESS['SERVICE_FORM_QUESTION_1'] = 'День недели';
$MESS['SERVICE_FORM_QUESTION_2'] = 'Вкладка';
$MESS['SERVICE_FORM_QUESTION_3'] = 'Для кого';
$MESS['SERVICE_FORM_QUESTION_4'] = 'Возраст';
$MESS['SERVICE_FORM_QUESTION_5'] = 'Время';
$MESS['SERVICE_FORM_QUESTION_6'] = 'Специалист';
$MESS['SERVICE_FORM_QUESTION_7'] = 'Услуга';
$MESS['SERVICE_FORM_QUESTION_8'] = 'Ваше имя';
$MESS['SERVICE_FORM_QUESTION_9'] = 'E-mail';
$MESS['SERVICE_FORM_QUESTION_10'] = 'Телефон';
$MESS['SERVICE_FORM_QUESTION_11'] = 'Комментарий';
$MESS['SERVICE_FORM_QUESTION_12'] = 'Источник';

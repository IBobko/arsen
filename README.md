# arsen

Интеграция МС и retailCRM

 
Бобко Игорь Игоревич
Вт 19.01.2016 17:56
[Черновик] Сообщение не отправлено.
Сохранено: Вт 19.01.2016 17:48

пометить как непрочитанное
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 17:36

1. Ключ к online-krasota.retailcrm.ru
DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n
 
Здесь можно менять только статус оплаты и дату оплаты. Это рабочая система! Важно не запороть!
 
2. В МойСклад https://online.moysklad.ru/ нет API-ключей. Там авторизация по логину и паролю. Можете пока сделать интеграцию на демо версии? У нас ограниченное количество пользователей в системе.
 
 
 
Бобко Игорь Игоревич
Вт 19.01.2016 17:06
Вот, это да. Самое главное. Нужен доступ к VPS и соответственно ключи управления API.
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 17:05
Скинем на Альфу. Что еще потребуется? Доступы, API-ключи.
 
Бобко Игорь Игоревич
Вт 19.01.2016 16:51
​Это Альфа-банк. А нужен сбер?
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 16:35
Это Сбер?
 
Бобко Игорь Игоревич
Вт 19.01.2016 16:33
ТЗ действительно стоящее... С ним посложнее, одному мне это быстро не сделать. Мне придется с товарищами обсудить. А деньги нужно будет отправить на 4154 8120 1305 8387
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 16:04
Игорь, куда отправлять деньги? 7000 же? Так же отправляю для оценки ТЗ по переносу online-krasota.ru с premerce на битрикс.
 
Бобко Игорь Игоревич
Вт 19.01.2016 15:54
​У вас есть VPS, гуд! А что у вас на этой VPS? Есть ли Ентерпрайз система? Я к чему клоню, обычно интеграционные системы пишутся на Java, но для этого нужны контейнеры приложений. Самый простой вариант это написать легкий скрипт на каком-нибудь php, вариант

пометить как непрочитанное
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 15:31

Выполняться должен на vps сервере beget.ru
Готовых интеграционных систем у нас таких нет. Есть скрипты интеграции сайтов с virtuemart (joomla) и retailCRM
 
 
Бобко Игорь Игоревич
Вт 19.01.2016 15:04
​Я понял, а такой момент, на какой машинке этот крон скрипт должен выполняться, и есть ли уже интеграциооные системы? Чтобы, возможно делать по стандарту старых, а не делать что-то принципиально отличное.
ОТВЕТИТЬОТВЕТИТЬ ВСЕМПЕРЕСЛАТЬ
пометить как непрочитанное
 
Роман Токтонов <rt@online-krasota.ru>
Вт 19.01.2016 14:57
Кому:
Бобко Игорь Игоревич;
Вы ответили 19.01.2016 15:04.

1. Наша CRM - RetailCRM. 
Описание API
http://www.retailcrm.ru/docs/%D0%A0%D0%B0%D0%B7%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%87%D0%B8%D0%BA%D0%B8/%D0%A0%D0%B0%D0%B7%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%87%D0%B8%D0%BA%D0%B8
2. Складская система - МойСклад.
Описание API https://support.moysklad.ru/hc/ru/articles/203404253-REST-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D1%81%D0%B8%D0%BD%D1%85%D1%80%D0%BE%D0%BD%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8-%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D1%85
Задача:
Необходимо написать cron-скрипт, который будет мониторить данные по документам "Входящий платеж" (CashIn, PaymentIn).
Ключевым полем в документе "Входящий платеж" будет являться реквизит customerOrder.id
Затем по customerOrder.id надо находить документ в CRM "Заказ" (Order) и по реквизиту Order.number. Менять статус Order.status на значение "Оплачено".
Скрипт запускается как можно чаще. Раз в 5 минут.

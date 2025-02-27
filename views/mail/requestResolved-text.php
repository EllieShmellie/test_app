<?php
/** @var $request app\models\Request */
?>
Ваш запрос №<?= $request->request_id ?> обработан

Здравствуйте, <?= $request->name ?>!

Ваш запрос был обработан нашими специалистами.

Ваш запрос:
<?= $request->message ?>

Ответ специалиста:
<?= $request->comment ?>

Спасибо за обращение!
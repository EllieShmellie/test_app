<?php
/** @var $request app\models\Request */
use yii\helpers\Html;
?>


    Ваш запрос №<?= $request->request_id ?> обработан
    Здравствуйте, <?= Html::encode($request->name) ?>!
    Ваш запрос был обработан нашими специалистами.
    
    Ваш запрос:
    
        <?= Html::encode($request->message) ?>
    
    
    Ответ специалиста:
    
        <?= Html::encode($request->comment) ?>
    
    Спасибо за обращение!

<?php

Yii::$container->set('app\services\MailService', function ($container) {
    return new app\services\MailService(
        Yii::$app->mailer,
        Yii::$app->params['adminEmail'] ?? 'noreply@example.com'
    );
});
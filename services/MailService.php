<?php
namespace app\services;

use Yii;
use yii\mail\MailerInterface;

class MailService
{
    /**
     * @var MailerInterface
     */
    protected $mailer;
    
    /**
     * @var string
     */
    protected $defaultSender;
    
    /**
     * @param MailerInterface|null $mailer
     * @param string $defaultSender
     */
    public function __construct(
        MailerInterface $mailer = null,
        $defaultSender = null
    ) {
        $this->mailer = $mailer ?: Yii::$app->mailer;
        $this->defaultSender = $defaultSender ?: Yii::$app->params['adminEmail'] ?? 'noreply@example.com';
    }
    
    /**
     * @param string $template
     * @param string $to
     * @param array $params
     * @param string $from
     * @return bool 
     */
    public function sendTemplate($template, $to, array $params = [], $from = null)
    {
        $from = $from ?: $this->defaultSender;
        $subject = $params['subject'] ?? 'Уведомление';
        try {
            $message = $this->mailer->compose(
                [
                    'html' => $template . '-html', 
                    'text' => $template . '-text'
                ],
                $params
            )
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject);
            
            $result = $message->send();
            
            if ($result) {
                Yii::info("Email '$subject' успешно отправлен на $to", 'mail');
            } else {
                Yii::warning("Не удалось отправить email '$subject' на $to", 'mail');
            }
            
            return $result;
        } catch (\Exception $e) {
            Yii::error("Ошибка при отправке email '$subject' на $to: " . $e->getMessage(), 'mail');
            return false;
        }
    }
}
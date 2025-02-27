<?php
namespace app\services;

use app\services\MailService;
use app\models\Request;

class RequestNotificationService
{
    /**
     * @var MailService
     */
    protected $mailService;
    protected $config;
    
    public function __construct(MailService $mailService, $config)
    {
        $this->mailService = $mailService;
        $this->config = $config;
    }
    
     /**
     * @param Request $request
     * @return bool
     */
    public function sendRequestResolvedNotification(Request $request): bool
    {
        $params = [
            'request' => $request,
            'subject' => 'Ответ на ваш тикет №' . $request->request_id
        ];
        
        return $this->mailService->sendTemplate('requestResolved', $request->email, $params);
    }
}
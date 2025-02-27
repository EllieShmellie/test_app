<?php
namespace app\services;

use app\models\Request;
use app\repositories\RequestRepository;

class RequestService
{
    /**
     * @var RequestNotificationService 
     */
    protected $notificationService;

    /**
     * @var RequestRepository 
     */
    protected $repository;
    
    public function __construct(RequestNotificationService $notificationService, RequestRepository $repository)
    {
        $this->notificationService = $notificationService;
        $this->repository = $repository;
    }
    
    /**
     * @param array $data
     * @return Request
     */
    public function create(array $data): Request
    {
        $request = new Request();
        $request->scenario = 'create';
        $request->attributes = $data;
        $request->status = Request::STATUS_ACTIVE;
        if ($request->validate()) {
            $request->save(false);
        }
        return $request;
    }
    
    /**
     * @param int $id
     * @param array $data
     * @return Request|null
     */
    public function update($id, array $data): ?Request
    {
        $request = Request::findOne($id);
        if (!$request) {
            return null;
        }
        
        if (isset($data['status']) && $data['status'] === Request::STATUS_RESOLVED) {
            return $this->resolveRequest($request, $data);
        }
        
        return $request;
    }

    /**
     * @param Request $request
     * @param array $data
     * @return Request
     */
    private function resolveRequest(Request $request, array $data): Request
    {
        $request->scenario = 'resolve';
        $request->status = Request::STATUS_RESOLVED;
        $request->comment = $data['comment'] ?? null;
        
        if (!$request->comment) {
            $request->addError('comment', 'Комментарий обязателен для закрытия тикета');
            return $request;
        }
        
        if ($request->validate() && $request->save()) {
            $this->notificationService->sendRequestResolvedNotification($request);
        }
        
        return $request;
    }
    
    /**
     *
     * @param array $filters
     * @return Request[]
     */
    public function get(array $filters = [])
    {
        return $this->repository->getRequest($filters);
    }
}

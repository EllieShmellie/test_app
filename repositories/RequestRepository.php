<?php
namespace app\repositories;

use app\models\Request;

class RequestRepository
{
    /**
     * @param array $filters
     * @return Request[]
     */
    public function getRequest(array $filters = []): array
    {
        $query = Request::find();
        if (isset($filters['status'])) {
            $query->andWhere(['status' => $filters['status']]);
        }
        if (isset($filters['created_at'])) {
            $query->andWhere(['>=', 'created_at', $filters['created_at']]);
        }
        return $query->all();
    }
}
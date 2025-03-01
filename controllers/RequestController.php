<?php
namespace app\controllers;

use Yii;
use app\models\User;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\filters\auth\HttpBasicAuth;
use app\models\Request;
use app\services\RequestService;

class RequestController extends Controller
{
    /**
     * @var RequestService
     */
    protected $requestService;
    
    public function __construct($id, $module, RequestService $requestService, $config = [])
    {
        $this->requestService = $requestService;
        parent::__construct($id, $module, $config);
    }
    
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $allowedDomain = Yii::$app->params['allowedDomain'];
        
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ["http://$allowedDomain", "https://$allowedDomain"],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
            ],
        ];
        
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'except' => ['create', 'options'],
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);
                if ($user && $user->validatePassword($password) && $user->username === 'admin') {
                    return $user;
                }
                return null;
            },
        ];
        
        return $behaviors;
    }

    public function actions(): array
{
    $actions = parent::actions();
    $actions['options'] = [
        'class' => 'yii\rest\OptionsAction',
    ];
    return $actions;
}
    
    /**
     * @return array|Request
     */
    public function actionCreate()
    {
        $data = Yii::$app->getRequest()->getBodyParams();
        $request = $this->requestService->create($data);
        if ($request->hasErrors()) {
            Yii::$app->response->statusCode = 422;
            return $request->errors;
        }
        Yii::$app->response->statusCode = 201;
        return $request;
    }
    
    /**
     * @return array
     */
    public function actionIndex(): array
    {
        $filters = Yii::$app->request->get();
        return $this->requestService->get($filters);
    }
    
    /**
     * @param mixed $id
     * @return array|Request
     */
    public function actionUpdate($id)
    {
        $data = Yii::$app->getRequest()->getBodyParams();
        $request = $this->requestService->update($id, $data);
        if (!$request) {
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Тикет не найден'];
        }
        if ($request->hasErrors()) {
            Yii::$app->response->statusCode = 422;
            return $request->errors;
        }
        return $request;
    }
}

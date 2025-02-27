<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $request_id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $message
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class Request extends ActiveRecord
{
    const STATUS_ACTIVE = 'Active';
    const STATUS_RESOLVED = 'Resolved';
    
    public static function tableName(): string
    {
        return 'request';
    }
    
    public function rules(): array
    {
        return [
            [['name', 'email', 'message'], 'required'],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_RESOLVED]],
            ['comment', 'required', 'when' => function($model) {
                return $model->status === self::STATUS_RESOLVED;
            }, 'whenClient' => "function (attribute, value) {
                return $('#request-status').val() === '" . self::STATUS_RESOLVED . "';
            }"],
            [['message', 'comment'], 'string'],
        ];
    }
    
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }
    
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'email', 'message'];
        $scenarios['update'] = ['name', 'email', 'message'];
        $scenarios['resolve'] = ['comment', 'status'];
        return $scenarios;
    }
}

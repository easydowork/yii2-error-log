<?php

namespace adminlog\models;

use Yii;

/**
 * This is the model class for table "error_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $level
 * @property string $request_uri
 * @property string $category
 * @property string $ip
 * @property string $title
 * @property string $message
 * @property string $get
 * @property string $post
 * @property double $files
 * @property string $cookie
 * @property string $session
 * @property string $server
 * @property string $create_at
 */
class ErrorLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'error_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'level'], 'integer'],
            [['request_uri', 'title', 'message', 'get', 'post', 'cookie', 'session', 'server', 'create_at'], 'string'],
            [['files'], 'number'],
            [['category'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => '用户',
            'level'       => '级别',
            'request_uri' => '请求地址',
            'category'    => '异常:状态码',
            'ip'          => 'IP',
            'title'       => '标题',
            'message'     => '信息',
            'get'         => 'Get',
            'post'        => 'Post',
            'files'       => 'Files',
            'cookie'      => 'Cookie',
            'session'     => 'Session',
            'server'      => 'Server',
            'create_at'   => '时间',
        ];
    }
}

<?php

namespace adminlog\components;

use Yii;
use yii\log\Target;
use yii\di\Instance;
use yii\db\Connection;
use yii\web\ErrorHandler;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\log\LogRuntimeException;

/**
 * Class ErrorDbTarget
 * @package adminlog\components\target
 */
class ErrorDbTarget extends Target
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbTarget object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'sqlite';
    /**
     * @var string name of the DB table to store cache content. Defaults to "log".
     */
    public $logTable = '{{%log}}';


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @return string
     */
    protected function getContextMessage()
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getGlobalsInfo()
    {
        $context = ArrayHelper::filter($GLOBALS, $this->logVars);
        $result  = [];
        foreach ( $context as $key => $value ) {
            $result[$key] = json_encode($value);
        }

        return $result;
    }

    /**
     * @throws LogRuntimeException
     * @throws \yii\db\Exception
     */
    public function export()
    {
        if ( $this->db->getTransaction() ) {
            // create new database connection, if there is an open transaction
            // to ensure insert statement is not affected by a rollback
            $this->db = clone $this->db;
        }

        $tableName = $this->db->quoteTableName($this->logTable);
        $sql       = "INSERT INTO $tableName ([[level]], [[category]], [[request_uri]], [[ip]], [[title]], [[message]], [[get]], [[post]], [[files]], [[cookie]], [[session]], [[server]], [[user_id]], [[create_at]])
                VALUES (:level, :category, :request_uri, :ip, :title, :message, :get, :post, :files, :cookie, :session, :server, :user_id, :create_at)";
        $command   = $this->db->createCommand($sql);

        $result = $this->getGlobalsInfo();

        foreach ( $this->messages as $message ) {
            list($text, $level, $category, $timestamp) = $message;
            if ( !is_string($text) ) {
                if ( $text instanceof \Throwable || $text instanceof \Exception ) {
                    $title = $text->getMessage();
//                    $text = (string)$text;
                    $text = Yii::$app->errorHandler->renderCallStack($text);
                } else {
                    $text = VarDumper::export($text);
                    $title = mb_strcut($text,0,50);
                    $traces = [];
                    if (isset($message[4])) {
                        foreach ($message[4] as $trace) {
                            $traces[] = "in {$trace['file']}:{$trace['line']}";
                        }
                    }
                    $text = (empty($traces) ? '' : ("<br>" . implode("<br>", $traces)."<br>")).$text;
                }

                if ( $command->bindValues([
                        ':level'       => $level,
                        ':category'    => $category,
                        ':ip'          => Yii::$app->request->getUserIP(),
                        ':title'       => $title??'',
                        ':message'     => $text,
                        ':get'         => $result['_GET'] ?? '',
                        ':post'        => $result['_POST'] ?? '',
                        ':files'       => $result['_FILES'] ?? '',
                        ':cookie'      => $result['_COOKIE'] ?? '',
                        ':session'     => $result['_SESSION'] ?? '',
                        ':server'      => $result['_SERVER'] ?? '',
                        ':request_uri' => Yii::$app->request->url,
                        ':user_id'     => Yii::$app->user->getId() ?? 0,
                        ':create_at'   => date('Y-m-d H:i:s'),
                    ])->execute() > 0 ) {
                    continue;
                }
                throw new LogRuntimeException('Unable to export log through database!');
            }
        }
    }
}

<?php

namespace adminlog\controllers;

use Yii;
use yii\db\Expression;
use adminlog\models\ErrorLog;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;
use adminlog\models\search\ErrorLogSearch;

/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * actionIndex
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new ErrorLogSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 全部删除
     * actionAllDelete
     * @return \yii\web\Response
     */
    public function actionAllDelete()
    {

        ErrorLog::deleteAll();

        return $this->redirect(['index']);
    }

    /**
     * 批量删除
     * actionBatchDelete
     * @return \yii\web\Response
     */
    public function actionBatchDelete()
    {
        $keys = Yii::$app->request->post('keys');

        ErrorLog::deleteAll(['in','id',$keys]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * 删除记录
     * actionDelete
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        ErrorLog::findOne($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

}

<?php
/* @var $this yii\web\View */
/* @var $searchModel \adminlog\models\search\ErrorLogSearch */

$this->title = '异常日志';

$js = <<< JS
$('.viewInfo').click(function(e) {
    $(this).next('.summary').toggle();
    errorMessage($(this).next('.summary')[0]);
});
$('.viewInfo').find('input[type=checkbox]').click(function(e) {
    e.stopPropagation();
});

$("#all-delete").on("click", function () {
     window.location.href = '/site/all-delete';
}).html('<span class="btn-link glyphicon glyphicon-trash">清空</span>');

$("#batch-delete").on("click", function () {
    var keys = $(".grid-view").yiiGridView("getSelectedRows");
    if(!keys.length){
        alert('请选择操作的选项！');
    }else{
        $.post('/site/batch-delete',{keys:keys});
    }
    return false;
}).html('<span class="btn-link glyphicon glyphicon-trash"></span>');
JS;

$this->registerJs($js);

?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12" style="overflow: auto;">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => '{items}</div><div class="box-footer clearfix"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
                    'pager'        => [
                        'options' => ['class' => 'pagination no-margin pull-right']
                    ],
                    'rowOptions'   => ['class' => 'viewInfo'],
                    'columns'      => [
                        [
                            'class'         => 'yii\grid\CheckboxColumn',
                            'name'          => 'id',
                            'filterOptions' => ['id' => 'batch-delete'],
                        ],
                        [
                            'class'  => 'yii\grid\SerialColumn',
                            'header' => '#'
                        ],
                        'user_id',
                        'level',
                        ['attribute'      => 'request_uri',
                         'contentOptions' => function ($model) {
                             return ['title' => $model->request_uri];
                         },
                         'value'          => function ($model) {
                             return mb_strcut($model->request_uri, 0, 50);
                         }
                        ],
                        'category',
                        'ip',
                        ['attribute'      => 'title',
                         'contentOptions' => function ($model) {
                             return ['title' => $model->title];
                         },
                         'value'          => function ($model) {
                             return mb_strcut($model->title, 0, 50);
                         }
                        ],
                        //                        'get',
                        //                        'post',
                        //                        'files',
                        //                        'cookie',
                        //                        'session',
                        //                        'server',
                        'create_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'header'   => '操作',
                            'filterOptions' => ['id' => 'all-delete'],
                        ],
                    ],
                    'afterRow'     => function ($model, $key, $index, $grid) {
                        return $this->render('_error-td', [
                            'model' => $model,
                            'key'   => $key,
                            'index' => $index,
                            'grid,' => $grid,
                        ]);
                    }
                ]); ?>
            </div>
        </div>
    </div>
</div>
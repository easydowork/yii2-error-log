<?php
/* @var $model \adminlog\models\ErrorLog */

$globalsInfo = [
    'get'     => (json_decode($model->get ?? '', 1)),
    'post'    => (json_decode($model->post ?? '', 1)),
    'files'   => (json_decode($model->files ?? '', 1)),
    'cookie'  => (json_decode($model->cookie ?? '', 1)),
    'session' => (json_decode($model->session ?? '', 1)),
    'server'  => (json_decode($model->server ?? '', 1))
];

$request     = '';

foreach ( $globalsInfo as $name =>$value ) {
    if(!empty($value)){
        $request .= '$' . $name . ' = ' . \yii\helpers\VarDumper::export($value) . ";\n\n";
    }
}
$request = '<pre>' . rtrim($request, "\n") . '</pre>';

?>
<tr class="summary" style="display: none;">
    <td colspan="10">
        <div class="col-sm-12">
            <div class="call-stack">
                <?= $model->message ?>
            </div>
            <div class="request">
                <div class="code">
                    <?= $request ?>
                </div>
            </div>
        </div>
    </td>
</tr>
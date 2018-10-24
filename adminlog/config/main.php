<?php

$dbConfig = require 'sqlite.php';

$params = [];

return [
    'id'                  => 'adminlog',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'adminlog\controllers',
    'name'                => 'adminlog',//添加项目名称
    'bootstrap'           => ['log'],
    'language'            => 'zh-CN',
    'timeZone'            => 'Asia/Shanghai',
    'vendorPath'          => dirname(dirname(__DIR__)) . '/vendor',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components'          => [
        'db'           => $dbConfig,
        'request'      => [
            'cookieValidationKey' => 'pcfi4ry0ADASSFZynS8jKL-T4amTUKH4Q',
            'csrfParam'           => 'adminlog',
        ],
        'response'     => [
            'class' => 'yii\web\Response'
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params'              => $params,
];

```php
//common/config 添加数据库配置
$sqliteConfig = require '../../adminlog/config/sqlite.php';
'sqlite' => $sqliteConfig,
//配置 log 组件
'log' => [
    'targets' => [
        [
            'class' => 'adminlog\components\ErrorDbTarget',
            'levels' => ['error'],
            'db' => 'sqlite',
            'logTable' => 'error_log',
        ],
    ],
],


//common/config/bootstrap.php
Yii::setAlias('@adminlog', dirname(dirname(__DIR__)) . '/adminlog');

chmod 0777 error_log.db

```


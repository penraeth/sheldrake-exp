<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/../../sheldrake-exp/vendor/autoload.php');
require(__DIR__ . '/../../sheldrake-exp/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../sheldrake-exp/common/config/bootstrap.php');
require(__DIR__ . '/../../sheldrake-exp/frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../sheldrake-exp/common/config/main.php'),
    require(__DIR__ . '/../../sheldrake-exp/common/config/main-local.php'),
    require(__DIR__ . '/../../sheldrake-exp/frontend/config/main.php'),
    require(__DIR__ . '/../../sheldrake-exp/frontend/config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();

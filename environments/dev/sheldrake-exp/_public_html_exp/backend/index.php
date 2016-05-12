<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../sheldrake-exp/vendor/autoload.php');
require(__DIR__ . '/../sheldrake-exp/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../sheldrake-exp/common/config/bootstrap.php');
require(__DIR__ . '/../sheldrake-exp/backend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../sheldrake-exp/common/config/main.php'),
    require(__DIR__ . '/../sheldrake-exp/common/config/main-local.php'),
    require(__DIR__ . '/../sheldrake-exp/backend/config/main.php'),
    require(__DIR__ . '/../sheldrake-exp/backend/config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();

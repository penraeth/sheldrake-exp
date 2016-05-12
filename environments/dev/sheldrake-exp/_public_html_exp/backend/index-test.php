<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) 
{
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../sheldrake-exp/vendor/autoload.php');
require(__DIR__ . '/../sheldrake-exp/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../sheldrake-exp/common/config/bootstrap.php');
require(__DIR__ . '/../sheldrake-exp/backend/config/bootstrap.php');

$config = require(__DIR__ . '/../sheldrake-exp/tests/codeception/config/backend/acceptance.php');

(new yii\web\Application($config))->run();

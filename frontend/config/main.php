<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // here you can set theme used for your frontend application 
        // - template comes with: 'default', 'slate', 'spacelab' and 'cerulean'
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/cerulean/views'],
                'baseUrl' => '@web/themes/cerulean',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\UserIdentity',
            'enableAutoLogin' => true,
            'autoRenewCookie' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
       'urlManager' => [
        	'enablePrettyUrl' => true,
        	'showScriptName' => false,
        	'rules' => [
        		'api/<action:([\-\w]+)>/<id:(\d+)>/<key:(\w+)>' => 'api/<action>',
        		'<controller:([\-\w]+)>/<action:([\-\w]+)>/<id:(\d+)>' => '<controller>/<action>',
        	]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];

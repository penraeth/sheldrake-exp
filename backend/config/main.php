<?php
$params = array_merge(
    require(__DIR__ . '/../../../sheldrake-exp/common/config/params.php'),
    require(__DIR__ . '/../../../sheldrake-exp/common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
       'gridview' =>  [
            'class' => '\kartik\grid\Module',
	    ],
	],
    'components' => [
        // here you can set theme used for your backend application 
        // - template comes with: 'default', 'slate', 'spacelab' and 'cerulean'
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/yeti/views'],
                'baseUrl' => '@web/themes/yeti',
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
            'baseUrl' => '/exp/backend',
        ],
       'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];

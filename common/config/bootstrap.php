<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('js', dirname(dirname(__DIR__)) . '/js');
Yii::setAlias('appRoot', '/'.basename(dirname(dirname(dirname(__DIR__)))));
Yii::setAlias('exp', '/exp');

session_set_cookie_params(3600 * 24 * 30);
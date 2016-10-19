<?php
use frontend\assets\AppAsset;
use kartik\widgets\Growl;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="/templates/penraeth/js/analytics.js" type="text/javascript"></script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
    
    
        <?php
            NavBar::begin([
                'brandLabel' => Html::img('@exp/templates/penraeth/images/Rupert-Sheldrake.gif'),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-default navbar-fixed-top',
                ],
            ]);

            // everyone can see Home page
            $menuItems[] = ['label' => Yii::t('app', 'experiments home'), 'url' => ['/site/index']];

            // we do not need to display Article/index, About and Contact pages to editor+ roles
            /*if (!Yii::$app->user->can('editor')) 
            {
                $menuItems[] = ['label' => Yii::t('app', 'Articles'), 'url' => ['/article/index']];
                $menuItems[] = ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']];
                $menuItems[] = ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']];
            }

            // display Article admin page to editor+ roles
            if (Yii::$app->user->can('editor'))
            {
                $menuItems[] = ['label' => Yii::t('app', 'Articles'), 'url' => ['/article/admin']];
            }        
            */
            
            // display Signup and Login pages to guests of the site
            if (Yii::$app->user->isGuest) 
            {
                $menuItems[] = ['label' => Yii::t('app', 'signup'), 'url' => ['/site/signup']];
                $menuItems[] = ['label' => Yii::t('app', 'login'), 'url' => ['/site/login']];
            }
            // display Logout to all logged in users
            else 
            {
                $menuItems[] = [
                    'label' => Yii::t('app', 'logout'). ' (' . Yii::$app->user->identity->first_name . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            
            $menuItems[] = ['label' => Yii::t('app', 'back to main site'), 'url' => ['/']];
           
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
			<?php
				foreach (Yii::$app->session->getAllFlashes() as $key=>$message) {
					echo \kartik\widgets\Growl::widget([
						'type' => $key,
						'body' => $message,
						'pluginOptions' => [
							'showProgressbar' => false,
							'placement' => ['from'=>'top', 'align'=>'left'],
							'delay' => 5000,
							'offset' => ['x'=>0,'y'=>42],
							'z_index' => 999
						],
						'options' => [
							'class' => 'col-sm-3 small square',
						]
					]);
				}
			?>
			
			<?= $content ?>
			
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; Rupert Sheldrake <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

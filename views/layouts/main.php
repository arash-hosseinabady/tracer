<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
$user = Yii::$app->user;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->title = Yii::t('app', 'Trace System'); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::t('app', 'Trace System'),
        'brandUrl' => '/',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('app', 'Login'),
                'url' => ['/user-management/auth/login'],
                'visible' => $user->isGuest,
            ],
            [
                'label' => !$user->isGuest ? $user->username : Yii::t('app', 'User'),
                'items' => [
                    ['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout']],
                    ['label' => Yii::t('app', 'Change own password'), 'url' => ['/user-management/auth/change-own-password']],
                ],
                'visible' => !$user->isGuest,
            ],
            [
                'label' => Yii::t('app', 'User Management'),
                'items' => UserManagementModule::menuItems(),
                'visible' => $user->isSuperadmin,
            ],
            [
                'label' => Yii::t('app', 'Device List'),
                'url' => '/device',
                'visible' => User::canRoute(['/device/index']),
            ],
            [
                'label' => Yii::t('app', 'Trace'),
                'url' => '/location-info/trace',
                'visible' => User::canRoute(['/location-info/trace']),
            ],
            [
                'label' => Yii::t('app', 'User Device'),
                'url' => '/user-device',
                'visible' => User::canRoute(['/user-device']),
            ],
            [
                'label' => Yii::t('app', 'Contact With Us'),
                'url' => '#',
            ],
        ],
    ]);
    ?>
    <?php NavBar::end(); ?>
    <div class="container-fluid">
        <?=
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])
        ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left"><?= Yii::$app->jdate->date('Y', strtotime('now')) ?> &copy;Tracer</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

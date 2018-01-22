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
    <?php $this->title = Yii::t('app', 'Support System'); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('app', 'Frontend'),
                'items' => [
                    ['label' => Yii::t('app', 'Logout'), 'url' => ['/user-management/auth/logout']],
                    ['label' => Yii::t('app', 'Change own password'), 'url' => ['/user-management/auth/change-own-password']],
                ],
                'visible' => !$user->isGuest,
            ],
            [
                'label' => Yii::t('app', 'User Management'),
                'items' => UserManagementModule::menuItems(),
                'visible' => (User::hasRole('Admin') || User::hasRole('supportAdmin')),
            ],
        ],
    ]);
    ?>
    <span class="navbar-text navbar-left" id='clock' data-clock='<?= date('U') ?>'>
                <?= Yii::$app->jdate->date('Y-m-d H:i'); ?>
            </span>
    <?php NavBar::end();
    ?>
    <div class="container-fluid">
        </br></br></br></br>
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

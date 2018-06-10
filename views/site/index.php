<?php
/* @var $this yii\web\View */
?>
    <div class="site-index">

        <div class="body-content">
            <div class="col-lg-12">
                <div class="col-lg-12 banner">
                    <figure style="background: url(/img/banner1.jpg) no-repeat; height: 100%;">
                        <figcaption style="padding-top: 250px">
                            <div class="col-lg-12" style="height: 150px; background-color: rgba(26,26,26,0.19)">
                                <h2><?php echo Yii::t('app', 'Tracer System') ?></h2>
                                <p>
                                    <?php echo Yii::t('app', 'Tracer System') ?>
                                </p>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <div class="col-lg-12" id="feature">
                    <div class="col-lg-12 title">
                        <h3><?= Yii::t('app', 'Feature List') ?></h3>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-lg-12 list">
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <div class="col-lg-6">
                                <div class="col-lg-12 details">
                                    <div class="col-lg-12 details-title">
                                        <h4><?= Yii::t('app', 'Feature') . $i ?></h4>
                                    </div>
                                    <div class="col-lg-12 feature-details-body">
                                        <div class="col-lg-6">
                                            <p>
                                                <?= Yii::t('app', 'Feature Tracer System') ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <img src="/img/p1.jpg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="col-lg-12" id="how-to-use">
                    <div class="col-lg-12 title">
                        <h3><?= Yii::t('app', 'How It Work') ?></h3>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-lg-4 steps">
                        <div class="col-lg-12">
                            <span>1.</span>
                            <?= Yii::t('app', 'Create Account') ?>
                        </div>
                        <hr class="step">
                    </div>
                    <div class="col-lg-4 steps">
                        <div class="col-lg-12">
                            <span>2.</span>
                            <?= Yii::t('app', 'Register Device') ?>
                        </div>
                        <hr class="step">
                    </div>
                    <div class="col-lg-4 steps">
                        <div class="col-lg-12">
                            <span>3.</span>
                            <?= Yii::t('app', 'Tracking') ?>
                        </div>
                        <hr class="step">
                    </div>
                    <div class="col-lg-12" style="text-align: center;">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-2">
                            <?= \kartik\helpers\Html::a(Yii::t('app', 'Get Started'),
                                ['/user-management/auth/registration'],
                                ['class' => 'btn btn-success']
                            ) ?>
                        </div>
                        <div class="col-lg-5"></div>
                    </div>
                </div>
                <div class="col-lg-12" id="about-us">
                    <div class="col-lg-12 title">
                        <h3><?= Yii::t('app', 'About Us') ?></h3>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-lg-12 content">
                        <p><?= Yii::t('app', 'About Us') ?>...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php
$this->registerCssFile('/css/home-page.css');
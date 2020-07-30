<?php

/* @var $this yii\web\View */

$this->title = 'Teleport';
?>
<div class="site-index">

    <div class="body-content" style="height: 100%" id="id_table">

        <?= $this->render('spisok',compact('provider')) ?>

    </div>
</div>

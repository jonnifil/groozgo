
<script type="text/javascript">
    var client_info = <?= json_encode($client) ?>;
    var client_edit = <?=(int)$edit ?>;
    var back_url = "<?=Yii::$app->getUrlManager()->createUrl(['site/index']) ?>";
    var client_url = "<?=Yii::$app->getUrlManager()->createUrl(['site/client']) ?>";
    var save_url = "<?=Yii::$app->getUrlManager()->createUrl(['site/save']) ?>";
    var create_url = "<?=Yii::$app->getUrlManager()->createUrl(['site/create']) ?>";
    var address_url = "<?=Yii::$app->getUrlManager()->createUrl(['site/address']) ?>";
</script>
<?php $this->registerJsFile(Yii::getAlias('@web/js/client.js'), ['depends' => \yii\web\JqueryAsset::className()]); ?>
<div class="col-lg-12" id="client_card">
    <h2 class="text-center">Карточка клиента</h2>
    <div class="col-lg-6 form-horizontal" id="main_info">
        <h3 class="text-center">Общая информация</h3>
        <div class="row">
            <div class="col-xs-12" style="margin-bottom:10px; ">
                <div class="pull-right button-panel" name="status-list" style="padding-right: 5px;">
                    <button class="btn btn-default" name="edit"><i class="glyphicon glyphicon-pencil"></i> Редактировать</button>
                    <button class="btn btn-default hide" name="save"><i class="glyphicon glyphicon-save"></i> Сохранить</button>
                </div>
                <button class="btn btn-default pull-right hide" name="cancel" style="margin-right: 5px;"><i class="glyphicon glyphicon-undo"></i> Отменить</button>
            </div>
        </div>
        <div id="error_message"></div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                Фамилия
            </label>
            <div class="col-lg-8">
                <p class="form-control-static">
                    <?=$client['last_name'] ?>
                </p>
                <input type="text" class="form-control hide" name="last_name" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                Имя
            </label>
            <div class="col-lg-8">
                <p class="form-control-static">
                    <?=$client['first_name'] ?>
                </p>
                <input type="text" class="form-control hide" name="first_name" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                Пол
            </label>
            <div class="col-lg-8">
                <p class="form-control-static">
                    <?=$client['sex'] ?>
                </p>
                <div class="input-group hide">
                    <label class="col-lg-3">  М  </label>
                    <div class="col-lg-3">
                        <input type="radio" name="sex" value="1">
                    </div>
                    <label class="col-lg-3">  Ж  </label>
                    <div class="col-lg-3">
                        <input type="radio" name="sex" value="0">
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                Дата рождения
            </label>
            <div class="col-lg-8">
                <p class="form-control-static">
                    <?=$client['born_date'] ?>
                </p>
                <input type="date" class="form-control hide" name="born_date" onclick="setDate(this);" max="2018-01-01" min="1900-01-01">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                Телефон
            </label>
            <div class="col-lg-8">
                <p class="form-control-static">
                    <?=$client['phone'] ?>
                </p>
                <div class="input-group hide">
                    <span class="input-group-addon">+7</span>
                    <input type="tel" class="form-control hide" name="phone" maxlength="10">
                </div>
            </div>
        </div>
    </div>
    <?php if ($edit): ?>
    <div class="col-lg-6" id="address_info">
        <h3 class="text-center">Адреса</h3>
        <div class="col-lg-12">
            <button class="btn btn-default pull-right" name="add_address"><i class="glyphicon glyphicon-plus"></i> Добавить адрес</button>
            <div class="clearfix"></div>
        </div>
        <?php echo \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                ['attribute' => 'name', 'header' => 'Название объекта'],
                ['attribute' => 'address', 'header' => 'Адрес'],

                [
                    'class' => '\yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '{view}',
                    'buttons' => [
                        'view'=>function ($url, $model) {
                            return \yii\helpers\Html::button( '<span class="glyphicon glyphicon-pencil"></span>',
                                ['data_id' => $model['id'], 'name' => 'edit_address', 'title' => 'Редактировать']);
                        }
                    ]
                ]
            ]
        ]); ?>
    </div>
    <?php endif; ?>
</div>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&amp;load=SuggestView&amp;onload=onLoad"></script>
<div class="modal fade" id="address_dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Добавление адреса клиента</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <input type="hidden" name="id">
                    <input type="hidden" name="client_id">
                    <div class="form-group">
                        <label class="control-label col-xs-3">Название объекта</label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-3">Адрес объекта</label>
                        <div class="col-xs-8">
                            <input type="text" class="form-control" name="address" id="address_add">
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" name="save"><i class="fa fa-save"></i> Сохранить</button>
                <button class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>

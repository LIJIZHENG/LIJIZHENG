<?php
$this->registerCssFile(Yii::getAlias('@web').'/datatables/css/jquery.dataTables.css');
$this->registerJsFile(Yii::getAlias('@web').'/datatables/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile(Yii::getAlias('@web').'/datatables/datatables.min.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);

?>
    <a href="/permissions/add-permissions" class="btn btn-primary btn-xs">添加</a>
    <table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
        <tbody>
        <?php foreach ($permissions as $v):?>
        <tr>
            <td><?=$v->name?></td>
            <td><?=$v->description?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['permissions/del','name'=>$v->name])?>" class="btn btn-danger btn-xs">删除</a>
                <a href="<?=\yii\helpers\Url::to(['permissions/edit-permissions','name'=>$v->name])?>" class="btn btn-primary btn-xs">修改</a>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
</table>
<?php
$this->registerJs(
<<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable();
} );
JS
);

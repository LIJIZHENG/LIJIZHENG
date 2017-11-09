<?php
$this->registerCssFile(Yii::getAlias('@web').'/datatables/css/jquery.dataTables.css');
$this->registerJsFile(Yii::getAlias('@web').'/datatables/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile(Yii::getAlias('@web').'/datatables/datatables.min.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);

?>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th><a href="/index.php?r=permissions/add-permissions" class="btn btn-primary btn-xs">添加</a></th>
        <th>Column 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
<?php foreach ($permissions as $v):?>
    <tr>
        <td><?=$v->name?></td>
        <td><?=$v->description?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['permissions/del','name'=>$v->name])?>" class="btn btn-primary btn-xs">删除</a>
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

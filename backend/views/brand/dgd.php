<?php
?>
<table class="table table-bordered">
<tr>
    <th>ID</th>
    <th>名称</th>
    <th>简介</th>
    <th>排序</th>
    <th>状态</th>
    <th>操作</th>
</tr>
<?php foreach ($model as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?=$v->name?></td>
        <td><?=$v->intro?></td>
        <td><?=$v->sort?></td>
        <td><?=$v->status?></td>
        <td>
            <a href="javascript:;" class="btn-del btn btn-primary btn-xs" data-id="<?=$v['id']?>">回收</a>
            <a href="<?=\yii\helpers\Url::to(['brand/update','id'=>$v['id']])?>" class="btn btn-primary btn-xs">恢复</a>
        </td>
    </tr>
<?php endforeach;?>
</table>
<?php
$url = \yii\helpers\Url::to(['brand/del']);
$this->registerJs(
    <<<JS
    $(".btn-del").click(function(){
        if(confirm('是否回收该用户?')){
            var url = "{$url}";
            var id = $(this).attr('data-id');
            var that = this;
            $.post(url,{id:id},function(data){
                if(data == 'success'){
                    $(that).closest('tr').fadeOut();
                }else{
                    //删除失败
                    alert(data);
                }
            });
        }
    });
JS
);
?>
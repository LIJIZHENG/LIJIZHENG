<?php
/* @var $this yii\web\View */
?>
    <a href="/index.php?r=goods/add" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
    <tr>
        <td><?=$v['id']?></td>
        <td><?=$v['name']?></td>
        <td>
            <a href="javascript:;" class="btn-del btn btn-primary" data-id="<?=$v['id']?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$v['id']])?>" class="btn btn-primary">修改</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
?>
<?php
$url = \yii\helpers\Url::to(['goods/del']);
$this->registerJs(
    <<<JS
    $(".btn-del").click(function(){
        if(confirm('是否删除该用户?删除后无法恢复!')){
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
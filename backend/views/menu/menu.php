<?php
?>
<a href="/index.php?r=menu/add-menu" class="btn btn-danger btn-xs">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>菜单名</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?=$v->label?></td>
        <td><?=$v->sort?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['menu/edit-menu','id'=>$v->id])?>" class="btn btn-primary btn-xs">修改</a>
            <a href="javascript:;" class="btn_del btn btn-danger btn-xs" data-id="<?=$v['id']?>">删除</a>
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
/**
 * @var $this \yii\web\View
 */
//视图注册js代码
$url = \yii\helpers\Url::to(['menu/del']);
$this->registerJs(
    <<<JS
    $(".btn_del").click(function(){
        if(confirm('是否删除该用户?删除后无法恢复!')){
            var url = "{$url}";
            var id = $(this).attr('data-id');
            var that = this;
            $.post(url,{id:id},function(data){
                if(data == 'success'){
                    //删除成功
                    //alert('删除成功');
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

<?php
?>
    <a href="/index.php?r=brand/xian" class="glyphicon glyphicon-trash"> 回收站</a>
<a href="/index.php?r=brand/add" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brand as $v):?>
        <tr>
            <td><?=$v['id']?></td>
            <td><?=$v['name']?></td>
            <td><?=$v['intro']?></td>
            <td><?=\yii\bootstrap\Html::img($v->logo,['width'=>80])?></td>
            <td><?=$v['sort']?></td>
            <td><?=$v['status']?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$v['id']])?>?>" class="btn btn-primary">修改</a>
                <a href="javascript:;" class="btn-del btn btn-primary" data-id="<?=$v['id']?>">删除</a>
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
$url = \yii\helpers\Url::to(['brand/del']);
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
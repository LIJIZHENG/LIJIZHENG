<?php
?>
<a href="/member/add" class="btn btn-primary btn-xs">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>收货人</th>
        <th>省区</th>
        <th>市区</th>
        <th>城区</th>
        <th>手机号</th>
        <th>详细地址</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
        <tr>
            <td><?=$v->id?></td>
            <td><?=$v->name?></td>
            <td><?=$v->cmbProvince?></td>
            <td><?=$v->cmbCity?></td>
            <td><?=$v->cmbArea?></td>
            <td><?=$v->tel?></td>
            <td><?=$v->address?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['member/edit','id'=>$v->id])?>" class="btn btn-primary">修改</a>
                <a href="javascript:;" class="btn-del btn btn-danger" data-id="<?=$v->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$url = \yii\helpers\Url::to(['member/del']);
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


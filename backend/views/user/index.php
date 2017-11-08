<?php
?>
    <a href="/index.php?r=user/add" class="btn btn-primary btn-xs">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>auth_key</th>
        <th>哈希密码</th>
        <th>password_reset_token</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新日期</th>
        <th>最后登录时间</th>
        <th>最后登陆ip字段</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
        <td><?=$v->id?></td>
        <td><?=$v->username?></td>
        <td><?=$v->auth_key?></td>
        <td><?=$v->password_hash?></td>
        <td><?=$v->password_reset_token?></td>
        <td><?=$v->email?></td>
        <td><?=$v->status?></td>
        <td><?=$v->created_at?></td>
        <td><?=$v->updated_at?></td>
        <td><?=$v->last_login_time?></td>
        <td><?=$v->last_login_ip?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['user/edit','id'=>$v['id']])?>?>" class="btn btn-primary btn-xs">修改</a>
            <a href="javascript:;" class="btn-del btn btn-primary btn-xs" data-id="<?=$v['id']?>">删除</a>
        </td>
    <?php endforeach;?>
</table>
<?php
echo yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
?>
<?php
$url = \yii\helpers\Url::to(['user/del']);
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
<?php
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>树id</th>
        <th>右值</th>
        <th>右值</th>
        <th>层级</th>
        <th>名称</th>
        <th>上级分类id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
    <tr>
        <td><?=$v['id']?></td>
        <td><?=$v['tree']?></td>
        <td><?=$v['lft']?></td>
        <td><?=$v['rgt']?></td>
        <td><?=$v['depth']?></td>
        <td><?=$v['name']?></td>
        <td><?=$v['parent_id']?></td>
        <td><?=$v['intro']?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goodsscategory/edit','id'=>$v['id']])?>?>" class="btn btn-primary">修改</a>
            <a href="javascript:;" class="btn-del btn btn-primary" data-id="<?=$v['id']?>">删除</a>
        </td>
    </tr>
<?php endforeach;?>
</table>
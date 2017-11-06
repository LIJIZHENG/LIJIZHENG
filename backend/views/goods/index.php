<?php
?>
<a href="" class="btn btn-primary">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品分类id</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>库存</th>
        <th>是否在售(1在售0下架)</th>
        <th>状态是否正常(1正常 0回收)</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>浏览次数</th>
        <th>操作</th>
    </tr>
<?php foreach ($model as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?=$v->name?></td>
        <td><?=$v->sn?></td>
        <td><?=$v->logo?></td>
        <td><?=$v->goods_category_id?></td>
        <td><?=$v->brand_id?></td>
        <td><?=$v->market_price?></td>
        <td><?=$v->shop_price?></td>
        <td><?=$v->stock?></td>
        <td><?=$v->is_on_sale?></td>
        <td><?=$v->status?></td>
        <td><?=$v->sort?></td>
        <td><?=date('Y:m:d H:m:s',$v->create_time)?></td>
        <td><?=$v->view_times?></td>
        <td>
            <a>删除</a>
            <a>修改</a>
        </td>
    </tr>
<?php endforeach;?>
</table>

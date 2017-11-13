<?php
?>
<form action="<?=\yii\helpers\Url::to(['goods/index'])?>" method="post">
<div class="form-div">
        <!-- 分类 -->
        <select name="goods_category_id">
            <option value="">所有分类</option>
            <?php foreach($category as $v):?>
                <option value="<?=$v->id?>"><?=$v->name?></option>
            <?php endforeach;?>
        </select>
        <!-- 推荐 -->
        <select name="status">
            <option value="">全部</option>
            <option value="1">正常</option>
            <option value="0">回收站</option>
        </select>
        <!-- 上架 -->
        <select name="is_on_sale">
            <option value=''>全部</option>
            <option value="1">在售</option>
            <option value="0">回收站</option>
        </select>
        <!-- 关键字 -->
        关键字 <input type="text" name="keyword" size="15" />
        <input type="submit" value=" 搜索 " class="button" />
</div>
</form>
<a href="/goods/add" class="btn btn-primary btn-xs">添加</a>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品分类id</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
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
        <td><?=\yii\bootstrap\Html::img($v->logo,['width'=>80])?></td>
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
            <a href="javascript:;" class="btn-del btn btn-danger btn-xs" data-id="<?=$v['id']?>">删除</a>
            <a href="/goods/addgoods" class="btn btn-primary btn-xs">相册</a>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$v['id']])?>" class="btn btn-danger btn-xs">编辑</a>
        </td>
    </tr>
<?php endforeach;?>
</table>
<?php
echo yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
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
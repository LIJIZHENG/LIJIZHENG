<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput()->label('名称');
echo $form->field($model,'sn')->textInput()->label('货号');
/**
* @var $this \yii\web\view
*/
echo $form->field($model,'logo')->hiddenInput();
//引入css
$this->registerCssFile('@web'.'/webuploader/webuploader.css');
//引入js
$this->registerJsFile('@web'.'/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),
]);
$url = \yii\helpers\Url::to(['brand/upload']);
$this->registerJs(
    <<<JS
  // 初始化Web Uploader
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf: '/js/Uploader.swf',
    // 文件接收服务端。
    server: '{$url}',
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png'
    }
});
uploader.on('uploadSuccess',function(file ,response) {
      // console.debug(response);
    // $('#'+file.id).addClass('upload-state-done')
    $("#img").attr('src',response.url);
    $("#goods-logo").val(response.url);
});
JS
);
?>
    <div id="uploader" class="wu-example">
        <!--用来存放文件信息-->
        <div id="thelist" class="uploader-list"></div>
        <div class="btns">
            <div id="filePicker">选择文件</div>
        </div>
    </div>
    <div><img id="img" ></div>
<?php

echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\GoodsCategory::getGoodsCategory());
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getBrand());
echo $form->field($model,'market_price')->textInput()->label('市场价格');
echo $form->field($model,'shop_price')->textInput()->label('商品价格');
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale')->radioList(['1'=>'在售','0'=>'下架']);
echo $form->field($model,'status')->radioList(['1'=>'正常','0'=>'回收站']);
echo $form->field($model,'sort')->textInput()->label('排序');
echo $form->field($_model,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();
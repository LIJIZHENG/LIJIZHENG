<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'name')->textInput()->label('名称');
echo $form->field($brand,'intro')->textarea(['style'=>'height:100px;'])->label('简介');
/**
 * @var $this \yii\web\view
 */
echo $form->field($brand,'logo')->hiddenInput();
//引入css
$this->registerCssFile('@web'.'/webuploader/webuploader.css');
//引入js
$this->registerJsFile('@web'.'/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),
    ]);
$url = \yii\helpers\Url::to(['upload']);
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
    $("#brand-logo").val(response.url);
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
echo $form->field($brand,'sort')->textInput()->label('排序');
echo $form->field($brand,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();
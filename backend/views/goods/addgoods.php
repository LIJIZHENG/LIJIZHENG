<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
/**
 * @var $this \yii\web\view
 */
//echo $form->field($model,'path')->hiddenInput();
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
   $("<tr><td><img src='"+response.url+"'></td><td></td></tr>").appendTo('table')
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
    <table class="table table-bordered">
        <tr>
            <th>图片路径</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?$v->path?></td>
            </tr>
        <?php endforeach;?>
    </table>

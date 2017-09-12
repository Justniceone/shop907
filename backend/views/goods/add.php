<?php
$form= \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'brand_id')->dropDownList($brands);
echo $form->field($model,'name')->textInput();
echo $form->field($model,'goods_category_id')->textInput();
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'is_on_sale')->checkbox(['下架','停售']);
echo $form->field($model,'status')->checkbox(['正常','回收站']);
echo $form->field($model,'logo')->hiddenInput();


/**
 * @var $this \yii\web\View
 */
//$this->registerCssFile('@web/assets/ztree/css/demo.css');
$this->registerCssFile('@web/assets/ztree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/assets/ztree/js/jquery-1.4.4.min.js');
$this->registerJsFile('@web/assets/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
        var setting = {
        data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
        callback: {//回调函数处理
		    onClick: function(event, treeId, treeNode) {
		   console.log(treeNode);
		   //将id放入input中
		   $('#goods-goods_category_id').val(treeNode.id);
		}
        	}    
        };
        var zNodes ={$topCategory};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       
        zTreeObj.expandAll(true);
        //回显节点
        var node=zTreeObj.getNodeByParam("id", "$model->goods_category_id", null);
        zTreeObj.selectNode(node);
JS

));

//--------uploadfive------------------

use yii\web\JsExpression;

//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $('input[type=hidden]').val(data.fileUrl);
        $('.image').attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);

//------------end------------------------

echo \yii\bootstrap\Html::img($model->logo,['class'=>'image']);
echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',]
]);


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
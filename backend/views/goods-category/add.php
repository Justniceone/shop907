<?php
$form= \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->textInput();
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
echo $form->field($model,'intro')->textarea(['rows'=>3]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();

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
		   $('#goodscategory-parent_id').val(treeNode.id);
		}
        	}    
        };
        var zNodes ={$topCategory};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       
        zTreeObj.expandAll(true);
        //回显节点
        var node=zTreeObj.getNodeByParam("id", "$model->parent_id", null);
        zTreeObj.selectNode(node);
JS

));

<?php
$this->pageTitle = $model->name;

$this->menu = MenuDefinitions::groupMenu($model);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
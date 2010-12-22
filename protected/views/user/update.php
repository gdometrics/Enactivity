<?php
$this->pageTitle = $model->fullName;
$this->menu=array(
	array('label'=>'Update Profile', 
		'url'=>array('update', 'id'=>$model->id),
		'linkOptions'=>array('id'=>'user_update_menu_item'), 
		'visible'=>Yii::app()->user->id == $model->id,
	),
	array('label'=>'Update Password', 
		'url'=>array('updatepassword', 'id'=>$model->id),
		'linkOptions'=>array('id'=>'user_update_menu_item'), 
		'visible'=>Yii::app()->user->id == $model->id,
	),
);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-update-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="formlabel"><?php echo $form->labelEx($model, 'username'); ?></div>
		<div class="forminput"><?php echo $form->textField($model, 'username', array(
			'maxlength'=>User::USERNAME_MAX_LENGTH
		)); ?></div>
		<div class="formerrors"><?php echo $form->error($model, 'username'); ?></div>
	</div>

	<div class="row">
		<div class="formlabel"><?php echo $form->labelEx($model,'email'); ?></div>
		<div class="forminput"><?php echo $form->textField($model,'email',array(
			'maxlength'=>User::EMAIL_MAX_LENGTH
		)); ?></div>
		<div class="formerrors"><?php echo $form->error($model,'email'); ?></div>
	</div>

	<div class="row">
		<div class="formlabel"><?php echo $form->labelEx($model,'firstName'); ?></div>
		<div class="forminput"><?php echo $form->textField($model,'firstName',array(
			'maxlength'=>User::FIRSTNAME_MAX_LENGTH
		)); ?></div>
		<div class="formerrors"><?php echo $form->error($model,'firstName'); ?></div>
	</div>

	<div class="row">
		<div class="formlabel"><?php echo $form->labelEx($model,'lastName'); ?></div>
		<div class="forminput"><?php echo $form->textField($model,'lastName',array(
			'maxlength'=>User::LASTNAME_MAX_LENGTH
		)); ?></div>
		<div class="formerrors"><?php echo $form->error($model,'lastName'); ?></div>
	</div>

	<div class="row">
		<div class="buttons"><?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?></div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
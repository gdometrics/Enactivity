<?php
$this->pageTitle = 'Create a Group';

$this->menu = MenuDefinitions::adminMenu();
?>

<?php echo PHtml::beginContentHeader(); ?>
	<h1><?php echo PHtml::encode($this->pageTitle);?></h1>
<?php echo PHtml::endContentHeader(); ?>

<div class="novel">
	<section>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</section>
</div>
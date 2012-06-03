<?php
$this->pageTitle = 'Groups';

?>

<?php echo PHtml::beginContentHeader(); ?>
	<h1><?php echo PHtml::encode($this->pageTitle);?></h1>
<?php echo PHtml::endContentHeader(); ?>

<section class="novel">
	<h1>Member of</h1>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
		'cssFile'=>false,
	)); ?>
<section>
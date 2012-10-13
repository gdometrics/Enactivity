<?
/**
 * Displays calendar widget and agenda of tasks 
 * @uses $calendar
 * @uses $month
 *
 **/
$this->pageTitle = Yii::app()->format->formatMonth($month->firstDayOfMonthTimestamp) . " " . $month->year;
?>

<?= PHtml::beginContentHeader(); ?>
<h1>Calendar</h1>
<?= PHtml::endContentHeader(); ?>

<div class="novel">
	<section id="calendar-container">
	<? 
	// show task calendar
	echo $this->renderPartial('_calendar', array(
		'calendar'=>$calendar,
		'month'=>$month,
	));
	?>
	</section>
</div>

<div class="novel">
	<section id="agenda-container" class="agenda">
		<?
		// agenda
		echo $this->renderPartial('_agenda', array(
			'calendar'=>$calendar,
			'showParent'=>'true',
		));?>
	</section>
</div>
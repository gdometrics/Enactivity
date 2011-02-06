<?php
$this->pageTitle = date('F', mktime(0, 0, 0, $month + 1, $day, $year));

$this->pageMenu[] = array(
	'label'=>'Previous Month', 
	'url'=>array('event/calendar',
		'month' => $month - 1 < 1 ? 12 : $month - 1,
		'year' => $month - 1 < 1 ? $year - 1 : $year,
	),
	'linkOptions'=>array('id'=>'event_next_month_menu_item'),
); 

$this->pageMenu[] = array(
	'label'=>'Next Month', 
	'url'=>array('event/calendar',
		'month' => $month + 1 > 12 ? 1 : $month + 1,
		'year' => $month + 1 > 12 ? $year + 1 : $year,
	),
	'linkOptions'=>array('id'=>'event_next_month_menu_item'),
);
?>

<?php
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$events = $dataProvider->getData();

for($day = 1; $day <= $numDays; $day++) {
	$currentDayStart = mktime(0, 0, 0, $month, $day, $year);
	$currentDayEnd = mktime(23, 59, 59, $month, $day, $year);
	
	echo PHtml::tag('dl', array('class' => 'day'));
	echo PHtml::tag('dd');
	echo date('d', mktime(0, 0, 0, $month, $day, $year));
	echo PHtml::closeTag('dd');
	
	foreach($events as $event) {
		if((Date::MySQLDateOffset($event->starts) <= $currentDayEnd)
		&& (Date::MySQLDateOffset($event->ends) >= $currentDayStart)) {
			echo PHtml::tag('dt');
			echo PHtml::link(PHtml::encode($event->startTime) . " " . PHtml::encode($event->name), 
				array('view', 'id'=>$event->id)
				); 
			echo PHtml::closeTag('dt');
		}
	}
	
	echo PHtml::closeTag('dl');
}

//$this->widget('zii.widgets.CListView', array(
//	'dataProvider'=>$dataProvider,
//	'itemView'=>'_calendar',
//)); 
?>
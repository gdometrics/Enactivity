<?php
/**
 * @param $calendar TaskCalendar 
 * @param $showParent boolean defaults to true
 */

// instantiate the arrays if needed
$showParent = empty($showParent) ? $showParent : true;

?>
<?php 
$currentDate = null;
foreach($calendar->getDatedTasks() as $day => $times) {
	
	$daytime = strtotime($day);
	if($showParent) : ?>
	<div class="menu novel-controls">
		<ol>
			<li>
				<?php
				echo PHtml::link(
					'Add Task',
					array('task/create/',
						'day' => PHtml::encode(date('d', $daytime)),
						'month' => PHtml::encode(date('m', $daytime)),
						'year' => PHtml::encode(date('Y', $daytime)),
					)
				);
				?>
			</li>
		</ol>
	</div>
	<?php
	endif;
	
	// Display date heading
	echo PHtml::openTag('h1', array(
		'id' => PHtml::dateTimeId($daytime),
		'class' => 'agenda-date',
	));
	echo PHtml::encode(Yii::app()->format->formatDate($daytime));
	echo PHtml::closeTag('h1');
	
	foreach($times as $time => $tasks) {
		
		// Display time heading
		$timestamp = strtotime($day . ' ' . $time);
		echo PHtml::openTag('h2', array(
			'id' => PHtml::dateTimeId($timestamp),
			'class' => 'agenda-date',
		));
		echo PHtml::encode(Yii::app()->format->formatTime($timestamp));
		echo PHtml::closeTag('h2');
	
		// display list of tasks
		foreach($tasks as $task) {
			echo $this->renderPartial('_view', array(
				'data'=>$task, 
				'showParent'=>$showParent,
			));
		}
	}
}

$somedaytasks = $calendar->getSomedayTasks();
if(!empty($somedaytasks)) {
	echo PHtml::openTag('h1', array('id' => 'no-start-datedTasks'));
	echo 'Someday';
	echo PHtml::closeTag('h1');
	foreach($somedaytasks as $task) {
		if(!isset($task->starts)) {
			echo $this->renderPartial('_view', array(
				'data'=>$task,
				'showParent'=>$showParent,
			));
		}
	}
}
?>
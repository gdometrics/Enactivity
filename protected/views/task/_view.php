<?php 
/**
 * View for individual task models
 * 
 * @param Task $data model
 * @param boolean $showParent
 */

$showParent = isset($showParent) ? $showParent : true;

// calculate article class
$articleClass[] = "view";
$articleClass[] = "story";
$articleClass[] = "task";
$articleClass[] = "task-" . PHtml::encode($data->id);
$articleClass[] = $data->hasStarts ? "starts" : "";
$articleClass[] = $data->isCompleted ? "completed" : "not-completed";
$articleClass[] = $data->isInheritedTrash ? "trash" : "not-trash";
$articleClass[] = $data->isUserParticipating ? "participating" : "not-participating";

// start article
echo PHtml::openTag('article', array(
	'id' => "task-" . PHtml::encode($data->id),
	'class' => implode(" ", $articleClass),
));

// start headers

// task name
echo PHtml::openTag('h1', array('class'=>'story-title'));
if(isset($data->starts)) {
	echo PHtml::openTag('time');
	echo PHtml::encode(Yii::app()->format->formatTime(strtotime($data->starts)));
	echo PHtml::closeTag('time');
	echo ' ';
}
echo PHtml::link(
		PHtml::encode($data->name), 
		array('/task/view', 'id'=>$data->id),
		array()
	);
echo PHtml::closeTag('h1');

// parent tasks
if(!$data->isRoot()) {
	echo PHtml::openTag('h2', array('class'=>'parent-tasks story-subtitle'));
	if(!$data->isRoot() && ($data->parent->id != $data->rootId) && $showParent) {
		echo PHtml::openTag('span', array('class'=>'parent-task-name top-parent-task-name'));
		echo PHtml::encode(StringUtils::truncate($data->root->name, 32, ''));
		echo PHtml::closeTag('span');
		echo '&hellip;';
	}
	if(!$data->isRoot() && $showParent) {
		echo PHtml::openTag('span', array('class'=>'parent-task-name'));
		echo PHtml::encode(StringUtils::truncate($data->parent->name, 32));
		echo PHtml::closeTag('span');
		echo ' ';
	}
	echo PHtml::closeTag('h2');
}

// body

// event date
// echo PHtml::openTag('p');
// $this->widget('application.components.widgets.TaskDates', array(
// 	'task'=>$data,
// ));
// echo PHtml::closeTag('p');

//	tasks toolbar
echo PHtml::openTag('div', array(
	'class' => 'menu'
));
echo PHtml::openTag('ul');

if($data->isParticipatable) {
	// show complete/uncomplete buttons if user is participating
	if($data->isUserParticipating) {
		echo PHtml::openTag('li');
		
		if($data->isUserComplete) {
			echo PHtml::ajaxButton(
				PHtml::encode('Resume'), 
				array('/task/useruncomplete', 'id'=>$data->id),
				array( //ajax
					'replace'=>'#task-' . $data->id,
					'type'=>'POST',
					'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken,
				),
				array( //html
					'csrf' => true,
					'id'=>'task-useruncomplete-menu-item-' . $data->id,
					'class'=>'task-useruncomplete-menu-item',
					'title'=>'Resume work on this task',
				)
			);
		}
		else {
			echo PHtml::ajaxButton(
				PHtml::encode('Complete'), 
				array('/task/usercomplete', 'id'=>$data->id),
				array( //ajax
					'replace'=>'#task-' . $data->id,
					'type'=>'POST',
					'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken,
				),
				array( //html
					'csrf' => true,
					'id'=>'task-usercomplete-menu-item-' . $data->id,
					'class'=>'task-usercomplete-menu-item',
					'title'=>'Finish working on this task',
				)
			); 
		}
		echo PHtml::closeTag('li');
		
		// 'participate' button
		echo PHtml::openTag('li');
		echo PHtml::ajaxButton(
			PHtml::encode('Quit'), 
			array('task/unparticipate', 'id'=>$data->id),
			array( //ajax
				'replace'=>'#task-' . $data->id,
				'type'=>'POST',
				'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->getCsrfToken(), 
			),
			array( //html
				'csrf' => true,
				'id'=>'task-unparticipate-menu-item-' . $data->id,
				'class'=>'task-unparticipate-menu-item',
				'title'=>'Quit this task',
			)
		);
		echo PHtml::closeTag('li');
	}
	else {
		echo PHtml::openTag('li');
		echo PHtml::ajaxButton(
			PHtml::encode('Sign up'), 
			array('task/participate', 'id'=>$data->id),
			array( //ajax
				'replace'=>'#task-' . $data->id,
				'type'=>'POST',
				'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken,
			),
			array( //html
				'csrf'=>true,
				'id'=>'task-participate-menu-item-' . $data->id,
				'class'=>'task-participate-menu-item',
				'title'=>'Sign up for task',
			)
		);
		echo PHtml::closeTag('li');
	}
}

// update link
/* echo PHtml::openTag('li');
echo PHtml::link(
PHtml::encode('Update'),
array('task/update', 'id'=>$data->id),
array(
		'id'=>'task-update-menu-item-' . $data->id,
		'class'=>'task-update-menu-item',
		'title'=>'Update this task',
)
);
echo PHtml::closeTag('li');

// trash link
echo PHtml::openTag('li');
if($data->isTrash) {
	echo PHtml::ajaxButton(
		PHtml::encode('Restore'), 
		array('task/untrash', 'id'=>$data->id),
		array( //ajax
			'replace'=>'#task-' . $data->id,
			'type'=>'POST',
			'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken,
		),
		array( //html
			'csrf'=>true,
			'id'=>'task-untrash-menu-item-' . $data->id,
			'class'=>'task-untrash-menu-item',
			'title'=>'Restore this task',
		)
	);
}
else {
	echo PHtml::ajaxButton(
		PHtml::encode('Trash'), 
		array('task/trash', 'id'=>$data->id),
		array( //ajax
			'replace'=>'#task-' . $data->id,
			'type'=>'POST',
			'data'=>Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->csrfToken,
		),
		array( //html
			'csrf' => true,
			'id'=>'task-trash-menu-item-' . $data->id,
			'class'=>'task-trash-menu-item',
			'title'=>'Trash this task',
		)
	);
}
echo PHtml::closeTag('li'); */

echo PHtml::closeTag('ul');
echo PHtml::closeTag('div');
// end of toolbar

//// list participants
//echo PHtml::openTag('ol', array('class' => 'users'));
//foreach($data->taskUsers as $taskUser) {
//	$spanClass = "view";
//	$spanClass .= " participant";
//	$spanClass .= " participant-" . $taskUser->id;
//	$spanClass .= $taskUser->isCompleted ? " completed" : " not-completed";
//	$spanClass .= $taskUser->isTrash ? " trash" : " not-trash";
//	
//	echo PHtml::openTag('li');
//	echo PHtml::openTag('span', array(
//	'class' => $spanClass,		
//	));
//	$this->widget('application.components.widgets.UserLink', array(
//		'userModel' => $taskUser->user,
//	));
//	echo PHtml::closeTag('span');
//	echo PHtml::closeTag('li');
//}
//echo PHtml::closeTag('ol');

// end body

// close article
echo PHtml::closeTag('article');
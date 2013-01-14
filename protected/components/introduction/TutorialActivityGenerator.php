<?php

class TutorialActivityGenerator extends CComponent
{
	public static function generateIntroActivity($userId)
	{
		$activityAttributes = array(
			'name' => "Meet {Yii::app()->name}",
			'description' => "Welcome to {Yii::app()->name}! This is a sample activity to help guide you through the process of creating, sharing, and participating in activities and tasks.",
		);

		$tasksAttributesList = array(
			array(
				'name' => 'Sign up for {Yii::app()->name}',
			),
			array(
				'name' => 'Read about {Yii::app()->name}',
			),
			array(
				'name' => 'Create a new Activity',
			),
			array(
				'name' => 'Create a new Task',
			),
		);

		$form = new ActivityAndTasksForm();
		$form->publishWithoutGroup($activityAttributes, $tasksAttributesList);

		//Setting responses for Sign up
		Response::signUp($form->tasks[0]->id, $userId);
		Response::start($form->tasks[0]->id, $userId);
		Response::complete($form->tasks[0]->id, $userId);
		$signComment = new Comment();
		$signComment ->publishComment($form->tasks[0], array('content' => 'Sign up for an Activity'));

		//Setting respones for Read about
		Response::signUp($form->tasks[1]->id, $userId);
		Response::start($form->tasks[1]->id, $userId);
		$readComment = new Comment();
		$readComment->publishComment($form->tasks[1], array('content' => 'Read more about us!'));

		//Setting respones for Create a new Activity		
		Response::pend($form->tasks[2]->id, $userId);
		$newActivityComment = new Comment();
		$newActivityComment->publishComment($form->tasks[2], array('content' => 'Creating an Activity'));

		//Setting respones for Create a new Task
		Response::pend($form->tasks[3]->id, $userId);
		$newTaskComment = new Comment();
		$newTaskComment->publishComment($form->tasks[3], array('content' => 'Creating a Task'));
	}
	
}

?>
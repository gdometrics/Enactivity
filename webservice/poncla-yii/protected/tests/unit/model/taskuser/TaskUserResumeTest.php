<?php
/**
 * Tests for {@link TaskUser::signup}
 * @author ajsharma
 */
class TaskUserResumeTest extends DbTestCase
{
	/**
	 * Test that group insert works when group and user exist
	 */
	public function testTaskUserResumeValid() {
		$task = TaskFactory::insert();
		$user = UserFactory::insert(array(), $task->groupId);

		$this->assertTrue(TaskUser::signup($task->id, $user->id), "New taskuser was not resumed");
		
		$taskUser = TaskUser::model()->findByAttributes(array(
			'taskId' => $task->id,
			'userId' => $user->id,
		));
		
		$this->assertNotNull($taskUser, "TaskUser sign up did not save task");
		$this->assertEquals(0, $taskUser->isTrash, "TaskUser was trashed on resume");
		$this->assertEquals(0, $taskUser->isCompleted, "TaskUser was completed on resume");
	}

	/**
	 * Test that group insert works throw exception
	 */
	public function testTaskUserResumeValidTwiceValid() {
		$task = TaskFactory::insert();
		$user = UserFactory::insert(array(), $task->groupId);
		
		$this->assertTrue(TaskUser::signup($task->id, $user->id), "Task user was not resumed");
		$this->assertTrue(TaskUser::signup($task->id, $user->id), "Task user was not resumed");
	}

	/**
	 * Test that group insert fails when group is null
	 * @expectedException CDbException
	 */
	public function testTaskUserResumeTaskNullIsInvalid() {
		$user = UserFactory::insert();
		TaskUser::signup(null, $user->id);
	}

	/**
	 * Test that group insert fails when user is null
	 * @expectedException CDbException
	 */
	public function testTaskUserResumeUserNullIsInvalid() {
		$task = TaskFactory::insert();
		TaskUser::signup($task->id, null);
	}

	/**
	 * Test that group insert fails when group is null
	 * @expectedException CDbException
	 */
	public function testTaskUserResumeGroupAndUserNullIsInvalid() {
		TaskUser::signup(null, null);
	}
}
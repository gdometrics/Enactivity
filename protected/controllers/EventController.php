<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','all'),
				'expression'=>$user->isAdmin,
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$event = $this->loadModel($id);
		if(isset($_POST['EventUser'])) {
			$eventuser = $this->setEventUser($event);
		}
		else {
			$eventuser = $this->getEventUser($event);
			$eventuser = $eventuser != NULL ? $eventuser : new EventUser;
		}
				
		$this->render('view',array(
			'model'=>$event,
			'eventuser'=>$eventuser,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Event;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all event's that the are in the user's groups.
	 */
	public function actionIndex()
	{		
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		
		$dataProvider= $model->search();
		$dataProvider->criteria->addCondition("id IN (SELECT id FROM event WHERE groupId IN (SELECT groupId FROM group_user WHERE userId='" . Yii::app()->user->id . "'))");
		$dataProvider->criteria->addCondition("ends > NOW()");
		$dataProvider->criteria->order = "starts ASC";
		
		$this->render('index', array(
		        'model'=>$model,
		        'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * List all events
	 */
	public function actionAll() 
	{		
		$dataProvider=new CActiveDataProvider('Event');
		$this->render('index', array(
			'dataProvider'=>$dataProvider,
			'myevents'=>$mygroupsevents,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Event::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Set an event user status for the current event and user
	 * @param unknown_type $event
	 */
	public function getEventUser($event) {
		$criteria = new CDbCriteria;
		$criteria->addCondition("eventId = '" . $event->id . "'");
		$criteria->addCondition("userId = '" . Yii::app()->user->id . "'");
		$eventuser = EventUser::model()->find($criteria);
		return $eventuser;
	}
	
	/**
	 * Set an event user status for the current event and user
	 * @param unknown_type $event
	 */
	public function setEventUser($event) {
		
		$eventuser = new EventUser;
		if(isset($_POST['EventUser']))
		{
			$eventuser->attributes = $_POST['EventUser'];
			if($event->addEventUser($eventuser))
			{
				Yii::app()->user->setFlash('Response Submitted', 'Thank you for your RSVP.');
				$this->refresh();
			}
		}
		return $eventuser;
	}
}

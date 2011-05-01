<?php

/**
 * This is the model class for table "goal".
 *
 * The followings are the available columns in table 'goal':
 * @property integer $id
 * @property string $name
 * @property integer $groupId
 * @property integer $ownerId
 * @property integer $isCompleted
 * @property integer $isTrash
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property User $owner
 * @property Group $group
 * @property Task[] $tasks
 */
class Goal extends CActiveRecord
{
	const NAME_MAX_LENGTH = 255;
	
	const SCENARIO_COMPLETE = 'complete';
	const SCENARIO_DELETE = 'delete';
	const SCENARIO_INSERT = 'insert'; // default set by Yii
	const SCENARIO_READ = 'read';
	const SCENARIO_OWN = 'own';
	const SCENARIO_TRASH = 'trash';
	const SCENARIO_UNCOMPLETE = 'uncomplete';
	const SCENARIO_UNOWN = 'unown';
	const SCENARIO_UNTRASH = 'untrash';
	const SCENARIO_UPDATE = 'update';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Goal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'goal';
	}

	/**
	 * @return array behaviors that this model should behave as
	 */
	public function behaviors() {
		return array(
			// Update created and modified dates on before save events
			'CTimestampBehavior'=>array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created',
				'updateAttribute' => 'modified',
				'setUpdateOnCreate' => true,
			),
			// Set the groupId automatically when user is in only one group
			'DefaultGroupBehavior'=>array(
				'class' => 'ext.behaviors.DefaultGroupBehavior',
			),
			// Record C-UD operations to this record
			'ActiveRecordLogBehavior'=>array(
				'class' => 'ext.behaviors.ActiveRecordLogBehavior',
				'feedAttribute' => $this->name,
				'ignoreAttributes' => array('modified'),
			)
		);
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, groupId, isCompleted, isTrash', 
				'required'),
			
			// boolean ints default to 0 or 1
			array('isCompleted, isTrash',
				'default',
				'value' => 0,
			),
			
			// boolean ints can be 0 or 1
			array('isCompleted, isTrash',
				'numerical',
				'min' => 0,
				'max' => 1,
				'integerOnly'=>true),
			
			// integer only values
			array('groupId, ownerId', 
				'numerical', 
				'integerOnly'=>true,
				'allowEmpty'=>true),

			array('name', 
				'length', 
				'max'=>self::NAME_MAX_LENGTH
			),
			
			array('name', 
				'filter', 
				'filter'=>'trim'
			),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			// array('id, name, groupId, ownerId, isCompleted, isTrash, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'owner' => array(self::BELONGS_TO, 'User', 'ownerId'),
			'group' => array(self::BELONGS_TO, 'Group', 'groupId'),
			'tasks' => array(self::HAS_MANY, 'Task', 'goalId',
				'order' => 'tasks.priority ASC',
			),
			'tasksCount' => array(self::STAT, 'Task', 'goalId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => 'What do you want to do?',
			'groupId' => 'Group',
			'ownerId' => 'Owner',
			'isCompleted' => 'Is Completed',
			'isTrash' => 'Is Trash',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('groupId',$this->groupId);
		$criteria->compare('ownerId',$this->ownerId);
		$criteria->compare('isCompleted',$this->isCompleted);
		$criteria->compare('isTrash',$this->isTrash);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Mark the goal as completed, does not save
	 * @return void
	 */
	public function complete() {
		$this->isCompleted = 1;
		$this->setScenario(self::SCENARIO_COMPLETE);
		return $this;
	}
	
	/**
	 * Mark the goal as not completed, does not save
	 * @return void
	 */
	public function uncomplete() {
		$this->isCompleted = 0;
		$this->setScenario(self::SCENARIO_UNCOMPLETE);
		return $this;
	}
	
	/**
	 * Mark the goal as trash, does not save
	 * @return void
	 */
	public function trash() {
		$this->isTrash = 1;
		$this->setScenario(self::SCENARIO_TRASH);
		return $this;
	}
	
	/**
	 * Mark the goal as not trash, does not save
	 * @return void
	 */
	public function untrash() {
		$this->isTrash = 0;
		$this->setScenario(self::SCENARIO_UNTRASH);
		return $this;
	}
	
	/**
	 * Make the user as the owner, does not save
	 * @return void
	 */
	public function own() {
		$this->ownerId = Yii::app()->user->id;
		$this->setScenario(self::SCENARIO_OWN);
		return $this;
	}
	
	/**
	 * Mark the goal as not trash, does not save
	 * @return void
	 */
	public function unown() {
		$this->ownerId = null;
		$this->setScenario(self::SCENARIO_UNOWN);
		return $this;
	}
}
<?php

/**
 * This is the model class for table "group_user".
 *
 * The followings are the available columns in table 'group_user':
 * @property integer $id
 * @property integer $groupId
 * @property integer $userId
 * @property string $status
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Group $group
 * @property User $user
 */
class GroupUser extends CActiveRecord
{
	const STATUS_PENDING = 'Pending';
	const STATUS_ACTIVE = 'Active';
	const STATUS_INACTIVE = 'Inactive';

	/**
	 * Store of group maps
	 * @var array
	 */
	private static $_groups = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @return GroupUser the static model class
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
		return 'group_user';
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
		array('groupId, userId', 'required'),
		array('groupId, userId', 'numerical', 'integerOnly'=>true),
		array('status', 'length', 'max'=>15),
		array('created, modified', 'safe'),

		// TODO: default to pending after adding user confirmation
		array('status', 'default',
		 'value'=>self::STATUS_ACTIVE,
		 'setOnEmpty'=>false, 'on'=>'insert'),
		array('status', 'in', 'range'=>$this->getStatuses()),

		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, groupId, userId, status, created, modified', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'Group', 'groupId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'groupId' => 'Group',
			'userId' => 'User',
			'status' => 'Status',
			'created' => 'Invited on',
			'modified' => 'Invite last modified on',
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
		$criteria->compare('groupId',$this->groupId);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return array of the available statuses
	 */
	public static function getStatuses() {
		return array(self::STATUS_ACTIVE,
		self::STATUS_INACTIVE,
		self::STATUS_PENDING);
	}
	
	public function scopeGroup($groupId)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>'groupId = :groupId',
			'params' => array(':groupId' => $groupId),
		));
		return $this;
	}
	
	public function scopeUser($userId)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>'userId = :userId',
			'params' => array(':userId' => $userId),
		));
		return $this;
	}
	
	/**
	 * Get whether the user is a member of the group
	 * @param int $groupId
	 * @param int $userId
	 * @return boolean true if group member else false
	 */
	public function isGroupMember($groupId, $userId) {
		$groupuser = GroupUser::model()
			->scopeGroup($groupId)
			->scopeUser($userId)
			->find();
		return isset($groupuser);
	}
	
}
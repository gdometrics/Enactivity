<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $token
 * @property string $password
 * @property string $firstName
 * @property string $lastName
 * @property string $status
 * @property string $created
 * @property string $modified
 * @property string $lastLogin
 *
 * The followings are the available model relations:
 * @property Event[] $events
 * @property EventUser[] $eventUsers
 * @property GroupUser[] $groupUsers
 * @property Group[] $groups
 */
class User extends CActiveRecord
{
	const EMAIL_MAX_LENGTH = 50;
	const EMAIL_MIN_LENGTH = 3;
	
	const FIRSTNAME_MAX_LENGTH = 50;
	const FIRSTNAME_MIN_LENGTH = 2;
	
	const PASSWORD_MAX_LENGTH = 40;
	const PASSWORD_MIN_LENGTH = 4;
	
	const LASTNAME_MAX_LENGTH = 50;
	const LASTNAME_MIN_LENGTH = 2;
	
	const TOKEN_MAX_LENGTH = 40;
	
	const STATUS_PENDING = 'Pending';
	const STATUS_ACTIVE = 'Active';
	const STATUS_INACTIVE = 'Inactive';
	const STATUS_BANNED = 'Banned';
	const STATUS_MAX_LENGTH = 15;
	
	const USERNAME_MAX_LENGTH = 50;
	const USERNAME_MIN_LENGTH = 3;

	/******************************************************
	 * DO NOT CHANGE THE SALT!  YOU WILL BREAK ALL SIGN-INS
	 ******************************************************/
	const SALT = 'yom0mm4wasap455w0rd';

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('email', 'required', 'on' => 'invite'),
		array('email, token, username, password, firstName, lastName', 'required', 'on' => 'update'),

		array('token', 'length', 'max'=>self::TOKEN_MAX_LENGTH),
		
		array('username', 'unique', 'allowEmpty' => false, 'caseSensitive'=>false, 'on' => 'update'),
		array('username', 'length', 'min'=>self::USERNAME_MIN_LENGTH, 'max'=>self::USERNAME_MAX_LENGTH, 'on' => 'update'),
		array('username', 'match', 'allowEmpty' => false, 'pattern' => '/^[a-zA-Z][a-zA-Z0-9_]*\.?[a-zA-Z0-9_]*$/', 'on' => 'update'),

		array('email', 'unique', 'allowEmpty' => false, 'caseSensitive'=>false),
		array('email', 'length', 'min'=>self::EMAIL_MIN_LENGTH, 'max'=>self::EMAIL_MAX_LENGTH),
		array('email', 'email'),

		array('firstName', 'length', 'min'=>self::FIRSTNAME_MIN_LENGTH, 'max'=>self::FIRSTNAME_MAX_LENGTH),
		array('lastName', 'length', 'min'=>self::LASTNAME_MIN_LENGTH, 'max'=>self::LASTNAME_MAX_LENGTH),
		array('firstName, lastName', 'match', 'allowEmpty' => false, 'pattern' => '/^[a-zA-Z]*$/'),
			
		array('password', 'length', 'min'=>self::PASSWORD_MIN_LENGTH, 'max'=>self::PASSWORD_MAX_LENGTH),

		array('status', 'length', 'max'=>self::STATUS_MAX_LENGTH),
		array('status', 'default',
			'value'=>self::STATUS_PENDING,
			'setOnEmpty'=>false, 'on'=>'insert'
		),
		array('status', 'in', 'range'=>array(
			self::STATUS_PENDING,
			self::STATUS_ACTIVE,
			self::STATUS_INACTIVE
		)
		),
		
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, username, email, password, firstName, lastName, status, created, modified, lastLogin', 'safe', 'on'=>'search'),
		);
		//FIXME: users can use restricted words for username
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'createdEvents' => array(self::HAS_MANY, 'Event', 'creatorId'),
		
			'events' => array(self::MANY_MANY, 'Event', 
				'event_user(userId, eventId)'),
			'eventUsers' => array(self::HAS_MANY, 'EventUser', 'userId'),
			
			'groups' => array(self::MANY_MANY, 'Group', 
				'group_user(userId, groupId)'),
			'groupUsers' => array(self::HAS_MANY, 'GroupUser', 'userId'),
		);
		//TODO: stats: # future events 
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'email' => 'Email',
			'token' => 'Token',
			'password' => 'Password',
			'firstName' => 'First name',
			'lastName' => 'Last name',
			'status' => 'Status',
			'created' => 'Created',
			'modified' => 'Last modified',
			'lastLogin' => 'Last login',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('lastLogin',$this->lastLogin,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			//lowercase unique values
			$this->email = strtolower($this->email);
			if(isset($this->username)) { //to prevent nulls turning into ""
				$this->username = strtolower($this->username);	
			}
			return true;
		}
		return false;
	}

	protected function afterValidate() {
		if(parent::afterValidate()) {
			return true;
		}
		return false;
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->created = new CDbExpression('NOW()');
				$this->modified = new CDbExpression('NOW()');
				
				//encrypt token and password
				$this->token = $this->encrypt(time(), '');
				//$this->password = $this->encrypt($this->password, $this->token);
			}
			else {
				//TODO: move to controller so login updates won't change it
				$this->modified = new CDbExpression('NOW()');
				$this->password = $this->encrypt($this->password, $this->token);
			}
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function isPassword($password)
	{
		return $this->encrypt($password, $this->token) === $this->password;
	}
	
	/**
	 * Checks if the user has the given status
	 * @param string the status to check
	 * @return boolean whether the user is of the given status
	 */
	public function isStatus($status)
	{
		return $this->status === $status;
	}
	
	/**
	 * Is the user's status 'Active'?
	 * @return boolean whether the user is active
	 */
	public function isActive() {
		return $this->isStatus(self::STATUS_ACTIVE);
	}
	
/**
	 * Is the user's status 'Banned'?
	 * @return boolean whether the user is banned
	 */
	public function isBanned() {
		return $this->isStatus(self::STATUS_BANNED);
	}

	/**
	 * Encrypt the given value
	 * @param string $value
	 * @param string $token
	 * @return encrypted value
	 */
	public function encrypt($value, $token) {
		return sha1(self::SALT . $token . $value);
	}

	/**
	 * Get the full name of the user (i.e. First Last)
	 * @return String FirstName LastName or NULL if neither is set
	 */
	public function fullName() {
		if($this->firstName != NULL 
		&& $this->lastName != NULL) {
			return $this->firstName . ' ' . $this->lastName;
		}
		else {
			return NULL;
		}
	}
	
	/**
	 * Get the url for viewing this user
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('user/view', array(
            'id'=>$this->id,
            'username'=>$this->username,
		));
	}
	
	/**
	 * Return a list of the available statuses
	 */
	public static function getStatuses() {
		return array(self::STATUS_ACTIVE,
			self::STATUS_INACTIVE, 
			self::STATUS_PENDING,
			self::STATUS_BANNED);
	}
	
	/**
	 * Invite a user to the web app
	 * @param string userName the name of the user sending the invite
	 * @param string groupName the name of the group
	 */
	public function invite($userName, $groupName) {
		//send invite email
		$from = "no-reply@poncla.com";
		$subject = "{$userName} invites you to join {$groupName} on Poncla";
		$body = $userName . " has invited you to join the {$groupName} group on"
		. " Poncla. To accept this invitation, go to "
		. Yii::app()->request->hostInfo . "/index.php/user/register?token=" . $this->token 
		. " and complete your registration.";
		
		$headers = 'From: no-reply@poncla.com';
		mail($this->email, $subject, $body, $headers);
	}
}
<?php
/**
 * UserIdentity represents the data needed to identify a user.
 * @author Ajay Sharma
 *
 */
class UserIdentity extends CUserIdentity
{
	// Additional error codes to CBaseUserIdentity
	const ERROR_STATUS_NOT_ACTIVE=3;
	const ERROR_STATUS_BANNED=4;
	
	private $_id;
	
	/**
	 * Authenticates a user based on {@link username} (which is actually
	 * an email) and {@link password}.
	 * This method is required by {@link IUserIdentity}.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{		
		$user = User::model()->findByAttributes(
			array(
				'email' => $this->username,
			)
		);
		
		if(is_null($user)) { // user does not exist
			$this->errorCode = self::ERROR_USERNAME_INVALID;	
		}
		else if($user->isPassword($this->password)) { //valid log in
			// check user status, re-activate inactive user
			if($user->isBanned) {
				$this->errorCode = self::ERROR_STATUS_BANNED;
			}
			else if($user->isActive) {
				// Set useful current user values  
				$this->_id = $user->id;
				$this->errorCode = self::ERROR_NONE;
			}
			else {
				$this->errorCode = self::ERROR_STATUS_NOT_ACTIVE;
			}
		}
		else { //user exists, but invalid log in attempt
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		
		return $this->errorCode == self::ERROR_NONE;
	}

	public function getId()
	{
		return $this->_id;
	}
}
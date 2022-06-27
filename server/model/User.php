<?php

class UserException extends Exception{}

class User {
    private $_userid;
	private $_uname;
	private $_upassword;
	private $_ustatus;

    public function __construct($userid, $uname, $upassword, $ustatus)
    {
        $this->setUserId($userid);
		$this->setUName($uname);
		$this->setUPassword($upassword);
		$this->setUStatus($ustatus);
    }

    public function getUserId(){
		return $this->_userid;
	}

	public function getUName(){
		return $this->_uname;
	}

	public function getUPassword(){
		return $this->_upassword;
	}

	public function getUStatus(){
		return $this->_ustatus;
	}

	public function setUserId($userid) {
		
		if(strlen($userid) < 1 || strlen($userid) > 255) {
			throw new UserException("User id error");
		}
		$this->_userid = $userid;
	}

	public function setUName($uname) {
		if(strlen($uname) < 1 || strlen($uname) > 255) {
			throw new UserException("User name error");
		}
		$this->_uname = $uname;
	}

    public function setUPassword($upassword) {
		if(strlen($upassword) < 1 || strlen($upassword) > 255) {
			throw new UserException("User password error");
		}
		$this->_upassword = $upassword;
	}

    public function setUStatus($ustatus) {
		$this->_ustatus = $ustatus;
	}

    public function returnUserAsArray() {
		$user= array();
		$user['userid'] = $this->getUserId();
		$user['uname'] = $this->getUName();
		$user['upassword'] = $this->getUPassword();
		$user['ustatus'] = $this->getUStatus();
		return $user;
	}
	
}

?>
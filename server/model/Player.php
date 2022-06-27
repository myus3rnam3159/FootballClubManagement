<?php
#Player Exception để catch lỗi
class PlayerException extends Exception{}

class Player {
    private $_playerid;
	private $_fullname;
	private $_clubid;
	private $_dob;
	private $_position;
	private $_nationality;
    private $_number;

    public function __construct($playerid, $fullname, $clubid, $dob, $position, $nationality, $number )
    {
        $this->setPlayerId($playerid);
		$this->setFullName($fullname);
		$this->setClubId($clubid);
		$this->setDOB($dob);
		$this->setPosition($position);
		$this->setNationality($nationality);
		$this->setNumber($number);
    }

	public function getPlayerId(){
		return $this->_playerid;
	}

	public function getFullName(){
		return $this->_fullname;
	}

	public function getClubId(){
		return $this->_clubid;
	}

	public function getDOB(){
		return $this->_dob;
	}

	public function getPosition(){
		return $this->_position;
	}

	public function getNationality(){
		return $this->_nationality;
	}

	public function getNumber(){
		return $this->_number;
	}

	public function setPlayerId($playerid) {
		if(($playerid !== null) && (!is_numeric($playerid) || $playerid <= 0 || $playerid > 9223372036854775807 || $this->_playerid !== null)) {
			throw new PlayerException("Player id error");
		}
		$this->_playerid = $playerid;
	}

	public function setFullName($fullname) {
		
		if(strlen($fullname) < 1 || strlen($fullname) > 255) {
			throw new PlayerException("Player full name error");
		}
		$this->_fullname = $fullname;
	}

	public function setClubId($clubid) {
		if(($clubid !== null) && (!is_numeric($clubid) || $clubid <= 0 || $clubid > 9223372036854775807 || $this->_clubid !== null)) {
			throw new PlayerException("Player club id error");
		}
		$this->_clubid = $clubid;
	}

	public function setDOB($dob) {
    	if($dob !== null) {
			if(!date_create_from_format('Y-m-d H:i:s', $dob) || date_format(date_create_from_format('Y-m-d H:i:s', $dob), 'Y-m-d H:i:s') != $dob) {
				throw new PlayerException("Player dob error");
			}
			$this->_dob = $dob;
    	}
	}

	public function setPosition($position) {
		if(is_numeric($position)) {
			throw new PlayerException("Player position is not characters");
		}
		$this->_position = $position;
	}

	public function setNationality($nationality) {
		if(strlen($nationality) < 1 || strlen($nationality) > 255) {
			throw new PlayerException("Player Nationality error");
		}
		$this->_nationality = $nationality;
	}

	public function setNumber($number){
		if(($number !== null) && (!is_numeric($number) || $number <= 0 || $number > 9223372036854775807 || $this->_number !== null)) {
			throw new PlayerException("Player id error");
		}
		$this->_number = $number;
	}

	public function returnPlayerAsArray() {
		$player= array();
		$player['playerid'] = $this->getPlayerId();
		$player['fullname'] = $this->getFullName();
		$player['clubid'] = $this->getClubId();
		$player['dob'] = $this->getDOB();
		$player['position'] = $this->getPosition();
		$player['nationality'] = $this->getNationality();
		$player['number'] = $this->getNumber();
		return $player;
	}
}
?>
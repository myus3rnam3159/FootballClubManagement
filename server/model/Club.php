<?php
#club Exception để catch lỗi
class ClubException extends Exception{}

class Club {
    private $_clubid;
	private $_clubname;
	private $_shortname;
	private $_stadiumid;
	private $_coachid;

    public function __construct($clubid, $clubname, $shortname, $stadiumid, $coachid)
    {
        $this->setClubId($clubid);
		$this->setClubName($clubname);
		$this->setShortName($shortname);
		$this->setStadiumId($stadiumid);
		$this->setCoachId($coachid);
    }

	public function getClubId(){
		return $this->_clubid;
	}

	public function getClubName(){
		return $this->_clubname;
	}

	public function getShortName(){
		return $this->_shortname;
	}

	public function getStadiumId(){
		return $this->_stadiumid;
	}

	public function getCoachId(){
		return $this->_coachid;
	}

	public function setClubId($clubid) {
		if(($clubid !== null) && (!is_numeric($clubid) || $clubid <= 0 || $clubid > 9223372036854775807 || $this->_clubid !== null)) {
			throw new ClubException("Club id error");
		}
		$this->_clubid = $clubid;
	}

	public function setClubName($clubname) {
		
		if(strlen($clubname) < 1 || strlen($clubname) > 255) {
			throw new ClubException("Club name error");
		}
		$this->_clubname = $clubname;
	}

	public function setShortName($shortname) {
		
		if(strlen($shortname) < 1 || strlen($shortname) > 255) {
			throw new ClubException("Club short name error");
		}
		$this->_shortname = $shortname;
	}

	public function setStadiumId($stadiumid) {
		if( ($stadiumid !== null ) && (strlen($stadiumid) < 1 || strlen($stadiumid) > 255)) {
			throw new ClubException("Club stadium id error");
		}
		$this->_stadiumid = $stadiumid;
	}

	public function setCoachId($coachid) {
		if(($coachid !== null) && (!is_numeric($coachid) || $coachid <= 0 || $coachid > 9223372036854775807)) {
			throw new ClubException("Club coach id error");
		}
		$this->_coachid = $coachid;
	}

	public function returnClubAsArray() {
		$club= array();
		$club['clubid'] = $this->getClubId();
		$club['clubname'] = $this->getClubName();
		$club['shortname'] = $this->getShortName();
		$club['stadiumid'] = $this->getStadiumId();
		$club['coachid'] = $this->getCoachId();
		return $club;
	}
}
?>
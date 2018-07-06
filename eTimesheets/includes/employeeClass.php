<?php

class Employee
{
	private $pin; // the pin is private so that the checkPin() method must be used

	public $name;
	public $uid;

	public function __construct(String $uname) {
		global $dbc; // get access to the dbc

		$stmt = $dbc->prepare('SELECT * FROM employees WHERE uname = ?'); // prepare a request
		$stmt->bind_param('s', $uname);
		$stmt->execute();

		$result = $stmt->get_result();
		if($result->num_rows !== 1) return false; // if 0, or >1 matchs were found, something's gome wrong so ret0

		$stmt->close();

		$result = $result->fetch_assoc();

		$this->pin = $result['pin']; // save the pin,
		$this->name = $result['name']; // the name,
		$this->uid = $result['uid']; // and the uid


		return true;
	}

	public function checkPin(String $inputPin) // check if an entered pin in correct
	{
		if ($this->pin === $inputPin) {
			return true;
		} else {
			return false;
		}
	}

	public function getEvents($dateBegin = null, $dateEnd = null)
	{
		global $dbc; // get access to the dbc

		if ($dateBegin == null) {
			$dateBegin = '0000-00-00 00:00:00';
		}

		if ($dateEnd == null) {
			$dateEnd   = '9999-12-31 23:59:59';
		}

		$stmt = $dbc->prepare('SELECT * FROM timesheet WHERE uid = ? AND datetime between ? AND ? order BY datetime DESC;'); // prepare a request
		$stmt->bind_param('iss', $this->uid, $dateBegin, $dateEnd);
		$stmt->execute();

		$result = $stmt->get_result();
		if($result->num_rows === 0) return false; // if no matchs were found, something's gome wrong so ret0

		$stmt->close();

		foreach ($result->fetch_all() as $row) { // loop through each row
			$ret[] = new Event($row[0], $row[1], $row[2], $row[3]);
		}

		return $ret;
	}
}

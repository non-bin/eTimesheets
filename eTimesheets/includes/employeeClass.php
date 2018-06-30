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
}

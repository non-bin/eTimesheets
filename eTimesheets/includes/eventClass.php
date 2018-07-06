<?php

class Event
{
	public $id;
	public $uid;
	public $dateTime;
	public $type;

	public function __construct(Int $id, Int $uid, String $dateTime, String $type) {
		$this->id = $id;
		$this->uid = $uid;
		$this->dateTime = $dateTime;
		$this->type = $type;
	}
}

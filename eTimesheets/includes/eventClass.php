<?php

/**
 * Harry Jacka eventClass.php 1.0 (created 6/7/18)
 *
 * handle all event related actions, including storing
 * event information, and handeling updates to the event
 */

class Event // event object definition
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

<?php

class Employee
{
    private $pin; // the pin is private so that the checkPin() method must be used

    public $name;
    public $uid;

    public function __construct(String $uid) {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('SELECT * FROM `employees` WHERE `uid` = ?'); // prepare a request
        $stmt->bind_param('s', $uid);
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

    public function getEvents($dateBegin = '0000-00-00 00:00:00', $dateEnd = '9999-12-31 23:59:59') // get an array of event objects for this emp within a date range
    {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('SELECT * FROM `timesheet` WHERE `uid` = ? AND `datetime` between ? AND ? ORDER BY `datetime`;'); // prepare a request
        $stmt->bind_param('iss', $this->uid, $dateBegin, $dateEnd);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows === 0) return false; // if no matchs were found, something's gome wrong so ret0

        $stmt->close();

        foreach ($result->fetch_all() as $row) { // loop through each row
            $ret[] = new Event($row[0], $row[1], $row[2], $row[3]); // and create an event object for each event found
        }

        return $ret; // return the array of events
    }

    public function addEvent(String $dateTime, String $type) // add an event as this emp
    {
        global $dbc; // get access to the dbc

        if (!($type == 'in' || $type == 'ou' || $type == 'bl' || $type == 'el')) { // if the event type is invalid
            return false;
        }

        $stmt = $dbc->prepare('INSERT INTO `eTimesheets`.`timesheet` (`uid`, `datetime`, `event`) VALUES (?, ?, ?);');
        $stmt->bind_param('iss', $this->uid, $dateTime, $type);
        $stmt->execute();

        if ($stmt->affected_rows == 1) { // if the request worked properly
            return true;
        }

        return false; // something went wrong
    }

    public function getLatestEvents()
    {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('SELECT * FROM `timesheet` WHERE `datetime` IN ( SELECT MAX( `datetime` ) FROM `timesheet` WHERE `uid` = ? ) AND `uid` = ?;'); // prepare a request
        $stmt->bind_param('ii', $this->uid, $this->uid);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows === 0) return false; // if no matchs were found, something's gome wrong so ret0

        $stmt->close();

        foreach ($result->fetch_all() as $row) { // loop through each row
            $ret[] = new Event($row[0], $row[1], $row[2], $row[3]); // and create an event object for each event found
        }

        return $ret; // return the array of events
    }

    public function predictEvent() // attempt to predict what event this emp will log next
    {
        $latestTypes = [];
        $latestEvents = $this->getLatestEvents();

        foreach ($latestEvents as $value) {
            $latestTypes[] = $value->type;
        }

        if (in_array($latestTypes, 'ou')) {
            return 'in';
        }
        if (in_array($latestTypes, 'el')) {
            return 'ou';
        }
        if (in_array($latestTypes, 'bl')) {
            return 'el';
        }
        return 'bl';
    }
}

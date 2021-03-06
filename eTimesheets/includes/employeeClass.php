<?php

/**
 * Alice Jacka employeeClass.php 2.0 (created 30/6/18)
 *
 * handle everything an employee may perform
 */

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

    // todo before 1/1/3001: increase this number       \           /
    public function getEvents(Int $dateBegin = 0, Int $dateEnd = 32535215999) // get an array of event objects for this emp within a date range
    {
        global $dbc; // get access to the dbc

        $dateBegin = sqlDateTime($dateBegin);
        $dateEnd   = sqlDateTime($dateEnd);

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
            return 'invalid type: ' . $type; // return an error
        }

        $stmt = $dbc->prepare('INSERT INTO `eTimesheets`.`timesheet` (`uid`, `datetime`, `event`) VALUES (?, ?, ?);');
        $stmt->bind_param('iss', $this->uid, $dateTime, $type);
        $stmt->execute();

        if ($stmt->affected_rows == 1) { // if the request worked properly
            return true;
        }

        return 'affected_rows "' . $stmt->affected_rows . '" is not 1'; // return an error
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

        if ($latestEvents) { // if the user has logged anything previously
            foreach ($latestEvents as $value) { // extract the type values
                $latestTypes[] = $value->type;
            }

            if (in_array('ou', $latestTypes)) {
                return 'in';
            }
            if (in_array('el', $latestTypes)) {
                return 'ou';
            }
            if (in_array('bl', $latestTypes)) {
                return 'el';
            }
            return 'bl';
        } else { // if this is the first event
            return 'in';
        }
    }

    public function currentStatus() // determine the employee's current status
    {
        $latestTypes = [];
        $latestEvents = $this->getLatestEvents();

        if ($latestEvents) { // if the user has logged anything previously
            foreach ($latestEvents as $value) { // extract the type values
                $latestTypes[] = $value->type;
            }

            if (in_array('ou', $latestTypes)) {
                return 'out';
            }
            if (in_array('el', $latestTypes)) {
                return 'in';
            }
            if (in_array('bl', $latestTypes)) {
                return 'lunch';
            }
            return 'in';
        } else { // if there are no events
            return 'out';
        }
    }

    public function eventsInCycle() // retrive a list of events in a given cycle
    {
        $cycle = getCycleInfo();
        return $this->getEvents($cycle['begin'], $cycle['end']);
    }

    public function extraWorkInCycle() // calculate the extra/missed time this cycle
    {
        return(($GLOBALS['config']['cycle']['expectedWork'] - $this->projectWork()) * -1);
    }

    public function workInCycle(Int $now = null) // calculate how long the employee has worked this cycle
    {
        defaultTo($now, $GLOBALS['config']['debug']['timeOverride']); // if no date was given, use the curent one

        $events = $this->eventsInCycle($now); // get all events in the requested cycle

        if ($events) {
            $signedIn = false;
            $output   = 0;
            $lastTime = 0;
            foreach ($events as $event) {
                if (in_array($event->type, ['in', 'el'])) { // if the user is signing in
                    $signedIn = true;
                    $lastTime = $event->unixTime; // save the time of the sign in
                } else {
                    $signedIn = false;
                    $output += $event->unixTime - $lastTime; // add the time they were signed in, to the output
                }
            }

            if ($signedIn) { // if the emp is currently working
                $output += $config['debug']['timeOverride'] - $event->unixTime; // add how long they have been signed in, to the output
            }

            return $output;
        } else {
            return 0;
        }
    }

    public function projectWork() // predict how long the employee will work this cycle
    {
        return ($this->workInCycle() / getCycleInfo()['startDif']) * $GLOBALS['config']['cycle']['length'];
    }
}

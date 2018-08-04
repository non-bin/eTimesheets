<?php

/**
 * Alice Jacka eventClass.php 2.0 (created 6/7/18)
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

    public function __construct(Int $id, Int $uid = null, String $dateTime = null, String $type = null) {
        if (isset($uid, $dateTime, $type)) {
            $this->id       = $id;
            $this->uid      = $uid;
            $this->dateTime = $dateTime;
            $this->unixTime = strtotime($dateTime);
            $this->type     = $type;
        } else {
            global $dbc; // get access to the dbc

            $stmt = $dbc->prepare('SELECT * FROM `timesheet` WHERE `id` = ?;'); // prepare a request
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();

            $result = $result->fetch_assoc();

            $this->id       = $result['id'];
            $this->uid      = $result['uid'];
            $this->dateTime = $result['datetime'];
            $this->unixTime = strtotime($result['datetime']);
            $this->type     = $result['event'];
        }
    }

    public function update(String $dateTime, String $type) // update the event
    {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('UPDATE `eTimesheets`.`timesheet` SET `datetime` = ? , `event` = ? WHERE `id` = ?;');
        $stmt->bind_param('ssi', $dateTime, $type, $this->id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) { // if the request worked properly
            return true;
        }

        return false; // return an error
    }

    public function delete() // delete the event
    {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('DELETE FROM `eTimesheets`.`timesheet` WHERE `id` = ?;');
        $stmt->bind_param('i', $this->id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) { // if the request worked properly
            return true;
        }

        return false; // return an error
    }
}

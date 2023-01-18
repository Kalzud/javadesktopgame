<?php

class Request
{
    protected $requestID, $sender, $receiver;

    /**
     * User constructor.
     * @param $requestID
     * @param $sender
     * @param $receiver
     */
    public function __construct($dbRow)
    {
        $this->requestID = $dbRow['request_id'];
        $this->sender = $dbRow['sender'];
        $this->receiver = $dbRow['receiver'];

    }

    /**
     * @return mixed
     */
    public function getRequestID(): mixed
    {
        return $this->requestID;
    }

    /**
     * @return mixed
     */
    public function getSender(): mixed
    {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getReceiver(): mixed
    {
        return $this->receiver;
    }

}
<?php

namespace Send;

use Services_Twilio;
use Services_Twilio_RestException;

class Call
{
    protected $client;

    protected $sid = "AC35ee67131238dad056cdc3ae47f7c4e5";

    protected $token = "3f09d23d728dc67db234b506b7a3ff91";

    protected $from = '6416727291';

    protected $to = "6412262329";

    public function __construct()
    {
        $this->client = new Services_Twilio($this->sid, $this->token);
    }

    public function call()
    {
        $this->client->account->calls->create($this->from, $this->to, "https://demo.twilio.com/welcome/voice/", array(
            'From' => "+16416727295",
            'To' => "+16412262329",
            'Url' => "http://remindcloud.com",
            'Method' => "GET",
            'FallbackMethod' => "GET",
            'StatusCallbackMethod' => "GET",
            'Record' => "false",
        ));
    }

    public function callByNumber()
    {
        $call = $this->client->account->calls->create($this->from, "+14155551212", "http://demo.twilio.com/docs/voice.xml", array());
        echo $call->sid;
    }

    public function callExtension()
    {
        $call = $this->client->account->calls->create($this->from, "+14155551212", "http://demo.twilio.com/docs/voice.xml", array(
            "SendDigits" => "1234#",
            "Method" => "GET"
        ));
        echo $call->sid;
    }

    public function callByClientName()
    {
        $call = $this->client->account->calls->create($this->from, "client:tommy", "http://demo.twilio.com/docs/voice.xml", array());
        echo $call->sid;
    }
} 
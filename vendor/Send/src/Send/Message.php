<?php
namespace Send;
use Services_Twilio;
use Services_Twilio_RestException;

class Message
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

    public function sendSMS()
    {
        $people = array(
            "6412262329" => "Johnny",
        );

        foreach ($people as $to => $name)
        {

            $body = "Bad news $name, the server is down and it needs your help";
            try
            {
                $this->client->account->sms_messages->create($this->from, $to, $body);
                echo "Sent message to $name";
            }
            catch (Services_Twilio_RestException $e)
            {
                echo $e->getMessage();
            }

        }
    }

    public function sendOther()
    {
        $this->client->account->messages->sendMessage($this->from, $this->to, "Jenny please?! I love you <3");
    }
}

?>

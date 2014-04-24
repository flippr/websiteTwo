<?php

namespace MssMessage\Mapper\Birthdate;

use Doctrine\DBAL\Connection,
    MssMessage\Message;

class DoctrineDbal implements MapperInterface
{
    /**
     * @var Doctrine\DBAL\Connection
     */
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function saveMessageBirthdate(Message $message)
    {
        $extra = $message->getExtraData();
        $extra_data = $extra['raw'];
        $year = date('Y');
        
        $data  = array(
            'dsid'           =>  $extra_data['patientDsid'],
            'patient_rid' => $extra_data['patientRid'],
            'birthday_year' => $year,   		
            'message_id'      => $message->getId()
        );

        $this->conn->insert('message_birthdate', $data);
        

    }
}
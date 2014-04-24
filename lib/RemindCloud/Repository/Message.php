<?php
namespace RemindCloud\Repository;

use Doctrine\ORM\Query;
use MssMessage\Message as MssMessage;
use RemindCloud\ORM\EntityRepository;

class Message extends EntityRepository
{
    public function findGridArrayByCompany($clientRid)
    {
        $query = $this->_em->createQuery("
            SELECT
                m.id,
                m.subject,
                ct.name AS contact_name,
                mt.name AS message_name,
                m.queuedAt,
                m.sentAt
            FROM MssMessage\Entity\Message m
            LEFT JOIN m.sent s
            LEFT JOIN m.contactType ct
            LEFT JOIN m.messageType mt
            WHERE m.client = ?1
        ");
        return $query->execute(array(1 => $clientRid));
    }

    public function getInboxDataTableQuery($companyId)
    {
        $messages = array(
            MssMessage::MESSAGE_TYPE_CONTACT_US,
            MssMessage::MESSAGE_TYPE_APPOINTMENT_REQUEST,
            MssMessage::MESSAGE_TYPE_BOARDING_REQUEST,
            MssMessage::MESSAGE_TYPE_REFILL_REQUEST,
            MssMessage::MESSAGE_TYPE_CUSTOM_FORM,
            MssMessage::MESSAGE_TYPE_ACCOUNT_CREATED
        );

        return $this->_em->createQuery("
            SELECT
                m.sentAt,
                m.result,
                TRIM(CONCAT(m.senderName, CONCAT(' <', CONCAT(m.sender, '>')))) AS sender,
                m.subject,
                m.id AS message_id,
                ct.name AS contact_type,
                mt.name AS message_type,
                TRIM(CONCAT(c.firstName, CONCAT(' ', CONCAT(c.lastName, CONCAT(' - ', c.email))))) as recipient
                 
            FROM RemindCloud\Entity\Message m
            LEFT JOIN m.contactType ct
            LEFT JOIN m.messageType mt
            LEFT JOIN m.client AS c
            
            WHERE m.company = {$companyId}
            AND mt.id IN (" . implode(',', $messages) . ")
            AND m.transport =" . MssMessage::TRANSPORT_TYPE_REMINDCLOUD . "
            AND m.sentAt IS NOT NULL 
            ORDER BY m.sentAt DESC
        ");
    }

    public function getDataTableQuery($companyId)
    {
        return $this->_em->createQuery("
              SELECT
                m.sentAt,
                m.result,
                TRIM(CONCAT(m.senderName, CONCAT(' <', CONCAT(m.sender, '>')))) AS sender,
                m.subject,
                m.id AS message_id,
                ct.name AS contact_type,
                mt.name AS message_type,
                TRIM(CONCAT(c.firstName, CONCAT(' ', CONCAT(c.lastName, CONCAT(' - ', c.email))))) as recipient
                
            FROM RemindCloud\Entity\Message m
            LEFT JOIN m.contactType ct
            LEFT JOIN m.messageType mt
            LEFT JOIN m.client AS c
            
            WHERE m.company = {$companyId}
            AND m.transport =" . MssMessage::TRANSPORT_TYPE_REMINDCLOUD . "
            AND m.sentAt IS NOT NULL 
            ORDER BY m.queuedAt DESC
        ");
    }
}
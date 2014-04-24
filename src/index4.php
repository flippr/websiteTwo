<?php
//use Doctrine\ORM\EntityManager;
require_once "bootstrap.php";
//require_once "../Bootstrap.php";
//include '../bootstrap.php';

$message = new \Send\Message();
$id = 1;
//$em = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper();
//$user = $em->find('RemindCloud/Entity/Newsletter', $id);
$messages = $entityManager->getRepository('Newsletter')->findAll();
echo $messages;

/*$d2helper = $this->_helper->get('d2.helper');
$repository = $d2helper->getEntityManager()->getRepository('Newsletter');
//$productRepository = $entityManager->getRepository('Newsletter');
$products = $repository->findAll();

foreach ($products as $product)
{
    echo sprintf("-%s\n", $product->getName());
}*/
//$product = new RemindCloud\Entity\Newsletter;
//$product->findOneBy(array('id' => 1));
//$message->sendSms();
//$product = $entityManager->getRepository('Newsletter')->findOneBy(array('id' => 1));

/* Send an SMS using Twilio. You can run this file 3 different ways:
 *
 * - Save it as sendnotifications.php and at the command line, run
 *        php sendnotifications.php
 *
 * - Upload it to a web host and load mywebhost.com/sendnotifications.php
 *   in a web browser.
 * - Download a local server like WAMP, MAMP or XAMPP. Point the web root
 *   directory to the folder containing this file, and load
 *   localhost:8888/sendnotifications.php in a web browser.
 *
// Include the PHP Twilio library. You need to download the library from
// twilio.com/docs/libraries, and move it into the folder containing this
// file.
require "../vendor/twilio/sdk/Services/Twilio.php";
//include('vendor/Nexmo-PHP-lib/NexmoMessage.php');
// Set our AccountSid and AuthToken from twilio.com/user/account
$AccountSid = "AC35ee67131238dad056cdc3ae47f7c4e5";
$AuthToken = "3f09d23d728dc67db234b506b7a3ff91";

// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);

/* Your Twilio Number or Outgoing Caller ID *
$from = '6416727295';

// make an associative array of server admins. Feel free to change/add your
// own phone number and name here.
$people = array(
    "6412262329" => "Andrea"
);

// Iterate over all admins in the $people array. $to is the phone number,
// $name is the user's name
foreach ($people as $to => $name)
{
    // Send a new outgoing SMS *
    $body = "Goodd news $name, I LOVE YOU!";
    $client->account->sms_messages->create($from, $to, $body);
    echo "Sent message to $name";
}*/

?>
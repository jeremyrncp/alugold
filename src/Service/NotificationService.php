<?php

namespace App\Service;

use App\Entity\Proposition;
use Pusher\PushNotifications\PushNotifications;

class NotificationService
{
    const SUBJECT = 'sale_validate';

    private $pushNotifications;

    public function __construct(PushNotifications $pushNotifications)
    {
        $this->pushNotifications = $pushNotifications;
    }

    public function send(Proposition $proposition)
    {
        return $this->pushNotifications->publish([self::SUBJECT],[
            "web" => array(
            "notification" => array(
                "title" => "Nouvelle vente",
                "body" => "Une vente de " . $proposition->getAmount() . " euros vient d'être validée par " . $proposition->getVendor()->getFirstname() . " " . $proposition->getVendor()->getDepartment()
            )
        )]);
    }
}
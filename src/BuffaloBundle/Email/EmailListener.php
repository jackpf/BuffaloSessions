<?php

namespace BuffaloBundle\Email;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;

class EmailListener
{
    private $mailer;

    private $mailerUser;

    public function __construct(Mailer $mailer, $mailerUser)
    {
        $this->mailer = $mailer;
        $this->mailerUser = $mailerUser;
    }
}
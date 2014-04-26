<?php

interface MessageSender
{
    public function send($subject, $sender, $recipient, $body);
}
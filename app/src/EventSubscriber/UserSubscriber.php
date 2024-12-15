<?php

namespace App\EventSubscriber;

use App\Event\RegisteredUserEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserSubscriber implements EventSubscriberInterface
{
	public function __construct(private Mailer $mailer) {}

	public static function getSubscribedEvents()
	{
		return [
			RegisteredUserEvent::NAME => 'onUserRegister'
		];
	}

	/**
	 * @param RegisteredUserEvent $registeredUserEvent
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function onUserRegister(RegisteredUserEvent $registeredUserEvent)
	{
		$this->mailer->sendConfirmationMessage($registeredUserEvent->getRegisteredUser());
	}
}

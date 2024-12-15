<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RegisteredUserEvent extends Event
{
	public const NAME = 'user.register';

	public function __construct(private User $registeredUser) {}


	public function getRegisteredUser(): User
	{
		return $this->registeredUser;
	}
}

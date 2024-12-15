<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use App\Entity\User;

class Mailer
{
    public const FROM_ADDRESS = 'test@test.com';

    public function __construct(private MailerInterface $mailer, private Environment $twig)
    {}

    public function sendConfirmationMessage(User $user)
    {
        $messageBody = $this->twig->render('security/confirmation.html.twig', [
            'user' => $user
        ]);

        $email = (new Email())
            ->from(self::FROM_ADDRESS)
            ->to('zick1990.zzz@gmail.com')
            ->subject('Вы успешно прошли регистрацию!')
            ->text('Текст письма.....')
            ->html($messageBody);

        $this->mailer->send($email);
    }
}

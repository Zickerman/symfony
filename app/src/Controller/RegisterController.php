<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\RegisteredUserEvent;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        EventDispatcherInterface $eventDispatcher,
        UserPasswordHasherInterface $passwordEncoder,
        Request $request,
        CodeGenerator $codeGenerator,
        EntityManagerInterface $em
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $passwordEncoder->hashPassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setConfirmationCode($codeGenerator->getConfirmationCode());

            $em->persist($user);
            $em->flush();

            $userRegisteredEvent = new RegisteredUserEvent($user);
            $eventDispatcher->dispatch($userRegisteredEvent, RegisteredUserEvent::NAME);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/confirm/{code}', name: 'email_confirmation')]
    public function confirmEmail(UserRepository $userRepository, string $code, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy(['confirmationCode' => $code]);

        if ($user === null) {
            return new Response('404');
        }

        $user->setEnable(true);
        $user->setConfirmationCode('');

        $em->flush();

        return $this->render('security/account_confirm.html.twig', [
            'user' => $user,
        ]);
    }
}

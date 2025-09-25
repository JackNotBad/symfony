<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class SigninController extends AbstractController
{
    #[Route('/signin', name: 'app_signin')]
    public function index(Request $req, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(SigninType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // Faire que le mot de passe soit caché/hashé
            // Vérifiez que le user posséde bien use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
            // Vérifiez que le fichier packages\security.yaml posséde bien le  https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords -  password_hashers: - Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
            $plaintextPassword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();
            // Afficher un message de validation
        } else {
            // Afficher une erreur
        }
        return $this->render('signin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

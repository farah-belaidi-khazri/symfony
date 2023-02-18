<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route("/registration", name: "app_registration")]
    public function registration(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher,FlashyNotifier $flashyNotifier): Response 
    {   $user = new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->HandleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){
            
            $user ->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
                );
            
            $entityManager =  $doctrine ->getManager();
            $entityManager -> persist($user);    
            $entityManager -> flush();
            $flashyNotifier->success('Account created successfully');

            return $this->redirectToRoute("app_blog");
        }
        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView()
        ]);
}

}

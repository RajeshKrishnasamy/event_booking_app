<?php

namespace App\Controller;

use App\Entity\AppUser;
use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppUserController extends AbstractController
{
    /**
     * @Route("/home", name="home", methods={"GET"})
     */
    public function home(Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(AppUser::class)->findAll();
        return $this->render('dashboard/index.html.twig', [
            'page_title' => 'Dashboard'
        ]);
    }

     /**
     * @Route("/user/new", name="register", methods={"GET"})
     */
    public function register_user(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(AppUser::class)->findAll();

        $user = new AppUser();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('password')->getData())
            );
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName($form->get('first_name')->getData());
            $user->setLastName($form->get('last_name')->getData());
            $user->setEmail($form->get('email')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }
      
        return $this->render('register/register.html.twig', [
            'page_title' => 'Registration Page',
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/user/success", name="register-success")
     */
    public function register_success(): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(AppUser::class)->findAll();
        dump($user);
        return $this->render('dashboard/index.html.twig', [
            'page_title' => 'Dashboard'
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastusername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', array(
            'last_username' => $lastusername,
            'error' => $error
        )
        );
        
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordUserFormType;
use App\Form\RegisterFormType;
use App\Form\EditUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
    {
        $user = new User();
        dump($user);
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $name = $form->get('username')->getData();
            $email = $form->get('email')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('azarusse@gmail.com') // <--   A REVOIR !
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                    // templates/emails/user.html.twig
                        'emails/registration.html.twig',
                        ['name' => $name]
                    ),
                    'text/html'
                )

                // you can remove the following code if you don't define a text version for your emails
                ->addPart(
                    $this->renderView(
                    // templates/emails/user.txt.twig
                        'emails/registration.txt.twig',
                        ['name' => $name]
                    ),
                    'text/plain'
                )
            ;

            $mailer->send($message);


                return $this->redirectToRoute('index');
        }

        return $this->render('user/register.html.twig', [
            'userForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit_user", name="app_edit_user")
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = $this->getUser();
        if($user != null) {
            $form = $this->createForm(EditUserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_edit_user');
            }

            $formPass = $this->createForm(PasswordUserFormType::class, $user);
            $formPass->handleRequest($request);
            dump($user);
            if ($formPass->isSubmitted() && $formPass->isValid())
            {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $formPass->get('password')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                dump($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_edit_user');
            }
            return $this->render('user/edit.html.twig', [
                'userForm' => $form->createView(),
                'passwordForm'=>$formPass->createView()
            ]);
        }
    }
}

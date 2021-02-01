<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        dump($request);

        if ($request->isMethod("POST")) {
            $email = (new Email())
                ->from($request->get('ct_email'))
                ->to('geoffreyplett@gmail.com')
                ->subject('Message de '.$request->get('ct_name'))
                ->text(
                    $request->get('ct_msg').'. Cordialement, '.$request->get('ct_name')
                );
            $mailer->send($email);
            $this->addFlash('success', 'Votre e-mail a bien été envoyé');

            return $this->redirectToRoute('index');
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}

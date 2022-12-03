<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailJet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="app_contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('success', "Merci de nous avoir contacté, notre équipe se charge de vous répondre dans les meilleurs délais.");

            $content = $form->get('content')->getData();
            $subject = $form->get('subject')->getData();
            $mail = new MailJet;
            $mail->send('ezcbdshop@gmail.com', 'Equipe EZ-CBD', $subject, $content);

            return $this->redirectToRoute('app_main');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

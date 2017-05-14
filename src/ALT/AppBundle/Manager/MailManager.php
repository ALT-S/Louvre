<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 24/04/2017
 * Time: 20:06
 */

namespace ALT\AppBundle\Manager;


use ALT\AppBundle\Entity\Commande;
use Symfony\Bundle\TwigBundle\TwigEngine;


class MailManager
{

    /** @var TwigEngine */
    private $view;

    /** @var string */
    private $from;

    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct(TwigEngine $view, $from, \Swift_Mailer $mailer)
    {
        $this->view = $view;
        $this->from = $from;
        $this->mailer = $mailer;
    }

    /**
     * Permet d'envoyer un e-mail de confirmation que la commande est bien passée.
     *
     * @param Commande $commande
     */
    public function envoyerConfirmationCommande(Commande $commande)
    {
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')//Message en HTML
            ->setSubject("Confirmation de paiement - Billets Musée du Louvre")
            ->setFrom($this->from)// Email de l'expéditeur est le destinataire du mail
            ->setTo($commande->getEmail())// destinataire du mail
            ->setBody($this->view->render("@ALTApp/mail/confirmation.html.twig", array('commande' => $commande)))
        ;

        $this->mailer->send($message);//Envoi mail
    }
}

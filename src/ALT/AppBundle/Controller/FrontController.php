<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 10/04/2017
 * Time: 14:12
 */

namespace ALT\AppBundle\Controller;


use ALT\AppBundle\Entity\Commande;
use ALT\AppBundle\Form\CommandeBilletType;
use ALT\AppBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/",name="accueil")
     * @Method({"GET","POST"})
     */
    public function accueilAction(Request $request)
    {
        $manager = $this->get('app.manager.commande');
        $commande = $manager->getCommandeOuCreerUneNouvelle();

        $form = $this->get('form.factory')->create(CommandeType::class, $commande); // Création du formulaire basé sur le type "CommandeType"
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->preparerBillets($commande);
            $manager->stockEnSession($commande);

            return $this->redirectToRoute("infos");
        }

        return $this->render('@ALTApp/Front/Billetterie.html.twig', array(
            'form' => $form->createView(), // Passage du formulaire à la vue
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/infos", name="infos")
     * @Method({"GET","POST"})
     */
    public function infosAction(Request $request)
    {
        $manager = $this->get('app.manager.commande');
        $commande = $manager->getCommande();

        $form = $this->get('form.factory')->create(CommandeBilletType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->calculerTarif($commande);
            $manager->stockEnSession($commande);

            return $this->redirectToRoute('panier');
        }

        return $this->render('ALTAppBundle:Front:Infos.html.twig', array(
            'form' => $form->createView(), // Passage du formulaire à la vue
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/panier", name="panier")
     * @Method({"GET"})
     */
    public function panierAction()
    {
        $commande = $this->get('app.manager.commande')->getCommande();

        if (false === $commande->isBilletsValides()) {
            return $this->redirectToRoute('infos');
        }

        return $this->render('ALTAppBundle:Front:Panier.html.twig', array(
            'commande' => $commande,
        ));
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/paiement", name="paiement")
     * @Method({"GET","POST"})
     */
    public function paiementAction(Request $request)
    {
        $manager = $this->get('app.manager.commande');
        $mailer = $this->get('app.manager.mail');
        $commande = $manager->getCommande();

        /**
         * Si la commande est gratuite, on passe la phase de paiement
         */
        if ($commande->estGratuit()) {

            $manager->faireGratuit($commande);
            $mailer->envoyerConfirmationCommande($commande);

            return $this->redirectToRoute("confirmation", array(
                'commandeId' => $commande->getId(),
            ));

        }

        if ($request->isMethod('POST')) {
            // Token is created using Stripe.js or Checkout!
            // Get the payment token submitted by the form:
            $token = $request->request->get('stripeToken');

            $resultat = $manager->fairePayer($commande, $token);
            if ($resultat) {
                $mailer->envoyerConfirmationCommande($commande);

                return $this->redirectToRoute("confirmation", array(
                    'commandeId' => $commande->getId(),
                ));
            }
        }

        return $this->render('ALTAppBundle:Front:Paiemment.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/confirmation/{commandeId}", name="confirmation")
     * @ParamConverter("commande", class="ALTAppBundle:Commande", options={"id" = "commandeId"})
     * @Method({"GET"})
     */
    public function confirmationAction(Commande $commande)
    {

        $this->get('app.manager.commande')->retireDeLaSession();

        return $this->render('ALTAppBundle:Front:Confirmation.html.twig', array(
            'commande' => $commande
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/tarifs", name="tarifs")
     * @Method({"GET"})
     */
    public function tarifsAction()
    {
        return $this->render('ALTAppBundle:Front:Tarifs.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/cgv", name="cgv")
     * @Method({"GET"})
     */
    public function cgvAction()
    {
        return $this->render('ALTAppBundle:Front:CGV.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/aide", name="aide")
     * @Method({"GET"})
     */
    public function aideAction()
    {
        return $this->render('ALTAppBundle:Front:Aide.html.twig');
    }
}
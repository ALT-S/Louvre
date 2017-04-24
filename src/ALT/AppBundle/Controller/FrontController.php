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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/",name="accueil")
     */
    public function accueilAction(Request $request)
    {
        $commande = $request->getSession()->get('commande');
        if ($commande === null) {
            $commande = new Commande();
        }

        $form = $this->get('form.factory')->create(CommandeType::class, $commande); // Création du formulaire basé sur le type "CommandeType"

        // Si le formulaire a été soumis
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.manager.commande')->preparerBillets($commande);
            $request->getSession()->set('commande', $commande);

            return $this->redirectToRoute("infos");
        }

        return $this->render('ALTAppBundle::Billetterie.html.twig', array(
            'form' => $form->createView(), // Passage du formulaire à la vue
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/infos", name="infos")
     */
    public function infosAction(Request $request)
    {

        $commande = $request->getSession()->get('commande');
        $form = $this->get('form.factory')->create(CommandeBilletType::class, $commande);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $this->get('app.manager.commande')->calculerTarif($form->getData());
            $request->getSession()->set('commande', $commande);

            return $this->redirectToRoute('panier');
        }

        return $this->render('ALTAppBundle::Infos.html.twig', array(
            'form' => $form->createView(), // Passage du formulaire à la vue
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/panier", name="panier")
     */
    public function panierAction(Request $request)
    {
        $commande = $request->getSession()->get('commande');
        if ($commande == null) { // Si l'objet n'existe pas, on retourne sur la page d'accueil !!
            // Ajout d'un message flash ?
            return $this->redirectToRoute('accueil');
        }
        
        return $this->render('ALTAppBundle::Panier.html.twig', array(
            'commande' => $commande,
        ));
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/paiement", name="paiement")
     */
    public function paiementAction(Request $request)
    {

        $commande = $request->getSession()->get('commande');
        if ($commande == null) { // Si l'objet n'existe pas, on retourne sur la page d'accueil !!
            // Ajout d'un message flash ?
            return $this->redirectToRoute('accueil');
        }

        /**
         * Si la commande est gratuite, on passe la phase de paiement
         */
        if ($commande->getTarif() < 1){

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $this->get('app.manager.mail')->envoyerConfirmationCommande($commande);

            return $this->redirectToRoute("confirmation", array(
                'commandeId' => $commande->getId(),
            ));

        }

        if ($request->isMethod('POST')) {
            // Token is created using Stripe.js or Checkout!
            // Get the payment token submitted by the form:
            $token = $request->request->get('stripeToken');

            $resultat = $this->get('app.manager.commande')->fairePayer($commande, $token);

            if ($resultat) {
                $this->get('app.manager.mail')->envoyerConfirmationCommande($commande);

                return $this->redirectToRoute("confirmation", array(
                    'commandeId' => $commande->getId(),
                ));
            }
        }

        return $this->render('ALTAppBundle::Paiemment.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/confirmation/{commandeId}", name="confirmation")
     */
    public function confirmationAction($commandeId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('ALTAppBundle:Commande')->find($commandeId);

        if ($commande === null) {
            // erreur !
        }

        $request->getSession()->set('commande', null);

        return $this->render('ALTAppBundle::Confirmation.html.twig', array(
            'commande' => $commande
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/tarifs", name="tarifs")
     */
    public function tarifsAction()
    {
        return $this->render('ALTAppBundle::Tarifs.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/cgv", name="cgv")
     */
    public function cgvAction()
    {
        return $this->render('ALTAppBundle::CGV.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/aide", name="aide")
     */
    public function aideAction()
    {
        return $this->render('ALTAppBundle::Aide.html.twig');
    }
}
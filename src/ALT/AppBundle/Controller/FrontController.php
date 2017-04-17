<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 10/04/2017
 * Time: 14:12
 */

namespace ALT\AppBundle\Controller;


use ALT\AppBundle\Entity\Billet;
use ALT\AppBundle\Entity\Commande;
use ALT\AppBundle\Form\BilletType;
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
    function accueilAction(Request $request)
    {
        $commande = new Commande(); // Création d'un objet "Commande" vide
        $form = $this->get('form.factory')->create(CommandeType::class, $commande); // Création du formulaire basé sur le type "CommandeType"

        // Si le formulaire a été soumis, alors on insère son contenu en DB et on redirige vers la page suivante
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $request->getSession()->set('idCommande', $commande->getId());

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
    function infosAction(Request $request)
    {

        $idCommande = $request->getSession()->get('idCommande');

        $em = $this->getDoctrine()->getManager();

        $commande = $em->getRepository('ALTAppBundle:Commande')->find($idCommande); // Récupération de l'objet commande via doctrine grâce à son ID
        if ($commande == null) { // Si l'objet n'existe pas, on retourne sur la page d'accueil !!
            // Ajout d'un message flash ?
            return $this->redirectToRoute('accueil');
        }

        // Sinon, on affiche le formulaire pour que chaque client enregistre son nom sur le billet

        $billet = new Billet();
        $billet->setCommande($commande);

        $form = $this->get('form.factory')->create(BilletType::class, $billet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()){
            $em = $this-> getDoctrine()->getManager();
            $em->persist($billet);
            $em->flush();
            
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
    function panierAction()
    {
        return $this->render('ALTAppBundle::Panier.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/coordonnees", name="coordonnees")
     */
    function coordonneesAction()
    {
        return $this->render('ALTAppBundle::Coordonnees.html.twig');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/paiement", name="paiement")
     */
    function paiementAction()
    {
        return $this->render('ALTAppBundle::Paiemment.html.twig');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/confirmation", name="confirmation")
     */
    function confirmationAction()
    {
        return $this->render('ALTAppBundle::Confirmation.html.twig');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/tarifs", name="tarifs")
     */
    function tarifsAction()
    {
        return $this->render('ALTAppBundle::Tarifs.html.twig');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/cgv", name="cgv")
     */
    function cgvAction()
    {
        return $this->render('ALTAppBundle::CGV.html.twig');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/aide", name="aide")
     */
    function aideAction()
    {
        return $this->render('ALTAppBundle::Aide.html.twig');
    }
}
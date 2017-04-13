<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 10/04/2017
 * Time: 14:12
 */

namespace ALT\AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/",name="accueil")
     */
    function accueilAction()
    {
        return $this->render('ALTAppBundle::Billetterie.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/infos", name="infos")
     */
    function infosAction()
    {
        return $this->render('ALTAppBundle::Infos.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/pannier", name="pannier")
     */
    function pannierAction()
    {
        return $this->render('ALTAppBundle::Pannier.html.twig');
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
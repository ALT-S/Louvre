<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 10/04/2017
 * Time: 14:12
 */

namespace ALT\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    function accueilAction()
    {
        return $this->render('ALTAppBundle::Billetterie.html.twig');
    }

    function infosAction()
    {
        return $this->render('ALTAppBundle::Infos.html.twig');
    }

    function pannierAction()
    {
        return $this->render('ALTAppBundle::Pannier.html.twig');
    }

    function coordonneesAction()
    {
        return $this->render('ALTAppBundle::Coordonnees.html.twig');
    }

    function paiementAction()
    {
        return $this->render('ALTAppBundle::Paiemment.html.twig');
    }

    function confirmationAction()
    {
        return $this->render('ALTAppBundle::Confirmation.html.twig');
    }

    function tarifsAction()
    {
        return $this->render('ALTAppBundle::Tarifs.html.twig');
    }

    function cgvAction()
    {
        return $this->render('ALTAppBundle::CGV.html.twig');
    }

    function aideAction()
    {
        return $this->render('ALTAppBundle::Aide.html.twig');
    }
}
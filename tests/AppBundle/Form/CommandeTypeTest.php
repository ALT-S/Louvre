<?php

namespace Tests\AppBundle\Form;

use ALT\AppBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CommandeTypeTest extends WebTestCase
{
    public function testPremierFormulaireQuiFonctionne()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals('ALT\AppBundle\Controller\FrontController::accueilAction', $client->getRequest()->attributes->get('_controller'));

        $form = $crawler->selectButton('submit')->form(array(
            'alt_appbundle_commande[email]'=>'alt@alt.fr',
            'alt_appbundle_commande[dateVisite]'=>'19-10-2018',
            'alt_appbundle_commande[type]' => Commande::DEMI_JOURNEE,
            'alt_appbundle_commande[nbBillets]'=>'3',
        ));
        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals('ALT\AppBundle\Controller\FrontController::infosAction', $client->getRequest()->attributes->get('_controller'));

    }

    public function testPremierFormulaireAvecUnChampQuiNeMarchePas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals('ALT\AppBundle\Controller\FrontController::accueilAction', $client->getRequest()->attributes->get('_controller'));

        $form = $crawler->selectButton('submit')->form(array(
            'alt_appbundle_commande[email]'=>'alt@test',
            'alt_appbundle_commande[dateVisite]'=>'19-10-2018',
            'alt_appbundle_commande[type]' => Commande::DEMI_JOURNEE,
            'alt_appbundle_commande[nbBillets]'=>'3',
        ));

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('span.glyphicon-exclamation-sign')->count() == 1);
    }

    public function testPremierFormulaireAvecDeuxChampsQuiNeMarchentPas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals('ALT\AppBundle\Controller\FrontController::accueilAction', $client->getRequest()->attributes->get('_controller'));

        $form = $crawler->selectButton('submit')->form(array(
            'alt_appbundle_commande[email]'=>'alt@alt.fr',
            'alt_appbundle_commande[dateVisite]'=>'25-12-2017',
            'alt_appbundle_commande[type]' => Commande::DEMI_JOURNEE,
            'alt_appbundle_commande[nbBillets]'=>'-2',
        ));

        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('span.glyphicon-exclamation-sign')->count() == 2);
    }

}
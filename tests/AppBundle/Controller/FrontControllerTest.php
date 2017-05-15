<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels
 */
class FrontControllerTest extends WebTestCase
{
    public function testPageAccueilOK()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Billetterie', $crawler->filter('.fil span.couleur')->text());
        $this->assertContains('Information billet', $crawler->filter('.fil span.gris')->text());
    }

    public function testPageInfoBilletAccesDirectRetourneVersAccueil()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/infos');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Billetterie', $crawler->filter('.fil span.couleur')->text());
        $this->assertContains('Information billet', $crawler->filter('.fil span.gris')->text());
    }

    public function testPageAccueilClickCGV()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('CGV')->link();
        $crawler = $client->click($link);

        $this->assertContains('Conditions Générales de Vente', $crawler->filter('h3#index')->text());
    }

    public function testPageConfirmationSansId()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/confirmation');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}

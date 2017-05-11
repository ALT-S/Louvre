<?php

namespace Tests\AppBundle\Manager;


use ALT\AppBundle\Entity\Billet;
use ALT\AppBundle\Entity\Commande;
use ALT\AppBundle\Exception\CommandeNotFoundException;
use ALT\AppBundle\Manager\CommandeManager;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Tests unitaires
 */
class CommandeManagerTest extends TestCase
{

    public function testStockageObjetCommandeEnSession()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage()); // Triche sur le stockage
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);

        $commande = new Commande();
        $manager->stockEnSession($commande);

        $this->assertSame($commande, $session->get('commande'));
    }

    public function testObjetCommandeRetirerDeLaSessionApresStockage()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);

        $commande = new Commande();
        $manager->stockEnSession($commande);

        $manager->retireDeLaSession();

        $this->assertNull($session->get('commande'));
    }

    public function testRecuperationObjetCommandeDepuisLaSessionOK()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);

        $commande = new Commande();
        $session->set('commande', $commande);

        $this->assertSame($commande, $manager->getCommande());
    }

    public function testRecuperationObjetCommandeDepuisLaSessionJetteUneException()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);

        $this->expectException(CommandeNotFoundException::class);
        $manager->getCommande();
    }

    public function testCreationObjetCommandeDepuisLaSessionSiElleNexistePasDeja()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);

        $commande = new Commande();

        //$session->set('commande', null);
        $this->assertNotSame($commande, $manager->getCommandeOuCreerUneNouvelle());

        $session->set('commande', $commande);
        $this->assertSame($commande, $manager->getCommandeOuCreerUneNouvelle());


    }


    public function testRecalculDuneCommande()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $session = new Session(new MockArraySessionStorage());
        $stripeApiKey = "stripeApiKey";

        $manager = new CommandeManager($em, $session, $stripeApiKey);


        $commande = new Commande();

        $b1 = new Billet();

        $dt = new \DateTime(); // donne la date d'aujourd'hui
        $dt->modify('-20 years'); // auquelle on retranche 20 ans

        $b1->setTarif(CommandeManager::TARIF_REDUIT)->setDateNaissance($dt);
        $commande->addBillet($b1);

        $commande = $manager->calculerTarif($commande);

        $this->assertNotEquals(CommandeManager::TARIF_REDUIT, $commande->getTarif());
        $this->assertEquals(CommandeManager::TARIF_NORMAL, $commande->getTarif());

        $dt->modify('-45 years'); // Je suis à 65 ans
        $commande = $manager->calculerTarif($commande);

        $this->assertNotEquals(CommandeManager::TARIF_NORMAL, $commande->getTarif());
        $this->assertEquals(CommandeManager::TARIF_SENIOR, $commande->getTarif());

        $dt->modify('+ 64 years'); // Je suis à 1 an
        $commande = $manager->calculerTarif($commande);
        $this->assertNotEquals(CommandeManager::TARIF_SENIOR, $commande->getTarif());
        $this->assertEquals(CommandeManager::TARIF_GRATUIT, $commande->getTarif());

        $dt->modify('+ 5 years'); // Je suis à 6 ans
        $commande = $manager->calculerTarif($commande);
        $this->assertNotEquals(CommandeManager::TARIF_GRATUIT, $commande->getTarif());
        $this->assertEquals(CommandeManager::TARIF_ENFANT, $commande->getTarif());
    }
}
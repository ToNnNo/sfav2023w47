<?php

namespace App\Tests\WebTestCase;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class EventTest extends WebTestCase
{
    private KernelBrowser $client;
    private Crawler $crawler;

    /**
     * @before
     */
    public function initCrawler(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/event');
    }

    public function testPageExists(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Les évènements');
    }

    public function testEmailSend(): void
    {
        $this->assertQueuedEmailCount(1);
        $this->assertEmailCount(1);
    }

    public function testExercice(): void
    {
        // tester si la balise H2 avec Exercice existe
        $this->assertSelectorExists("h2");
        $this->assertSelectorCount(2, "h2");

        $h2 = $this->crawler->filter('h2')->eq(1);
        $this->assertEquals("Exercice", $h2->text());

        // cliquer sur le lien "Accéder à notre page" -> on vérifie qu'on obtient bien la page /new-way

        $this->client->clickLink("Accéder à notre page");
        $this->assertResponseStatusCodeSame(301);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'New Way');
    }
}

<?php

namespace App\Tests\WebTestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class WebpackTest extends WebTestCase
{
    private Crawler $crawler;

    /**
     * @before
     */
    public function initCrawler(): void
    {
        $client = static::createClient();
        $this->crawler = $client->request('GET', '/webpack');
    }

    public function testPageExists(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Webpack Encore');
    }

    public function testPageHasImage(): void
    {
        $imgCrawler = $this->crawler->filter('img')->first();

        $this->assertEquals('/build/images/grogu.jpg', $imgCrawler->attr('src'));
        $this->assertNotEmpty($imgCrawler->attr('alt'));
    }
}

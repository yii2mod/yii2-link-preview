<?php

namespace yii2mod\linkpreview\tests;

use yii2mod\linkpreview\Crawler;

/**
 * Class CrawlerTest
 * @package yii2mod\linkpreview\tests
 */
class CrawlerTest extends TestCase
{
    public function testGetPageInfoFromContent()
    {
        $crawler = new Crawler(['content' => 'some content https://github.com/yii2mod']);
        $this->assertNotEmpty($crawler->getPageInfo()->getTitle());
    }

    public function testNoPageInfo()
    {
        $crawler = new Crawler(['content' => 'some content']);
        $this->assertEmpty($crawler->getPageInfo());
    }
}
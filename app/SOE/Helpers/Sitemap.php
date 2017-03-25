<?php

class Sitemap
{
    protected $type;
    protected $xml;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function open()
    {
        if($this->type == 'index')
            $this->openIndex();
        else if($this->type == 'sitemap')
            $this->openSitemap();
    }

    public function createItem($url, $change = null, $priority = null)
    {
        if($this->type == 'index')
            $this->createIndex($url);
        else if($this->type == 'sitemap')
            $this->createUrl($url, $change, $priority);
    }

    public function getXML()
    {
        return $this->xml;
    }

    protected function openIndex()
    {
        $xml = new \SimpleXMLElement('<sitemapindex></sitemapindex>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->xml = $xml;
        return $xml;
    }

    protected function openSitemap()
    {
        $xml = new \SimpleXMLElement('<urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        $this->xml = $xml;
        return $xml;
    }

    protected function createIndex($url)
    {
        $sitemap = $this->xml->addChild('sitemap');
            $sitemap->addChild('loc', $url);
    }

    protected function createUrl($url, $change = null, $priority = null)
    {
        $item = $this->xml->addChild('url');
            $item->addChild('loc', $url);
            if($change)
                $item->addChild('changefreq', $change);
            if($priority)
                $item->addChild('priority', $priority);
    }
}
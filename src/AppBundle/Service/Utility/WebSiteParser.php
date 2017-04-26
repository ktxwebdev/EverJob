<?php

namespace AppBundle\Service\Utility;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of WebSiteParser
 *
 * @author Cyril
 */
class WebSiteParser {

    public function getWebSiteInformation() {

        $client = new Client();
        $crawler = $client->request('GET', 'http://emploi.alsacreations.com/');

        $jobList = array();

        $offerListCrawler = $crawler->filter('.annonces .offre');

        foreach ($offerListCrawler as $domElement) {

            $node = new Crawler($domElement);

            $job = array();

            $job['link'] = $node->filter('a')->attr('href');

            $linkInArray = explode('-', $job['link']);

            $job['number'] = $linkInArray[1];

            $job['link'] = $node->filter('a')->attr('href');
            $job['title'] = $node->filter('.title-link')->text();
            $job['company'] = $node->filter('.societe')->text();

            $jobList[] = $job;
        }

        return $jobList;
    }
}

<?php

namespace AppBundle\Service\Utility;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Web Site Parser service
 */
class WebSiteParser {

    /**
     * Get job list infmration from the Alsacreations website
     * 
     * @return array
     */
    public function getWebSiteInformation() {

        $client = new Client();
        $crawler = $client->request('GET', 'http://emploi.alsacreations.com/');

        $jobList = array();

        // Get the offer list
        $offerListCrawler = $crawler->filter('.annonces .offre');

        // For each offer found, get the information and add it to the job list
        foreach ($offerListCrawler as $domElement) {

            $node = new Crawler($domElement);

            $job = array();

            $job['link'] = $node->filter('a')->attr('href');

            $linkInArray = explode('-', $job['link']);

            // Get the number from the url
            $job['number'] = $linkInArray[1];

            $job['title'] = $node->filter('.title-link')->text();
            $job['company'] = $node->filter('.societe')->text();

            $jobList[] = $job;
        }

        return $jobList;
    }

}

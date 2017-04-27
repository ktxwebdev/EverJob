<?php

namespace AppBundle\Tests\Service\Utility;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * WebSiteParserTest
 */
class WebSiteParserTest extends WebTestCase {

    public function setup() {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->container = static::$kernel->getContainer();
    }

    /**
     * Test the get website information result
     * 
     * Check if there is still an array or an error
     */
    public function testAddMessageTest() {

        $serviceWebSiteParser = $this->container->get('app.utility.web_site_parser');

        /**
         * Get the job list information from the service
         */
        $jobInfoList = $serviceWebSiteParser->getWebSiteInformation();

        /**
         * Check if the result is an array or not
         */
        $this->assertTrue(is_array($jobInfoList));

        /**
         * Check if all the array information are here
         */
        foreach ($jobInfoList as $jobInfo) {
            $this->assertArrayHasKey('link', $jobInfo);
            $this->assertArrayHasKey('number', $jobInfo);
            $this->assertArrayHasKey('title', $jobInfo);
            $this->assertArrayHasKey('company', $jobInfo);
        }
    }

}

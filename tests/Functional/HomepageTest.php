<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'Czy Ola jest dzisiaj guru?'
     */
    public function testGetHomepage()
    {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Czy Ola jest dzisiaj guru?', (string)$response->getBody());
    }

    /**
     * Test that the index route won't accept a post request
     */
    public function testPostHomepageNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);
        $this->assertEquals(405, $response->getStatusCode());
    }
}
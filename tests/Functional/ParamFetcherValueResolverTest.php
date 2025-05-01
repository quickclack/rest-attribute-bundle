<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Quickclack\RestAttributeBundle\Tests\Fixtures\TestKernel;

class ParamFetcherValueResolverTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testControllerWithParamFetcher(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/test?name=John&age=30');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('John', $responseData['name']);
        $this->assertEquals(30, $responseData['age']);
    }

    public function testMissingRequiredParamReturnsError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/test?name=John');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Invalid parameter value', $client->getResponse()->getContent());
    }

    public function testInvalidPatternParamError(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/test?name=John&age=test');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Invalid format for param', $client->getResponse()->getContent());
    }
}
<?php

namespace Test\Command\Mailboxes;

use PHPUnit\Framework\TestCase;
use TomCan\CombellApi\Adapter\AdapterInterface;
use TomCan\CombellApi\Common\HmacGenerator;
use TomCan\CombellApi\Common\Api;
use TomCan\CombellApi\Command\Mailboxes\GetMailboxes;
use TomCan\CombellApi\Structure\Mailbox\Mailbox;

final class GetMailboxesTest extends TestCase
{
    public function testGetMailboxes(): void
    {
        $returnValue = [
            'status' => 200,
            'headers' => [
                'Content-Type' => ['application/json; charset=utf-8'],
                'x-ratelimit-limit' => ['100'],
                'x-ratelimit-usage' => ['1'],
                'x-ratelimit-remaining' => ['99'],
                'x-ratelimit-reset' => ['60'],
                'Date' => ['Sat, 02 Feb 2019 20:23:35 GMT'],
            ],
            'body' => json_encode([
                (object) [
                    'name' => 'info@example.com',
                    'max_size' => 1000,
                    'actual_size' => 50,
                ],
                (object) [
                    'name' => 'abuse@example.com',
                    'max_size' => 1000,
                    'actual_size' => 2,
                ],
            ]),
        ];

        $adapterStub = $this->createMock(AdapterInterface::class);
        $headers = [
            'Authorization' => 'hmac mocked',
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
        ];
        $adapterStub->method('call')
            ->with('GET', 'https://api.combell.com/v2/mailboxes/?domain_name=example.com', $headers, '')
            ->willReturn($returnValue);

        $hmacGeneratorStub = $this->createMock(HmacGenerator::class);
        $hmacGeneratorStub->method('getHeader')
            ->willReturn('hmac mocked');
        $api = new Api($adapterStub, $hmacGeneratorStub);

        $cmd = new GetMailboxes('example.com');
        /** @var Mailbox[] $mailboxes */
        $mailboxes = $api->executeCommand($cmd);

        $this->assertCount(2, $mailboxes);

        $this->assertInstanceOf(Mailbox::class, $mailboxes[0]);
        $this->assertEquals('info@example.com', $mailboxes[0]->getName());
        $this->assertEquals(1000, $mailboxes[0]->getMaxSize());
        $this->assertEquals(50, $mailboxes[0]->getActualSize());
    }
}

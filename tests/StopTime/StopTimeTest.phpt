<?php

declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mistrfilda\Pid\Api\Client\GuzzlePsr18Client;
use Mistrfilda\Pid\Api\Client\IClientFactory;
use Mistrfilda\Pid\Api\GolemioService;
use Mistrfilda\Pid\Test\Data\TestDataGetter;
use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../Bootstrap.php';

$mockedHandler = new MockHandler([
	new Response(200, [], FileSystem::read(TestDataGetter::AVAILABLE_DATA['20190216']['stopTime'])),
]);

$mockedGuzzleClient = new GuzzlePsr18Client(['handler' => HandlerStack::create($mockedHandler)]);

$clientFactoryMock = Mockery::mock(IClientFactory::class);
$clientFactoryMock->shouldReceive('createClient')->andReturn($mockedGuzzleClient);

$pidService = new GolemioService('aaaa', 'http://ofce.cz', $clientFactoryMock);

$stopResponse = $pidService->sendGetStopTimesRequest('1234');

Assert::equal($stopResponse->getCount(), 20);
$stopTimes = $stopResponse->getStopTimes();
Assert::count($stopResponse->getCount(), $stopTimes);

Assert::equal($stopTimes[0]->getArivalTime(), '10:01:00');
Assert::equal($stopTimes[5]->getDepartureTime(), '10:17:00');
Assert::equal($stopTimes[11]->getTripId(), '150_7_200106');
Assert::equal($stopTimes[17]->getStopSequence(), 3);

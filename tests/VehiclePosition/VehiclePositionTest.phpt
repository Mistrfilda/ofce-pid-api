<?php

declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mistrfilda\Pid\Api\Client\GuzzlePsr18Client;
use Mistrfilda\Pid\Api\Client\IClientFactory;
use Mistrfilda\Pid\Api\PidService;
use Mistrfilda\Pid\Test\TestDataGetter;
use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../Bootstrap.php';

$mockedHandler = new MockHandler([
    new Response(200, [], FileSystem::read(TestDataGetter::AVAILABLE_DATA['20190216']['vehiclePosition'])),
]);

$mockedGuzzleClient = new GuzzlePsr18Client(['handler' => HandlerStack::create($mockedHandler)]);

$clientFactoryMock = Mockery::mock(IClientFactory::class);
$clientFactoryMock->shouldReceive('createClient')->andReturn($mockedGuzzleClient);

$pidService = new PidService('aaaa', 'http://ofce.cz', $clientFactoryMock);

$vehiclePositionResponse = $pidService->sendGetVehiclePositionRequest();

Assert::equal($vehiclePositionResponse->getCount(), 1);
$vehiclePositions = $vehiclePositionResponse->getVehiclePositions();
Assert::count($vehiclePositionResponse->getCount(), $vehiclePositions);

Assert::equal(49.7164, $vehiclePositions[0]->getLatitude());
Assert::equal(14.32664, $vehiclePositions[0]->getLongitude());
Assert::equal(true, $vehiclePositions[0]->getWheelchairAccessible());
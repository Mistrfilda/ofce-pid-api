<?php

declare(strict_types=1);

namespace Mistrfilda\Pid\Test\Data;

class TestDataGetter
{
	public const AVAILABLE_DATA = [
		'20190216' => [
			'stop' => __DIR__ . '/20190216/Stops.json',
			'stopTime' => __DIR__ . '/20190216/StopTimes.json',
			'trip' => __DIR__ . '/20190216/Trips.json',
			'vehiclePosition' => __DIR__ . '/20190216/VehiclePosition.json',
		],
		'20200921' => [
			'parkingLot' => __DIR__ . '/20200921/parkingLots.json',
		],
	];
}

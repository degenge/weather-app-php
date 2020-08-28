<?php

use Carbon\Carbon;

CONST OPENWEATHER_KEY = 'bb0e1f790c8db79e3532961bf204d7aa',
OPENWEATHER_API_URL   = 'https://api.openweathermap.org/data/2.5/',
IMAGE_PATH            = 'assets/images/',
DAY_HOUR              = '15:00:00',
NIGHT_HOUR            = '03:00:00';

function getWeather($cityName)
{

    $url       = OPENWEATHER_API_URL . 'weather?q=' . $cityName . '&APPID=' . OPENWEATHER_KEY;
    $request   = file_get_contents($url);
    $jsonArray = json_decode($request, true);

    //print_arr($jsonArray);

    $mappedWeather = [
        'name'                 => $jsonArray['name'],
        'dt'                   => Carbon::createFromTimestamp($jsonArray['dt'])->format('d-m-Y H:i'),
        'image'                => IMAGE_PATH . $jsonArray['weather'][0]['icon'] . ".svg",
        'temperatureCurrent'   => calculateTemperature($jsonArray['main']['temp'], TEMPERATURE_SCALES::CELCIUS),
        'temperatureFeelsLike' => calculateTemperature($jsonArray['main']['feels_like'], TEMPERATURE_SCALES::CELCIUS),
        'description'          => $jsonArray['weather'][0]['description'],
        'pressure'             => $jsonArray['main']['pressure'],
        'humidity'             => $jsonArray['main']['humidity'],
        'wind'                 => $jsonArray['wind']['speed'],
        'clouds'               => $jsonArray['clouds']['all'],
        'sunrise'              => Carbon::createFromTimestamp($jsonArray['sys']['sunrise'])->toTimeString(),
        'sunset'               => Carbon::createFromTimestamp($jsonArray['sys']['sunset'])->toTimeString(),
    ];

    return $mappedWeather;

}

function getForecast($cityName, $timeMode)
{
    $url       = OPENWEATHER_API_URL . 'forecast?q=' . $cityName . '&APPID=' . OPENWEATHER_KEY;
    $request   = file_get_contents($url);
    $jsonArray = json_decode($request, true);

    // ADD ONE DAY TO CURRENT DATE
    $dateDay   = Carbon::now()->addDays(1)->setHour(12)->setMinutes(00)->setSeconds(00);
    $dateNight = Carbon::now()->addDays(1)->setHour(00)->setMinutes(00)->setSeconds(00);

    $tempForecastDay   = [];
    $tempForecastNight = [];
    foreach ($jsonArray['list'] as $item) {

        if ($item['dt_txt'] == $dateDay) {
            $tempForecastDay[] = $item;
            $dateDay->addDays(1);
        }

        if ($item['dt_txt'] == $dateNight) {
            $tempForecastNight[] = $item;
            $dateNight->addDays(1);
        }

    }
    //print_arr($tempForecastDay);
    //print_arr($tempForecastNight);

    $mappedForecastDay   = [];
    $mappedForecastNight = [];
    foreach ($tempForecastDay as $item) {
        $mappedForecastDay[] = [
            'dt'                   => Carbon::createFromTimestamp($item['dt']),
            'dateDayName'          => Carbon::createFromTimestamp($item['dt'])->isoFormat('ddd'),
            'dateDayMonth'         => Carbon::createFromTimestamp($item['dt'])->format('d/m'),
            'image'                => IMAGE_PATH . $item['weather'][0]['icon'] . ".svg",
            'temperatureCurrent'   => calculateTemperature($item['main']['temp'], TEMPERATURE_SCALES::CELCIUS),
            'temperatureFeelsLike' => calculateTemperature($item['main']['feels_like'], TEMPERATURE_SCALES::CELCIUS),
            'description'          => $item['weather'][0]['description'],
            'pressure'             => $item['main']['pressure'],
            'humidity'             => $item['main']['humidity'],
            'wind'                 => $item['wind']['speed'],
            'clouds'               => $item['clouds']['all'],
            //'sunrise'              => Carbon::createFromTimestamp($item['sys']['sunrise'])->toTimeString(),
            //'sunset'               => Carbon::createFromTimestamp($item['sys']['sunset'])->toTimeString(),
        ];
    }
    foreach ($tempForecastNight as $item) {
        $mappedForecastNight[] = [
            'dt'                   => Carbon::createFromTimestamp($item['dt']),
            'dateDayName'          => Carbon::createFromTimestamp($item['dt'])->isoFormat('ddd'),
            'dateDayMonth'         => Carbon::createFromTimestamp($item['dt'])->format('d/m'),
            'image'                => IMAGE_PATH . $item['weather'][0]['icon'] . ".svg",
            'temperatureCurrent'   => calculateTemperature($item['main']['temp'], TEMPERATURE_SCALES::CELCIUS),
            'temperatureFeelsLike' => calculateTemperature($item['main']['feels_like'], TEMPERATURE_SCALES::CELCIUS),
            'description'          => $item['weather'][0]['description'],
            'pressure'             => $item['main']['pressure'],
            'humidity'             => $item['main']['humidity'],
            'wind'                 => $item['wind']['speed'],
            'clouds'               => $item['clouds']['all'],
            //'sunrise'              => Carbon::createFromTimestamp($item['sys']['sunrise'])->toTimeString(),
            //'sunset'               => Carbon::createFromTimestamp($item['sys']['sunset'])->toTimeString(),
        ];
    }

    switch ($timeMode) {
        case TIME_MODES::DAY:
            return $mappedForecastDay;
        case TIME_MODES::NIGHT:
            return $mappedForecastNight;
        default:
            return '';
    }

}

function calculateTemperature($value, $scale)
{
    $temperature = 0;
    switch ($scale) {
        case TEMPERATURE_SCALES::CELCIUS:
            $temperature = round((float)$value - 273.15);
            break;
        case TEMPERATURE_SCALES::FAHRENHEIT:
            $temperature = round((((float)$value - 273.15) * 1.8) + 32);
            break;
        default:
            break;
    }
    return $temperature;
}
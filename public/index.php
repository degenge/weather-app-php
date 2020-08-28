<?php
require_once '../src/enums.php';
require_once '../src/helpers.php';
require_once '../src/weather.php';
require_once '../vendor/autoload.php';

const PREFERRED_CITY = 'Dendermonde';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cityName = test_input($_POST["city"]);
} else {
    $cityName = PREFERRED_CITY;
}

$weather  = getWeather($cityName);
$forecast = getForecast($cityName, TIME_MODES::DAY);

?>
<!DOCTYPE html>
<html lang="en" >

<head >
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--    <script src="dist/bundle.js" type="module" defer ></script >-->

    <!-- Bootstrap CSS -->
    <link
            rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
            integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
            crossorigin="anonymous"
    />

    <!--Chart.js-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css"
          integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA=="
          crossorigin="anonymous" />

    <!-- style -->
    <link rel="stylesheet" href="css/style.css" />

    <link
            href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
            rel="stylesheet"
    />

    <!--<link rel="stylesheet" href="vendor/font-awesome/css/all.css" />-->


    <title >Weather app</title >

</head >

<body >

<header >
    <h1 >World Wide Weather</h1 >
</header >

<main class="container" >

    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
        <div id="formInput" class="row" >
            <div class="col-md-10 col-sm-8" >
                <div class="input-group mb-3" >
                    <div class="input-group-prepend" >
                        <span class="input-group-text" id="basic-addon1" >City </span >
                    </div >
                    <input id="city" type="text" name="city" class="form-control" aria-describedby="basic-addon1" />
                </div >
            </div >
            <div class="col-md-2 col-sm-4 text-right" >
                <button id="search" type="submit" name="submit" class="btn btn-primary" >Search</button >
            </div >
        </div >
    </form >

    <div id="spinner" >Loading...</div >

    <div id="weather-container" >

        <div class="row location-date" >
            <div class="col-12" >
                <h2 id="location" class="location-date__location" ><?php echo $weather['name']; ?></h2 >
                <div id="currentDate" class="location-date__date" ><?php echo $weather['dt']; ?></div >
            </div >
        </div >

        <!--start current temperature-->
        <div class="row" >

            <div class="col-md-6 col-sm-12 current-temperature" >

                <div class="row" >

                    <div class="col-sm-6 current-temperature__icon-container" >
                        <img id="currentTemperatureIcon" src="<?php echo $weather['image']; ?>" class="current-temperature__icon" alt="" >
                    </div >

                    <div class="col-sm-6 current-temperature__content-container" >
                        <div id="currentTemperatureValue" class="current-temperature__value" ><?php echo $weather['temperatureCurrent']; ?></div >
                        <div id="currentTemperatureFeelsLikeValue" class="current-temperature-feelslike__value" >Feels like <?php echo $weather['temperatureFeelsLike']; ?></div >
                        <div id="currentTemperatureSummary" class="current-temperature__summary" ><?php echo $weather['description']; ?></div >
                    </div >

                </div >
                <!--end current temperature-->
            </div >

            <!--start current stats-->
            <div class="col-md-6 col-sm-12 current-stats" >

                <div class="row" >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsTemperatureHighValue" class="current-stats__value" ></div >
                        <div class="text-muted current-stats__label" >High</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsWindSpeedValue" class="current-stats__value" ><?php echo $weather['wind']; ?>m/s</div >
                        <div class="text-muted current-stats__label" >Wind</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsPressureValue" class="current-stats__value" ><?php echo $weather['pressure']; ?>hPa</div >
                        <div class="text-muted current-stats__label" >Pressure</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsSunriseValue" class="current-stats__value" ><?php echo $weather['sunrise']; ?></div >
                        <div class="text-muted current-stats__label" >Sunrise</div >
                    </div >
                </div >

                <div class="row" >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsTemperatureLowValue" class="current-stats__value" ></div >
                        <div class="text-muted current-stats__label" >Low</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsCloudsValue" class="current-stats__value" ><?php echo $weather['clouds']; ?>%</div >
                        <div class="text-muted current-stats__label" >Clouds</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsHumidityValue" class="current-stats__value" ><?php echo $weather['humidity']; ?>%</div >
                        <div class="text-muted current-stats__label" >Humidity</div >
                    </div >
                    <div class="col-md-3 col-sm-6" >
                        <div id="currentStatsSunsetValue" class="current-stats__value" ><?php echo $weather['sunset']; ?></div >
                        <div class="text-muted current-stats__label" >Sunset</div >
                    </div >
                </div >

            </div >

        </div >
        <!--end current stats-->

        <!--todo: implement weather by hours-->
        <!--start weather by hours-->
        <!--<div class="weather-by-hour">-->
        <!--<h2 class="weather-by-hour__heading">Today's weather</h2>-->
        <!--<div class="weather-by-hour__container">-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">3am</div>-->
        <!--<img src="icons/mostly-sunny.svg" alt="Mostly sunny">-->
        <!--<div>14°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">6am</div>-->
        <!--<img src="icons/mostly-sunny.svg" alt="Mostly sunny">-->
        <!--<div>16°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">9am</div>-->
        <!--<img src="icons/mostly-sunny.svg" alt="Mostly sunny">-->
        <!--<div>17°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">12pm</div>-->
        <!--<img src="icons/mostly-sunny.svg" alt="Mostly sunny">-->
        <!--<div>19°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">3pm</div>-->
        <!--<img src="icons/sunny.svg" alt="Sunny">-->
        <!--<div>21°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">6pm</div>-->
        <!--<img src="icons/sunny.svg" alt="Sunny">-->
        <!--<div>20°</div>-->
        <!--</div>-->
        <!--<div class="weather-by-hour__item">-->
        <!--<div class="weather-by-hour__hour">9pm</div>-->
        <!--<img src="icons/mostly-sunny.svg" alt="Mostly sunny">-->
        <!--<div>18°</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--end weather by hours-->

        <!--next 5 days-->
        <div class="next-5-days" >

            <div class="row" >

                <div class="col-md-8 col-sm-6" >
                    <h2 class="next-5-days__heading" >Next 5 days</h2 >
                </div >

                <div class="col-md-4 col-sm-6 text-right" >
                    <div class="btn-group" role="group" aria-label="Basic example" >
                        <button id="day" type="button" class="btn btn-primary active" >Day</button >
                        <button id="night" type="button" class="btn btn-primary" >Night</button >
                    </div >
                </div >

            </div >

            <div class="row" >

                <div class="col-12" >

                    <div id="nextFiveDaysContainer" class="next-5-days__container" >

                        <?php foreach ($forecast as $item) { ?>
                            <div class="next-5-days__row" >

                                <div class="next-5-days__date" >
                                    <div class="next-5-days__value" ><?php echo $item['dateDayName'] ?></div >
                                    <div class="next-5-days__label" ><?php echo $item['dateDayMonth'] ?></div >
                                </div >

                                <div class="next-5-days__icon" >
                                    <div class="next-5-days__value" ><img src="<?php echo $item['image'] ?>" /></div >
                                    <div class="next-5-days__label" ><?php echo $item['description'] ?></div >
                                </div >

                                <div class="next-5-days__low" >
                                    <div class="next-5-days__value" >Low</div >
                                    <div class="next-5-days__label" ><?php echo $item['temperatureCurrent'] ?>°</div >
                                </div >

                                <div class="next-5-days__high" >
                                    <div class="next-5-days__value" >High</div >
                                    <div class="next-5-days__label" ><?php echo $item['temperatureCurrent'] ?>°</div >
                                </div >

                                <div class="next-5-days__rain" >
                                    <div class="next-5-days__value" >Clouds</div >
                                    <div class="next-5-days__label" ><?php echo $item['clouds'] ?>%</div >
                                </div >

                                <div class="next-5-days__wind" >
                                    <div class="next-5-days__value" >Wind</div >
                                    <div class="next-5-days__label" >m/s</div >
                                </div >

                            </div >
                        <?php } ?>

                    </div >

                </div >

            </div >

        </div >
        <!--end next 8 days-->

        <!--start temperature chart-->
        <div >
            <canvas id="nextFiveDaysChart" width="400" height="150" ></canvas >
        </div >
        <!--end temperature chart-->

    </div >

    <!-- Footer -->
    <footer ><p class="footer__paragraph" >© Gerrit Degenhardt</p ></footer >

</main >

<!-- Optional JavaScript -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous" >
</script >
<script
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"
></script >
<script
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"
></script >

<!--Chart.js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"
        integrity="sha512-QEiC894KVkN9Tsoi6+mKf8HaCLJvyA6QIRzY5KrfINXYuP9NxdIkRQhGq3BZi0J4I7V5SidGM3XUQ5wFiMDuWg=="
        crossorigin="anonymous" ></script >

<script >

    let ctx = document.getElementById("nextFiveDaysChart").getContext('2d');
    drawChart(ctx);

    //(function () {


    function drawChart(ctx) {

        let data = '';
        data = <?php echo json_encode(getForecast($cityName, TIME_MODES::DAY)) ?>;

        $.ajax({
            url: "data.php",
            type: "POST",
            dataType: 'json',
            data: {
                mappedForecast: data
            },
            success: function (result) {
                let myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: result.legend,
                        datasets: [{
                            label: ' Temperature (°C)',
                            data: result.data,
                            backgroundColor: 'rgba(0, 0, 0, 0.1)',
                            borderColor: '#f4a91a',
                            clip: 50,
                            fill: false,
                            lineTension: 0.1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawTicks: true,
                                    offsetGridLines: false,
                                    zeroLineColor: 'rgba(255, 255, 255, 0.25)'
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: 'white',
                                    fontStyle: '300',
                                    fontSize: 14
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawTicks: true,
                                    offsetGridLines: false,
                                    zeroLineColor: 'rgba(255, 255, 255, 0.25)'
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: 'white',
                                    fontStyle: '300',
                                    fontSize: 14
                                },
                            }]
                        },
                        layout: {
                            padding: 25
                        },
                        legend: {
                            display: true,
                            labels: {
                                boxWidth: 2,
                                fontColor: 'white',
                                fontStyle: '300',
                                fontSize: 16
                            }
                        }
                    }
                });
            }
        });

    }

    //})();
</script >

</body >
</html >

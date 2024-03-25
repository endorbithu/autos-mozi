<?php
declare(strict_types=1);

namespace Tests\Feature;

class ScreeningApiTest extends RESTApi
{

    protected bool $seed = true;

    //URL: /api/$entityPath/...
    protected string $entityPath = 'screening';

    //Ezt a recordot fogja létrehozni
    protected array $createRecord = [
        "datetime" => "2024-04-04 15:00",
        "available_seats" => 10,
        "movie_id" => 1
    ];

    //Frissítéshez az értékek, amivel a createRecord-ot felülírjuk
    protected array $updateRecord = [
        "datetime" => "2024-05-04 15:00",
        "available_seats" => 0,
        "movie_id" => 2
    ];

    //Szándékos elrontása egy vagy több mezőnek, hogy teszteljük a validálást
    protected array $invalidFields = [
        "movie_id" => 9999999
    ];


}

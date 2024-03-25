<?php
declare(strict_types=1);

namespace Tests\Feature;

class MovieApiTest extends RESTApi
{

    protected bool $seed = true;
    //URL: /api/$entityPath/...
    protected string $entityPath = 'movie';

    //Ezt a recordot fogja létrehozni
    protected array $createRecord = [
        "title" => "Nobis sed.",
        "desc" => "Facilis odit perspiciatis dolores aut. Sunt et deleniti suscipit ut laborum. Qui quisquam omnis quas aut.",
        "lang" => "bg",
        "age_restrict" => 18,
        "cover_img" => "https://via.placeholder.com/640x480.png/005577?text=reiciendis"
    ];

    //Frissítéshez az értékek, amivel a createRecord-ot felülírjuk
    protected array $updateRecord = [
        "title" => "EEEE sed. upd",
        "desc" => "Facilis odit perspiciatis dolores aut. Sunt et deleniti suscipit ut laborum. Qui quisquam omnis quas aut. upd",
        "lang" => "hu",
        "age_restrict" => 6,
        "cover_img" => "https://via.placeholder.com/640x480.png/655556?text=reiciendis"
    ];

    //Szándékos elrontása egy vagy több mezőnek, hogy teszteljük a validálást
    protected array $invalidFields = [
        "age_restrict" => 99
    ];


}

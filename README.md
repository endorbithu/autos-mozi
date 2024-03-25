# Autósmozi tesztfeladat

A következő feladat során Dockerizált környezetben, Laravel keretrendszer használatával egy
egyszerű autósmozis alkalmazást kell elkészíteni a moziműsor adatainak kezelésére.
Lépések:

1. Tetszőleges relációs adatbázis beüzemelése és integrálása
2. Az alábbi adattáblák létrehozása, amelyeknek a következőket tartalmazniuk kell:
    - Film: cím, leírás, korhatári besorolás, nyelv, borítókép
    - Vetítés: időpont, elérhető helyek száma, film hivatkozás
3. Az adatbázis feltöltése tesztadatokkal
4. REST API implementálása a filmek és vetítések adatainak kezelésére
5. API dokumentáció elkészítése

6. Beadás:
   A feladat beadását Github repository formájában várjuk, dokumentációval együtt.

7. Bónusz Feladat:
   ReactJS alapú frontend alkalmazás készítése, ami a projekt adatainak megjelenítését teszi
   lehetővé

## Megoldás

### Setup

- Project root könyvtárban az `.env` fájl létrehozása `.env.example` alapján
- `laravel-app\.env.example` => `laravel-app\.env`
- `docker-compose up -d --build` (meg kell várni amíg a háttérben lefut a composer és a node container)
- hosts fájlba: `127.0.0.1 autosmozi`
- Laravel containerben (`docker exec -it app bash`)
    - `cd /html`
    - `php artisan migrate`
    - `php artisan db:seed`
- URL: http://autosmozi
    - (ha esetleg hibára fut, mert nem tudja írni a log fájlt, akkor `docker exec -it autosmozi bash`
      és `chmod 777 /var/www/html/storage -R`)
- (Ha a laravelnél később szükség lesz a `composer` vagy `npm` műveletekre, akkor így kell futtatni őket saját conatinerben):
    - `docker container start node` -> npm i + npm run build parancsok futnak
    - `docker container start composer` --> composer install parancs fut

### Applikáció áttekintése

#### Backend

- A REST API-ba bevont entitásoknak implementálniuk kell a`RESTModelInterface`-t, hogy egységesen lehessen őket kezelni
  a `Services\RESTService` által:
    - `public static function getItemsQuery(): Builder`
        - itt tudjuk előkészíteni az index/show lekérdezéshez az eloquent query-t az egyes entitásoknál, adatbázis
          relation-ök hozzáadása stb. a többi (pl lapozó) a `RESTService` van kidolgozva egységesen.
    - `public static function getPageLimit(): int`
        - itt adjuk vissza, hogy egy oldal hány elemből áll az adott entitásnál

- Entitás specifikus részek (`Movie` példáján keresztül):
    - `Http\Requests\MovieRequest` itt tudjuk a store/update -nél beküldött form (json) mezők validációs adatait
      megadni, és ez
      a request példány lesz beinjektálva a store/update action method-okba
    - `Http\Controllers\MovieController` action methodjaiban egy az egyben a `RESTService`-ugyanolyan nevű
      methodjának adjuk át a paramétereket, és az action method visszatérési értéke is egy az egyben a `RESTService`
      megfelelő methodjának a visszatérési értéke lesz. Ha eltérne egy művelet a `RESTService`-től, akkor mivel minden
      entitásnak saját Controllere van, ki lehet ott ez(eke)t a különbségeket dolgozni.
    - `routes/api.php` -ban  `Route::resource('movie', \App\Http\Controllers\MovieController::class);` elkészíti a REST
      route-okat és hozzákapcsolja a `MovieController`-hez.

#### Frontend (React Js)

`URL/movies`  
`URL/screenings`

Végpontokon lévő HTML oldalakon az entitások elemeit (lista 1. oldalát) betölti az oldalakon lévő táblázatba (lapozás
nem készült el).

#### API Teszt

- Feature teszt (HTTP requestekkel), végigviszi az összes REST műveletet mind a két entitásnál (SQLite in memory
  DB-t használva, hogy az adatbázis integritása ne sérüljön)
- `tests/Feature/RESTApi.php` -ből származtatott test classoknál csak az örökölt property-ket kell megfelelően kitölteni
  az egyes entitások adataival, és a `Feature/RESTApi` az adatok alapján teljeskörűen teszteli az egyes entitásokat.
- Futtatás: `php artisan test`

### API dokumentáció

#### Index oldal lapozással, kereséssel, rendezéssel

- `GET /api/{entitas}?GET_PARAMS`
- GET PARAMS:
    - `page` - `?page=2`
    - `where` - Eloquent `queryBuilder->where(...)` methodja által feldolgozható tömb reprezentációja
        - pl.:`?where[0][0]=id&where[0][1]=>&where[0][2]=21&where[1][0]=id&where[1][1]=<&where[1][2]=23`
    - `orderBy` - melyik attribútum szerint legyen rendezve pl.: `?orderBy[id]=desc`
- REQUEST body: üres
- RESPONSE:
    - ha nincsen `prev_page` property, akkor az 1. oldalon vagyunk
    - ha nincs `next_page` property, akkor az utolsó oldalon vagyunk

```
HTTP 200
{
    "message": "Retrieved",
    "count": 101,
    "page_limit": 10,
    "last_page": 11,
    "current_page": 2,
    "next_page": 3,
    "prev_page": 1,
    "data": [
        {
          "id": 11,
          "created_at":
          .....
        }, 
        {...},
        {...}
    ]
}
```

#### Egy elem lekérdezése

- `GET /api/{entitas}/{id}`
- REQUEST body: üres
- RESPONSE:
    - `movie` - a `Screenings` eloquent modelben definiált, és a `getItemsQuery()` methodban megdolgozott `BelongsTo`
      relation

``` 
HTTP 200
{
    "message": "Retrieved",
    "data": {
        "id": 11,
        "created_at": "2024-03-22 05:06:56",
        "updated_at": "2024-03-22 05:06:56",
        "datetime": "2024-04-30 06:51",
        "available_seats": 40,
        "movie_id": 11,
        "movie": {
            "id": 11,
            "title": "Voluptatem et expedita."
        }
    }
}
```

#### Hozzáadás

- `POST /api/{entitas}`
- REQUEST body:

``` 
{
    "title": "Nobis sed.",
    "desc": "Facilis odit perspiciatis dolores aut. Sunt et deleniti suscipit ut laborum. Qui quisquam omnis quas aut.",
    "lang": "bg",
    "age_restrict": 18,
    "cover_img": "https://via.placeholder.com/640x480.png/005577?text=reiciendis"
}
```

- RESPONSE:
    - `id` property az új rekord ID-ja

```
HTTP 201
{
    "message": "Created",
    "data": {
        "id": 12
    }
}
```

#### Módosítás

- `PUT /api/{entitas}/{id}`
- REQUEST body:

``` 
{
    "title": "Nobis sed.",
    "desc": "Facilis odit perspiciatis dolores aut. Sunt et deleniti suscipit ut laborum. Qui quisquam omnis quas aut.",
    "lang": "bg",
    "age_restrict": 18,
    "cover_img": "https://via.placeholder.com/640x480.png/005577?text=reiciendis"
}
```

- RESPONSE:

```
HTTP 200
{
    "message": "Updated"
}
```

#### Törlés

- `DELETE /api/{entitas}/{id}`
- REQUEST body: üres
- RESPONSE:

```
HTTP 200
{
    "message": "Deleted"
}
```

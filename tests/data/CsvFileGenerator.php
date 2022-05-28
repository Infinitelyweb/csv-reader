<?php

namespace Saswo\CsvReader\Tests\data;

use Faker\Factory;
use League\Csv\Writer;

class CsvFileGenerator
{
    public static function generateSimpleTestFile(string $file): void
    {
        $faker = Factory::create('DE_de');

        $columns = [
            'Nr', 'Nachname', 'Vorname', 'Geburtstag', 'Geschlecht', 'Stadt', 'Bemerkung'
        ];
        $csv = Writer::createFromPath($file, 'w+');
        $csv->setDelimiter(';');
        $csv->insertOne($columns);

        $totalRows = 100;
        for ($i = 1; $i <= $totalRows; $i++) {
            $fixture = [
                $i,
                $faker->lastName,
                $faker->firstName,
                $faker->dateTimeBetween('-100 years', 'now')->format('Y-m-d'),
                rand(0,1) === 1 ? 'M' : 'W',
                $faker->city,
                $faker->randomElements(['noticeA', 'noticeB', '####', null], 1)[0]
            ];
            $csv->insertOne($fixture);
        }
    }

    public static function generateTetfileWithoutHeader(string $file): void
    {
        $faker = Factory::create('DE_de');

        $csv = Writer::createFromPath($file, 'w+');
        $csv->setDelimiter(';');

        $totalRows = 10;
        for ($i = 1; $i <= $totalRows; $i++) {
            $csv->insertOne([
                $i,
                $faker->lastName,
                $faker->firstName,
                $faker->dateTimeBetween('-100 years', 'now')->format('Y-m-d'),
                rand(0,1) === 1 ? 'M' : 'W',
                $faker->city
            ]);
        }
    }
}
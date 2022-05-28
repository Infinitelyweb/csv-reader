<?php

namespace Saswo\CsvReader\Tests\data;

use Saswo\CsvReader\Attributes\IndexMapping;
use Saswo\CsvReader\Attributes\TitleMapping;

class Foo
{
    private int $id;
    private string $lastname;
    private string $firstname;
    private string $gender;
    private ?string $city;
    private string $country = 'DE';

    #[TitleMapping('Bemerkung')]
    private ?string $notice = null;

    public function getId(): int
    {
        return $this->id;
    }

    #[IndexMapping(0)]
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    #[TitleMapping('Nachname')]
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    #[TitleMapping('Vorname')]
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    #[TitleMapping('Geschlecht')]
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    #[TitleMapping('Stadt')]
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    #[TitleMapping('Country')]
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }


}
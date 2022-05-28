# Read CSV files and map to typed PHP models

![php 8.1+](https://img.shields.io/badge/php-min%208.1-blue.svg)

A simple library to read and map csv-files to typed PHP data model.

It uses the PHP 8's typed properties/arguments and
automatically converts string values to the typed defined
for your class fields.

It makes things easier.

## Usage

Define a class that represents your Data structure and use the
[`DataIndexMapping`](src/Attributes/IndexMapping.php)
or
[`DataTitleMapping`](src/Attributes/TitleMapping.php)
attributes to define the mapping.
Just map the ones you need in your model.

Your CSV-File:

```csv
 ;  Nachname;   Vorname;    Bemerkung
1;  Stumpf;     Magdalena;  
2;  Schomber;   Guenther;   noticeB
3;  Börner;     Ian;        ####
```

You want to convert the data of the csv-file into that object model:

```php
class Foo
{
    private int $id;
    private string $lastname;
    
    #[TitleMapping('Bemerkung')]
    private ?string $notice = null;

    [...]
  
    #[IndexMapping(0)]
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    #[TitleMapping('Nachname')]
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }
    
    [...]
}
```

Now use the `CsvReader` class to convert data to your object model:

```php
$csvReader = new CsvReader('myfilename.csv');
foreach ($csvReader->read(Foo::class) as $row) {
    echo sprintf(
        '%s has the Number %. Check notice: %s'
        $row->getLastname(), $row->getId(), $row->getNotice() ?? '-'
    )
}
```

## Value Converter - Supported types

Uses a value converter to convert values to the appropriate type of the property.

Supported types are:

- `int`
- `float`
- `string`
- `bool` (uses `filter_var()` with `FILTER_VALIDATE_BOOLEAN` and supports values like `on`, `true`, `1`)
- `DateTime`
- `DateTimeInterface`
- `DateTimeImmutable`

If a type uses a nullable type like `?int` - an empty string of the CSV value is going to mapped as null

## Options for CSV

You can set options for header, delimiter and enclosure.

Refer to the [`CsvReaderOptions`](src/CsvReaderOptions.php)
class to look at the options.
If you do not pass options to the CsvReader - the default values are taken.

##ToDos

- Error-Handling
- Unit-Tests
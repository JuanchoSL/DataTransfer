# DataTransfer

## Description

A small, lightweight utility to read values and properties from distinct sources using the same methodology

## Install

```bash
composer require juanchosl/datatransfer
composer update
```

## How use it

Load composer autoload and use it

## Data Transfer Objects

### Using the provided Factory

```php
$dto = JuanchoSL\DataTransfer\Factories\DataTransferFactory::create($element);
```

The **$element** parameter can be:
- array indexed or associtive
- object
- string with serialized object or array
- filepath containing a serialized object or array
- json encoded object or array
- json filepath with json extension containing a serialized object or array
- SimpleXMLElement object
- XML string or filepath with xml extension
- CSV filepath with csv extension
- INI filepath with ini extension
- YAML filepath with yml or yaml extension (require __yaml__ extension) 
- primitive value

### Using a specific repository

Alternative you can use the distincts repositories

```php
$dto = new JuanchoSL\DataTransfer\Repositories\{SOURCE_READER}($element)
```

#### Available origins and repositories

|   Type    | Compatibility                                     | Reader            |
|-----------|---------------------------------------------------|-------------------|
| Array     |filepath \| array \| string from array serialized  |ArrayDataTransfer  |
| CSV       |filepath \| array of lines \| string               |CsvDataTransfer    |
| INI       |filepath \| string                                 |IniDataTransfer    |
| JSON      |filepath \| string                                 |JsonDataTransfer   |
| stdClass  |filepath \| object \| string from object serialized|ObjectDataTransfer |
| XML       |filepath \| string \| SimpleXmlElement             |XmlDataTransfer    |
| YAML      |filepath \| string                                 |YamlDataTransfer   |

> The **$element** parameter needs to be the required type for the selected repo

> Is more efective use a magic factory in order to avoid know the type of the original data, and you can change it with no need to adapt your existing code.

> The resultant **$dto** variable is a recursive data transfer object, iterable, countable, clonable and json serializable

### Use data

```php
$dto = DataTransferFactory::create(['key' => 'value']);
echo $dto->has('key'); //true
echo $dto->get('key'); //value
echo $dto->get['key']; //value
echo $dto->key; //value
echo $dto->has('other_key'); //false
$dto->other_key = 'other_value';// alias for $dto->set('other_key','other_value')
echo $dto->has('other_key'); //true
echo $dto->other_key; //other_value
```

## TODO

When we need to use a CSV, YAML or INI contents to transform to a DTO, we need to use the native DataTransfer, today is not possible detect it as distinct of string or array


## Data Converters

You can convert any DataTransferObject to a standar format, as:

* array
* json
* xml
* SimpleXMLElement
* stdClass
* ini
* yaml
* csv

### Using the provided Factory

```php
$json = JuanchoSL\DataTransfer\Factories\DataConverterFactory::asJson($dto);
```

### Using a specific converter

Alternative you can use the distincts converters

```php
$result = new JuanchoSL\DataTransfer\Converters\{SOURCE_CONVERTER}($dto)
```

The available converters are:
- ArrayConverter
- CsvConverter
- JsonConverter
- ObjectConverter
- XmlConverter
- XmlObjectConverter
- CsvConverter
- YamlConverter
- IniConverter
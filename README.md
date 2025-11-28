# DataTransfer

## Description

A small, lightweight utility to read values and properties from distinct sources using the same methodology and convert it to a standard DTO.
Include a second tool that can convert any of DTOs to any of the know formats, creating a simple format converter from format A to format B.

## Install

```bash
composer require juanchosl/datatransfer
composer update
```

## How use it

Load composer autoload and use it

## Data Transfer Objects

### Using the provided Factory

The **$element** parameter can be:

- array indexed or associtive
- object that implements JsonSerializable interface
- string with serialized object or array
- filepath containing a serialized object or array
- json encoded object or array
- json filepath with _json_ extension containing a json encoded object or array
- SimpleXMLElement object
- Excel filepath with xlsx extension
- ODS string or filepath with ods extension
- XML string or filepath with xml extension
- CSV string or filepath with csv extension
- INI string or filepath with ini extension
- TAB separated values string or filepath with tsv extension
- YAML string or filepath with yml or yaml extension (require **yaml** php extension)
- primitive value

#### Using a filepath

Open and convert the contents included into file, alternatively you can specify the Format if the extension is not knowed

```php
$dto = JuanchoSL\DataTransfer\Factories\DataTransferFactory::byFile($element, Format $original_format = null);
```

#### Using a string

Try to detect and convert the contents included into string (json encoded, serialized object or array, xml, csv, yaml), alternatively you can specify the Format if the type is INI

```php
$dto = JuanchoSL\DataTransfer\Factories\DataTransferFactory::byString($element, Format $original_format = null);
```

#### Using a mime-type

You can try passing an iterable number of strings as standard mimetypes ir order to process with the first compatible

```php
$dto = JuanchoSL\DataTransfer\Factories\DataTransferFactory::byMimeType($element, string|iterable $mime_types);
```

#### Using a trasversable element

Detect and convert the trasversable element (object or array)

```php
$dto = JuanchoSL\DataTransfer\Factories\DataTransferFactory::byTrasversable($element);
```

### Using a specific repository

Alternative you can use the distincts repositories

```php
$dto = new JuanchoSL\DataTransfer\Repositories\{SOURCE_READER}($element)
```

#### Available origins and repositories

| Type     | Compatibility                                       | Reader                                        |
| -------- | --------------------------------------------------- | --------------------------------------------- |
| Array    | filepath \| array \| string from array serialized   | ArrayDataTransfer                             |
| stdClass | filepath \| object \| string from object serialized | ObjectDataTransfer                            |
| CSV      | filepath \| array of lines \| string                | CsvDataTransfer(,) or ExcelCsvDataTransfer(;) |
| INI      | filepath \| string                                  | IniDataTransfer                               |
| TAB      | filepath \| string                                  | TabsvDataTransfer                             |
| JSON     | filepath \| string                                  | JsonDataTransfer                              |
| XML      | filepath \| string \| SimpleXmlElement              | XmlDataTransfer                               |
| YAML     | filepath \| string                                  | YamlDataTransfer                              |
| XLSX     | filepath \| string                                  | ExcelXlsxDataTransfer                         |
| ODS      | filepath \| string                                  | OdsDataTransfer                               |

> The **$element** parameter needs to be the required type for the selected repo

> Is more efective use a magic factory in order to avoid know the type of the original data, and you can change it with no need to adapt your existing code.

> The resultant **$dto** variable is a recursive data transfer object, iterable, countable, clonable and json serializable

### Use data

```php
$dto = DataTransferFactory::create(['key' => 'value']);
echo $dto->has('key'); //true
echo $dto->get('key'); //value
echo $dto['key']; //value
echo $dto->key; //value
echo $dto->has('other_key'); //false
$dto->other_key = 'other_value';// alias for $dto->set('other_key','other_value')
echo $dto->has('other_key'); //true
echo $dto->other_key; //other_value
```

## Data Converters

You can convert any DataTransferObject to a standar format, as:

- array
- stdClass
- SimpleXMLElement
- json
- xml
- ini
- yaml
- csv
- tsv (tab separated)
- excel csv
- xlsx

### Using the provided Factory

```php
$json = JuanchoSL\DataTransfer\Factories\DataConverterFactory::asJson($dto);
```

### Using a mime-type

You can try passing an iterable number of strings as standard mimetypes ir order to convert with the first compatible

```php
$json = JuanchoSL\DataTransfer\Factories\DataConverterFactory::asMimeType($dto, 'application/json');
```

### Using a specific converter

Alternative you can use the distincts converters

```php
$converter = new JuanchoSL\DataTransfer\Converters\{SOURCE_CONVERTER}($dto);
$result = $converter->getData();
```

```php
$converter = new JuanchoSL\DataTransfer\Converters\{SOURCE_CONVERTER}();
$converter->setData($dto);
$result = $converter->getData();
```

The available converters are:

- CsvConverter (,)
- ExcelCsvConverter(;)
- ExcelXlsxConverter
- JsonConverter
- XmlConverter
- YamlConverter
- IniConverter
- TabsvConverter
- ArrayConverter
- ObjectConverter
- XmlObjectConverter

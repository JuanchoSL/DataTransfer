# Change Log DataTransfer

## [1.0.12] - 2025-11-07

### Added

- xml formatting when convert to string

### Changed

- mime type header 'application/vnd.ms-excel' is move to use the ExcelCsvFormat in oredr to add BOM to final string

### Fixed

- added some text encoding in order to convert data

## [1.0.11] - 2025-06-16

### Added

- More documentation on class functions

### Changed

- JSON converter returns string with pretty format when getData is called

### Fixed

- CSV converter apply enclosure where the character separator is present into string
- check if library is loaded before test for YAML file and strings

## [1.0.10] - 2025-06-07

### Added

- DataConverter using standard mimetype in order to convert a DataTrasfer object to a plain converted string, the available mimetypes to convert are:
  - application/json -> to json string
  - application/xml | text/xml -> to xml string
  - application/csv -> to excel csv, with ; as separator and BOM
  - text/csv -> to standard csv with , as separator
  - application/vnd.ms-excel -> to xslx using XLSWRITER extension
  - application/yaml -> to YAML format
- extract and check contents in order to know if is a csv or excel csv data
- extract and check contents in order to know if is a yaml data
- extract and check contents in order to know if is an ini data

### Changed

- examples into readme
- csv converter changed in order to filter subnodes when parse from xml data
- excel converter parsing data, perform a previous csv conversion in order to solve issues from xml data

### Fixed

- some readme examples
- excel tests for non loaded extension environments

## [1.0.9] - 2025-06-05

### Added

### Changed

### Fixed

- XML converters have a trouble when a value is a number

## [1.0.8] - 2024-12-13

### Added

- Excel XLSX reader compatibility using XLSWRITER php extension
- Excel XLSX converter compatibility using XLSWRITER php extension
- PHP 8.4 test compatibility

### Changed

- ExcelCsvConverter use BOM when cast to string

### Fixed

- CSV converters enclousure headers between " when a space is detected in the text
- Null values from data container return false on has check

## [1.0.7] - 2024-10-14

### Added

- Yaml reader compatibility
- Yaml converter compatibility
- INI reader compatibility
- INI converter compatibility
- CSV for/from Excel with semicolon separator compatibility
- requirements into composer for JSON extension
- suggestion into composer for YAML extension
- ArrayIterator implementation
- DataContainer in order to group data without modification with Object attribute access
- Collection class in order to create iterable grouped data
- All Converers are now Stringable, in order to cast to string for simplify text creation

### Changed

- JsonDataTransfer accept a filepath as string too in order to read his contents as json
- ObjectDataTransfer accept a string too in order to read as a filepath or as a serialized object
- ArrayDataTransfer accept a string too in order to read as a filepath or as a serialized array
- XmlObjectDataTransfer is renamed to XmlDataTransfer and accept a filepath as string and a xml string too

### Fixed

- Use inheritance in order to reduce memory consumption
- Added CDATA for child nodes on XMLConverters
- use validators

## [1.0.6] - 2024-06-20

### Added

- UTF-8 string conversion from Factory for plain strings
- ArrayAcces compatibility

### Changed

- removed redundant JSON subclases and use only JsonDataTransferClass
- unset method called remove in order to aviod collision
- append method return the int value for the assigned position

### Fixed

- Fix for non collection passed to CsvConverter

## [1.0.5] - 2024-06-14

### Added

- SimpleXMLElement compatibility using XmlObjectDataTransfer
- CSV contents file compatibility using CsvDataTransfer
- xml string and SimpleXMLElement from DataTransferFactory
- Data converters, in order to transform DTO to:
  - JSON string
  - Array
  - Object
  - XML string
  - XML Entity object
  - CSV lines array
- append method in order to push data to indexed DTO

### Changed

- Fatories namespace for DataTransferFactory
- when get, if not exist, return converted default value or null

### Fixed

## [1.0.4] - 2024-01-18

### Added

- strict types declaration
- BaseCollectionable class

### Changed

- strings returned unalterated

### Fixed

- Quality code
- null values

## [1.0.3] - 2023-10-01

### Added

- new empty method in order to check if data transfer do not have elements

### Changed

- Reorder class structure

### Fixed

- Fix objects iterator

## [1.0.2] - 2023-07-19

### Added

- Factory in order to create the necessary instance automatically

### Changed

### Fixed

## [1.0.1] - 2023-06-20

### Added

- More writeing tests

### Changed

- Convert values to DataTransfer entities in a recursive mode when is setted

### Fixed

## [1.0.0] - 2023-06-16

### Added

- Initial release, first version

### Changed

### Fixed

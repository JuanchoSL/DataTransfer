# Change Log DataTransfer


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

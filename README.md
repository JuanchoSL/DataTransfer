# DataTransfer

## Description
A small, lightweight utility to read values and properties from distinct sources using the same methodology


## Install
```
composer require juanchosl/datatransfer
```

## How use it
Load composer autoload and use the JuanchoSL\DataTransfer\Repositories\{SOURCE_READER} class

### Load data

```
$dto = new ArrayDataTransfer(['key' => 'value']);
$dto->has('key'); //true
echo $dto->get('key'); //value
echo $dto->key; //value
```

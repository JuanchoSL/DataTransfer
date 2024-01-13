# DataTransfer

## Description
A small, lightweight utility to read values and properties from distinct sources using the same methodology

## Install
```
composer require juanchosl/datatransfer
```

## How use it
Load composer autoload and use it

### Using the provided Factory
```
$dto = JuanchoSL\DataTransfer\DataTransferFactory::create($element);
```

### Using a specific repository
Alternative you can use the distincts repositories
```
$dto = new JuanchoSL\DataTransfer\Repositories\{SOURCE_READER}($element)
```

> The __$element__ parameter can be:
>- array indexed or associtive
>- object
>- json encoded object or array
>- primitive value

>The resultant __$dto__ variable is a recursive data transfer object, iterable, countable, clonable and json serializable

>Is more efective use a magic factory in order to avoid know the type of the original data, and you can change it with no need to adapt your existing code.

### Use data
```
$dto = DataTransferFactory::create(['key' => 'value']);
echo $dto->has('key'); //true
echo $dto->get('key'); //value
echo $dto->key; //value
echo $dto->has('other_key'); //false
$dto->other_key = 'other_value';// alias for $dto->set('other_key','other_value')
echo $dto->has('other_key'); //true
echo $dto->other_key; //other_value
```
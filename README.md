# DiContainer
[![Build](https://github.com/pattisahusiwa/dicontainer/workflows/Build/badge.svg?branch=master)](https://github.com/pattisahusiwa/dicontainer/actions)
[![Coverage Status](https://coveralls.io/repos/github/pattisahusiwa/dicontainer/badge.svg?branch=master&service=github)](https://coveralls.io/github/pattisahusiwa/dicontainer?branch=master)
[![License](https://img.shields.io/github/license/pattisahusiwa/dicontainer)](https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/xynha/dicontainer)](https://packagist.org/packages/xynha/dicontainer)
[![Stable](https://img.shields.io/packagist/v/xynha/dicontainer?label=stable)](https://packagist.org/packages/xynha/dicontainer)
[![unstable](https://img.shields.io/packagist/v/xynha/dicontainer?include_prereleases&label=unstable)](https://packagist.org/packages/xynha/dicontainer)

PSR-11 compliant PHP dependency injection container.

## Installation
Use [composer](https://getcomposer.org/) to install the library.
```bash
composer require xynha/dicontainer
```

## Usage
`DiContainer` can be used with or without configuration.
All classes that do not required constructor arguments, can be created directly.
````php
// Without configuration
$dic = new DiContainer(new DiRuleList);

$obj = $this->dic->get('classname');
````

However, for complex initialization, you have to define `DiContainer` rules and pass them to `DiRuleList`.
````php
// Construct rule list
$rlist = new DiRuleList();

// Add configuration from file
$json = json_decode(file_get_contents('filename'), true);
$rlist = $rlist->addRules($json);

// Add configuration manually
$rule['shared'] = true;
$rlist = $rlist->addRule('classname', $rule);

// Construct the container
$dic = new DiContainer($rlist);

$obj = $this->dic->get('classname');
````



## DiContainer rules
General format of `DiContainer` rules in JSON format:
````json
{
  "rule_key" : {
    "config_keys": "config_value"
  },
  "another_rule_key" : {
    "config_keys": "config_value"
  }
}
````
Where:
  - `rule_key`, `another_rule_key` are a class or an interface namespace, and
  - `config_keys` are supported rule keys:
    - `shared`
    - `instanceOf`
    - `constructParams`
    - `substitutions`
    - `getFrom`

### # `shared`: `boolean` (default `false`)
  - Possible values:
    * `true`, if the constructed instance can be shared betwen classes during execution,
    * `false`, otherwise.
  - Example:
    ````json
    "classA" : {
      "shared" : true
    },
    "classB" : {
      "shared" : false
    }
    ````

### # `instanceOf`: `string|object` (default `null`)
  - To override class or interface defined in `rule_key`.
  - Possible values:
    - `string`: class namespace
    - `object`: already constructed instance.
  - Example:
    ````json
    "interfaceA" : {
      "instanceOf" : "classA"
    }
    ````
    Example of passing already constructed instance,
    ````php
    $rlist = new DiRuleList();
    $rule['interfaceA'] = ['instanceOf' => $object];
    $rlist = $rlist->addRules($rule);
    ````

### # `constructParams`: `array` (default `null`)
  - `constructParams` is a list of constructor argument values.
  - Example:
    ````json
    "classA" : {
      "constructParams" : [
        "arg1",
        "arg2"
      ]
    }
    ````
  - Construct params can also be retrieved from another class by using `CALL::OBJECT` and `CALL::SCALAR` identifiers.
  - `CALL::SCALAR` is used to retrieve `array` and `scalar` values
  - `CALL::OBJECT` is used to retrieve `object` value.
  - `CALL::OBJECT` and `CALL::SCALAR` format:
  ````json
  "constructParams": [
    ["CALL::SCALAR", ["callback"], ["array_of_callback_arguments"]],
    ["CALL::OBJECT", ["callback"], ["array_of_callback_arguments"]]
  ]
  ````
  - Example:
  ````php
  class Config{
    public function getConfig(string $key){
      if ($key === 'db'){
        return 'dbconfig';
      }
    }
  }

  class DatabaseDriver{
    public function __construct(string $config){}
  }
  ````
  \# if constructed manually,
  ````php
  $config = new Config();
  $driver = new DatabaseDriver($config->getConfig('db'));
  ````
  \# using `DiContainer`
  ````json
  {
    "DatabaseDriver" : {
      "constructParams" : [
        ["CALL::SCALAR", ["Config","getConfig"], ["db"]]
      ]
    }
  }
  ````
  ````php
  $driver = $dic->get(DatabaseDriver::class);
  ````

### # `substitutions`: associative `array` (default `null`)
  - Substitute interface in the constructor argument with a substituted class.
  - Example
    ````json
    "classA" : {
      "substitutions" : {
        "interfaceB" : "classB",
        "interfaceC" : "classC",
      }
    }
    ````

### # `getFrom`: `array` (default `null`)
  - Get `rule_key` instance from a factory or a class.
  - `getFrom` format
    ````json
    "getFrom" : [
      ["callback"],
      [ "array_of_callback_arguments" ]
    ]
    ````
  - Example
    ````json
    "classA" : {
      "getFrom" : [
        ["FactoryClassA", "getClassA"],
        ["arg1","arg2"]
      ]
    },
    "interfaceB" : {
      "getFrom" : [
        ["FactoryClassB", "getClassB"],
        ["arg1",["array", "arguments"]]
      ]
    }
    ````

## Use Cases

Please check unit test configuration files in `tests/Data/config` and the corresponding tests.


## Contributing
All form of contributions are welcome. You can [report issues](https://github.com/pattisahusiwa/dicontainer/issues), fork the repo and submit pull request.

For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
Released under [Apache-2.0 License](https://opensource.org/licenses/Apache-2.0). See [LICENSE](https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE) file for more details.

````
   Copyright 2020 Asis Pattisahusiwa

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
````

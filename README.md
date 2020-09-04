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

However, for complex initialization, you have to define construction rules.
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
## Contributing
All form of contributions are welcome. You can [report issues](https://github.com/pattisahusiwa/dicontainer/issues), fork the repo and submit pull request.

For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[Apache-2.0 License](https://opensource.org/licenses/Apache-2.0). See [LICENSE](https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE) file.

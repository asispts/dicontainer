# DiContainer
[![License](https://poser.pugx.org/xynha/dicontainer/license)](//packagist.org/packages/xynha/dicontainer)
[![Latest Stable Version](https://poser.pugx.org/xynha/dicontainer/v)](//packagist.org/packages/xynha/dicontainer)
[![Latest Unstable Version](https://poser.pugx.org/xynha/dicontainer/v/unstable)](//packagist.org/packages/xynha/dicontainer)
[![Total Downloads](https://poser.pugx.org/xynha/dicontainer/downloads)](//packagist.org/packages/xynha/dicontainer)

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

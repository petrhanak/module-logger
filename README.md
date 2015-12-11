# Logger Module
[![Build Status](https://scrutinizer-ci.com/g/WildPHP/module-logger/badges/build.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-logger/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WildPHP/module-logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-logger/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wildphp/module-logger/v/stable)](https://packagist.org/packages/wildphp/module-logger)
[![Latest Unstable Version](https://poser.pugx.org/wildphp/module-logger/v/unstable)](https://packagist.org/packages/wildphp/module-logger)
[![Total Downloads](https://poser.pugx.org/wildphp/module-logger/downloads)](https://packagist.org/packages/wildphp/module-logger)

This module shows information about a link when it is posted in a channel.

## System Requirements
If your setup can run the main bot, it can run this module as well.

## Installation
To install this module, we will use `composer`:

composer require wildphp/module-logger

That will install all required files for the module. In order to activate the module, add the following line to your `main.modules` file:

    WildPHP\CoreModules\Logger\Logger

The bot will run the module the next time it is started.

## License
This module is licensed under the GNU General Public License, version 3. Please see `LICENSE` to read it.

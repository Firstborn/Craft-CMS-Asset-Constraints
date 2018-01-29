# Asset Constraints for [Craft CMS](https://craftcms.com/)
As files are uploaded via the asset manager they will be checked against the constraints you define. If a constraint is not passed the upload will be rejected.

## Installation
1. Download a copy of this repository.
1. Move the `assetconstraints` directory into your `craft/plugins` directory.
1. Go to Settings &gt; Plugins from your Craft control panel and enable the `Asset Constraints` plugin.

## Use
Go into the Asset Constraints settings and add one or more constraints. Each constraint consists of a source folder, file type and maximum file size. To completely block files of a certain type set the maximum size to '0'. Whenever a file is uploaded via the Asset panel it will be checked against constraints that match the destination folder. If the file fails a constraints test the upload will be rejected.

## Configuration settings
In addition to the locally set constraints that can be defined on the settings screen you can also use the `config.php` file to set constraints. To use the config file, copy the `config.php` file to a file called `assetconstraints.php` and move it to the `craft/config` directory. Use the example in the file as a guide to setup constraints. 

#### Credits

The entire dev team at [Firstborn](https://www.firstborn.com/)

File icon by rajakumara from the [Noun Project](https://thenounproject.com/)

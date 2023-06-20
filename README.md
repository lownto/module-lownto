# Lownto for Magento 2

## License

This module and the code herein is proprietary. The code must not be edited or redistributed without the advance 
written permission of Colin Tickle ([colin.tickle@gmail.com](mailto:colin.tickle@gmail.com)).

## Usage
In addition to the modules contained herein, an additional folder needs creating in the `./app/code` folder of your
Magento installation.

The folder structure needs to be: `./app/code/lownto/dynamic_configuration`.

Inside that folder, create a single file named `registration.php`.

The file must contain the following contents, modified to point at your Lownto NodeRED endpoint.
```php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Lownto_DynamicConfiguration',
    'http://eg-nodered:1880'
);
```

# Lownto for Magento 2

## License

This module and the code herein is proprietary. The code must not be edited or redistributed without the advance 
written permission of Colin Tickle ([colin.tickle@gmail.com](mailto:colin.tickle@gmail.com)).

## Installation

### This module (in Magento)

Run the following commands in your Magento root folder.

```bash
composer config repositories.lownto/module-lownto vcs git@github.com:lownto/module-lownto.git
composer config repositories.cmtickle/module-event-thing vcs git@github.com:cmtickle/module-event-thing.git
composer require lownto/module-lownto:dev-main cmtickle/module-event-thing:dev-main
```

Once the above completes, we need a module to specify how to connect to NodeRED. Create that module as below.

```bash
mkdir -p ./app/code/lownto/dynamic_configuration
```

Inside the folder you just created, create a single file named `registration.php`.

The file must contain the following contents, modified to point at your Lownto NodeRED endpoint.
```php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Lownto_DynamicConfiguration',
    'http://eg-nodered:1880'
);
```

### NodeRED portion of install.

Follow the instructions at : [lownto/node-red-lownto/README.md](https://github.com/lownto/node-red-lownto/blob/main/README.md)

### Once NodeRED is configured...

When you have followed the instructions for NodeRED, you'll need to enable the Magento modules as per the below commands:

```bash
bin/magento module:enable Cmtickle_EventThing Lownto_RemoteConfig Lownto_DynamicConfiguration
bin/magento setup:upgrade
```

Once you've followed the above you should have NodeRED integrated to Magento via Lownto.

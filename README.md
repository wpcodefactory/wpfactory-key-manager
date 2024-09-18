# WPFactory Key Manager

A library meant to be imported by the Pro version of WPFactory plugins to manage client product keys.

## Installation

Installation via Composer. Instructions to setup the `composer.json`.

1. Add this object to the `repositories` array:

```json
"repositories": [    
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-key-manager"
    }
]
```

2. Require the library:

```json
"wpfactory/wpfactory-key-manager": "*"
```

**Full Example:**

```json
{
  "repositories": [    
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-key-manager"
    }
  ],
  "require": {    
    "wpfactory/wpfactory-key-manager": "*"
  },
  "config": {
    "preferred-install": "dist"
  }
}
```

## How to use it?
First, just require Composer autoloader:
```php
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
```

Then initialize the library with `wpf_key_manager()`.

```php
// Initializes WPFactory Key Manager library.
wpf_key_manager();
```

It's probably a good idea to use it only on pro version.

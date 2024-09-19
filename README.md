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
    },
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-admin-menu"
    },
]
```

2. Require the library and its dependencies:

```json
"require": {    
    "wpfactory/wpfactory-key-manager": "dev-master",    
    "wpfactory/wpfactory-admin-menu": "*"
  },
```

3. Use `preferred-install` parameter set as `dist` on `config`.

```json
"config": {
  "preferred-install": "dist"
}
```

**Full Example:**

```json
{
  "repositories": [    
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-key-manager"
    },
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-admin-menu"
    },
  ],
  "require": {    
    "wpfactory/wpfactory-key-manager": "*",
    "wpfactory/wpfactory-admin-menu": "*"
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

> [!NOTE]  
> It's probably a good idea to use it only on pro version.

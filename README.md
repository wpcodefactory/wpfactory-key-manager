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
  }
]
```

2. Require the library and its dependencies:

```json
"require": {
  "wpfactory/wpfactory-key-manager": "*",
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
    }
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
1. Put the composer.json on the pro folder. In general it should be located in `includes/pro`, or `src/php/pro`. Example:
```
src/
├── php/
│   ├── pro/    
│   │   └── composer.json
└── ...
```

2. Require the Composer `autoload.php` only on the pro version. Example:
```php
require_once plugin_dir_path( $this->get_plugin_file_path() ) . '/src/php/pro/vendor/autoload.php';
```

3. Then initialize the library with `wpfactory_key_manager()` from within the Pro class.
- Probably the best place is inside the hook `plugins_loaded`. If the Pro class is already being loaded with that hook, you can simply load the key manager in the class constructor.
- Probably it's a good idea to run it after a `is_admin()` check.

```php
add_action( 'plugins_loaded', function(){
    $pro_plugin = new Pro_Plugin();
} );
```

```php
class Pro_Plugin(){
    function __construct() {
        // Composer.
        require_once plugin_dir_path( $this->get_plugin_file_path() ) . '/src/php/pro/vendor/autoload.php';

        // Initializes WPFactory Key Manager library.
        if ( is_admin() ) {
            function_exists( 'wpfactory_key_manager' ) ? wpfactory_key_manager() : wpf_key_manager();
        }
    }
}
```

> [!NOTE]  
> For compatibility reasons, please check if `wpf_key_manager()` function exists before calling `wpfactory_key_manager()`. Full example: `function_exists( 'wpfactory_key_manager' ) ? wpfactory_key_manager() : wpf_key_manager();`

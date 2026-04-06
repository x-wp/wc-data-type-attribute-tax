<div align="center">

<h1 align="center" style="border-bottom: none; margin-bottom: 0px">WC Data Attribute Tax</h1>
<h3 align="center" style="margin-top: 0px">WooCommerce attribute taxonomy data objects</h3>

[![Packagist Version](https://img.shields.io/packagist/v/x-wp/wc-data-attribute-tax?label=Release&style=flat-square)](https://packagist.org/packages/x-wp/wc-data-attribute-tax)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/x-wp/wc-data-attribute-tax/php?label=PHP&logo=php&logoColor=white&logoSize=auto&style=flat-square)
![Static Badge](https://img.shields.io/badge/WP-%3E%3D6.5-3858e9?style=flat-square&logo=wordpress&logoSize=auto)
![Static Badge](https://img.shields.io/badge/WC-%3E%3D9.0-7f54b3?style=flat-square&logo=woocommerce&logoSize=auto)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/x-wp/wc-data-attribute-tax/tests.yml?label=Tests&event=push&style=flat-square&logo=githubactions&logoColor=white&logoSize=auto)](https://github.com/x-wp/wc-data-attribute-tax/actions/workflows/tests.yml)

</div>

This package provides `XWC_Attribute_Tax` data objects for working with WooCommerce product attribute taxonomies. Built on the [`x-wp/wc-data-type`](https://github.com/x-wp/wc-data-type) framework, it wraps WooCommerce's `wc_*_attribute()` functions in a model-driven CRUD workflow with typed properties, a dedicated factory, and convenience helpers.

## Installation

```bash
composer require x-wp/wc-data-attribute-tax
```

> [!TIP]
> We recommend using `automattic/jetpack-autoloader` with this package to reduce autoloading conflicts in WordPress environments.

## Usage

### Loading an attribute

```php
<?php

// By ID
$attribute = xwc_get_attribute_tax( 5 );

// By name/slug
$attribute = xwc_get_attribute_tax( 'color' );

// By label (creates if it doesn't exist)
$attribute = XWC_Attribute_Tax::from_label( 'Color' );
```

### Working with attribute properties

```php
<?php

$attribute = xwc_get_attribute_tax( 'color' );

$attribute->get_label();          // 'Color'
$attribute->get_name();           // 'color'
$attribute->get_type();           // 'select'
$attribute->get_orderby();        // 'menu_order'
$attribute->get_public();         // true
$attribute->get_taxonomy_name();  // 'pa_color'
$attribute->get_terms();          // array of WP_Term objects
```

### Converting to WC_Product_Attribute

```php
<?php

$attribute = xwc_get_attribute_tax( 'color' );
$wc_attr   = $attribute->get_wc_attribute( position: 0 );
```

### Utility functions

```php
<?php

// Data store access
$data_store = xwc_att_ds();

// Get attribute by ID (with default)
$attribute = xwc_get_attribute_tax( $id, false );

// Get a fresh instance by ID
$attribute = xwc_get_attribute_tax_object( $id );

// Find attribute ID by label
$id = xwc_get_attribute_tax_id_by_label( 'Color' );
```

## Testing

The package ships with a PHPUnit suite that boots a WordPress + WooCommerce test environment.

Local prerequisites:

- Docker with `docker compose`
- PHP
- WP-CLI (`wp`)
- MySQL client tools (`mysql` and `mysqladmin`)
- `curl`
- `unzip`

Run the suite with:

```bash
composer test
```

To prepare the local WordPress test environment first:

```bash
composer test:install
composer test
```

`composer test:install` starts the MySQL service, downloads WordPress, installs WooCommerce, and prepares the WordPress test suite under `.cache/wp-tests`.

To fully reset the local test environment:

```bash
composer test:clean
```

That command stops the test containers and removes the cached WordPress test files.

## Documentation

Core classes:

- `XWC_Attribute_Tax` &mdash; attribute taxonomy entity
- `XWC_Attribute_Tax_Factory` &mdash; string-aware factory for resolving attributes by name or label
- `XWC_Attribute_Tax_Data_Store` &mdash; data persistence layer using WooCommerce attribute functions

For framework-level documentation, see [`x-wp/wc-data-type`](https://github.com/x-wp/wc-data-type).

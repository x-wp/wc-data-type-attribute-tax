<?php
/**
 * Plugin Name: XWC Attribute Tax Test Bootstrap
 * Description: Test-only plugin wrapper for the XWC attribute tax package.
 */

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

xwc_register_entity(XWC_Attribute_Tax::class);

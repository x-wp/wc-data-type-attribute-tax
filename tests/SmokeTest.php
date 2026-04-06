<?php

declare(strict_types=1);

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use XWC\Data\Entity_Manager;

final class SmokeTest extends TestCase {
    public function test_wordpress_woocommerce_and_package_bootstrap_together(): void {
        $this->assertTrue( function_exists( 'add_action' ) );
        $this->assertTrue( class_exists( 'WooCommerce' ) );
        $this->assertTrue( class_exists( Entity_Manager::class ) );

        $manager = Entity_Manager::instance();

        $this->assertInstanceOf( Entity_Manager::class, $manager );
    }

    public function test_attribute_tax_classes_are_loaded(): void {
        $this->assertTrue( class_exists( 'XWC_Attribute_Tax' ) );
        $this->assertTrue( class_exists( 'XWC_Attribute_Tax_Factory' ) );
        $this->assertTrue( class_exists( 'XWC_Attribute_Tax_Data_Store' ) );
    }

    public function test_utility_functions_are_loaded(): void {
        $this->assertTrue( function_exists( 'xwc_att_ds' ) );
        $this->assertTrue( function_exists( 'xwc_get_attribute_tax' ) );
        $this->assertTrue( function_exists( 'xwc_get_att_tax' ) );
        $this->assertTrue( function_exists( 'xwc_get_attribute_tax_object' ) );
        $this->assertTrue( function_exists( 'xwc_get_att_tax_obj' ) );
        $this->assertTrue( function_exists( 'xwc_get_attribute_tax_id_by_label' ) );
        $this->assertTrue( function_exists( 'xwc_get_att_tax_by_label' ) );
    }

    public function test_attribute_tax_entity_is_registered(): void {
        $this->assertTrue( xwc_entity_exists( 'attribute_tax' ) );
    }
}

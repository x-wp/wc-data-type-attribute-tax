<?php

declare(strict_types=1);

require_once __DIR__ . '/Support/AttributeTaxTestHelper.php';

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class UtilityFunctionsTest extends TestCase {
    use AttributeTaxTestHelper;

    protected function tear_down(): void {
        $this->cleanup_test_attributes();

        parent::tear_down();
    }

    public function test_xwc_att_ds_returns_data_store_instance(): void {
        $ds = xwc_att_ds();

        $this->assertInstanceOf( XWC_Attribute_Tax_Data_Store::class, $ds );
    }

    public function test_xwc_get_attribute_tax_returns_object_for_valid_id(): void {
        $id  = $this->create_test_attribute( [ 'name' => 'Util Test', 'slug' => 'util-test' ] );
        $att = xwc_get_attribute_tax( $id );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertSame( $id, $att->get_id() );
    }

    public function test_xwc_get_attribute_tax_returns_default_for_invalid_id(): void {
        $this->assertFalse( xwc_get_attribute_tax( 999999 ) );
        $this->assertNull( xwc_get_attribute_tax( 999999, null ) );
    }

    public function test_xwc_get_att_tax_is_alias(): void {
        $id  = $this->create_test_attribute( [ 'name' => 'Alias Test', 'slug' => 'alias-test' ] );
        $att = xwc_get_att_tax( $id );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertSame( $id, $att->get_id() );
    }

    public function test_xwc_get_attribute_tax_object_returns_instance(): void {
        $att = xwc_get_attribute_tax_object( 0 );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertSame( 0, $att->get_id() );
    }

    public function test_xwc_get_att_tax_obj_is_alias(): void {
        $att = xwc_get_att_tax_obj( 0 );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertSame( 0, $att->get_id() );
    }

    public function test_xwc_get_attribute_tax_id_by_label_returns_id(): void {
        $id = $this->create_test_attribute( [ 'name' => 'Material', 'slug' => 'material' ] );

        $this->assertSame( $id, xwc_get_attribute_tax_id_by_label( 'Material' ) );
    }

    public function test_xwc_get_attribute_tax_id_by_label_returns_zero_for_null(): void {
        $this->assertSame( 0, xwc_get_attribute_tax_id_by_label( null ) );
    }

    public function test_xwc_get_att_tax_by_label_is_alias(): void {
        $id = $this->create_test_attribute( [ 'name' => 'Fabric', 'slug' => 'fabric' ] );

        $this->assertSame(
            xwc_get_attribute_tax_id_by_label( 'Fabric' ),
            xwc_get_att_tax_by_label( 'Fabric' ),
        );
    }
}

<?php

declare(strict_types=1);

require_once __DIR__ . '/Support/AttributeTaxTestHelper.php';

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class AttributeTaxDataStoreTest extends TestCase {
    use AttributeTaxTestHelper;

    protected function tear_down(): void {
        $this->cleanup_test_attributes();

        parent::tear_down();
    }

    private function get_data_store(): XWC_Attribute_Tax_Data_Store {
        return xwc_att_ds();
    }

    public function test_reformat_data_remaps_keys(): void {
        $ds     = $this->get_data_store();
        $method = new ReflectionMethod( $ds, 'reformat_data' );
        $method->setAccessible( true );

        $result = $method->invoke( $ds, [
            'label' => 'Color',
            'name'  => 'color',
            'type'  => 'select',
        ] );

        $this->assertArrayHasKey( 'name', $result );
        $this->assertArrayHasKey( 'slug', $result );
        $this->assertArrayNotHasKey( 'label', $result );
        $this->assertSame( 'select', $result['type'] );
    }

    public function test_reformat_data_filters_empty_strings(): void {
        $ds     = $this->get_data_store();
        $method = new ReflectionMethod( $ds, 'reformat_data' );
        $method->setAccessible( true );

        $result = $method->invoke( $ds, [
            'label' => 'Size',
            'name'  => 'size',
            'type'  => '',
        ] );

        $this->assertArrayNotHasKey( 'type', $result );
    }

    public function test_delete_zeros_id(): void {
        $id  = $this->create_test_attribute( [ 'name' => 'Delete Me', 'slug' => 'delete-me' ] );
        $att = xwc_get_attribute_tax( $id );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );

        $ds = $this->get_data_store();
        $ds->delete( $att );

        // Remove from cleanup list since we already deleted it.
        $this->created_attribute_ids = array_diff( $this->created_attribute_ids, [ $id ] );

        $this->assertSame( 0, $att->get_id() );
    }

    public function test_register_tax_registers_taxonomy(): void {
        $ds = $this->get_data_store();

        $ds->register_tax( 999, [
            'attribute_name'  => 'test_reg_tax',
            'attribute_label' => 'Test Reg Tax',
        ] );

        $this->assertTrue( taxonomy_exists( 'pa_test_reg_tax' ) );
    }
}

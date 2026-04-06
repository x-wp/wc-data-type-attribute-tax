<?php

declare(strict_types=1);

require_once __DIR__ . '/Support/AttributeTaxTestHelper.php';

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class AttributeTaxEntityTest extends TestCase {
    use AttributeTaxTestHelper;

    protected function tear_down(): void {
        $this->cleanup_test_attributes();

        parent::tear_down();
    }

    public function test_new_entity_has_default_values(): void {
        $att = xwc_get_attribute_tax_object( 0 );

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertSame( 'menu_order', $att->get_orderby() );
        $this->assertTrue( $att->get_public() );
    }

    public function test_set_and_get_core_props(): void {
        $att = xwc_get_attribute_tax_object( 0 );

        $att->set_label( 'Color' );
        $att->set_name( 'color' );
        $att->set_type( 'select' );
        $att->set_orderby( 'name' );
        $att->set_public( false );

        $this->assertSame( 'Color', $att->get_label() );
        $this->assertSame( 'color', $att->get_name() );
        $this->assertSame( 'select', $att->get_type() );
        $this->assertSame( 'name', $att->get_orderby() );
        $this->assertFalse( $att->get_public() );
    }

    public function test_call_backport_strips_attribute_prefix(): void {
        $att = xwc_get_attribute_tax_object( 0 );
        $att->set_label( 'Size' );

        $this->assertSame( 'Size', $att->get_attribute_label() );

        $att->set_attribute_type( 'select' );

        $this->assertSame( 'select', $att->get_type() );
    }

    public function test_set_attribute_id_and_get_attribute_id_backport(): void {
        $att = xwc_get_attribute_tax_object( 0 );

        $att->set_attribute_id( 42 );

        $this->assertSame( 42, $att->get_attribute_id() );
        $this->assertSame( 42, $att->get_id() );
    }

    public function test_get_taxonomy_name(): void {
        $att = xwc_get_attribute_tax_object( 0 );
        $att->set_name( 'color' );

        $this->assertSame( 'pa_color', $att->get_taxonomy_name() );
    }

    public function test_from_label_creates_and_returns_attribute(): void {
        $att = XWC_Attribute_Tax::from_label( 'Test From Label' );

        $this->created_attribute_ids[] = $att->get_id();

        $this->assertInstanceOf( XWC_Attribute_Tax::class, $att );
        $this->assertGreaterThan( 0, $att->get_id() );
        $this->assertSame( 'Test From Label', $att->get_label() );
    }

    public function test_from_label_returns_existing_on_second_call(): void {
        $first = XWC_Attribute_Tax::from_label( 'Duplicate Label' );

        $this->created_attribute_ids[] = $first->get_id();

        $second = XWC_Attribute_Tax::from_label( 'Duplicate Label' );

        $this->assertSame( $first->get_id(), $second->get_id() );
    }

    public function test_get_terms_returns_empty_array_when_no_terms(): void {
        $id  = $this->create_test_attribute( [ 'name' => 'Empty Terms', 'slug' => 'empty-terms' ] );
        $att = xwc_get_attribute_tax( $id );

        $this->assertSame( [], $att->get_terms() );
    }
}

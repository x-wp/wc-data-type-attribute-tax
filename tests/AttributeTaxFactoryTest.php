<?php

declare(strict_types=1);

require_once __DIR__ . '/Support/AttributeTaxTestHelper.php';

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class AttributeTaxFactoryTest extends TestCase {
    use AttributeTaxTestHelper;

    protected function tear_down(): void {
        $this->cleanup_test_attributes();

        parent::tear_down();
    }

    private function get_factory(): XWC_Attribute_Tax_Factory {
        return xwc_get_object_factory( 'attribute_tax' );
    }

    public function test_get_id_with_integer_returns_integer(): void {
        $factory = $this->get_factory();

        $this->assertSame( 5, $factory->get_id( 5 ) );
    }

    public function test_get_id_with_string_resolves_by_name(): void {
        $id      = $this->create_test_attribute( [ 'name' => 'Factory Resolve', 'slug' => 'factory-resolve' ] );
        $factory = $this->get_factory();

        $this->assertSame( $id, $factory->get_id( 'factory-resolve' ) );
    }

    public function test_get_id_with_nonexistent_string_returns_zero(): void {
        $factory = $this->get_factory();

        $this->assertSame( 0, $factory->get_id( 'nonexistent-attribute' ) );
    }

    public function test_get_id_with_false_returns_false(): void {
        $factory = $this->get_factory();

        $this->assertFalse( $factory->get_id( false ) );
    }
}

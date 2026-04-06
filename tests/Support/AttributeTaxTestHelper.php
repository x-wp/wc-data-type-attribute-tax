<?php

declare(strict_types=1);

trait AttributeTaxTestHelper {
    /** @var int[] */
    private array $created_attribute_ids = [];

    protected function create_test_attribute( array $args = [] ): int {
        $defaults = [
            'name'    => 'Test Attribute ' . wp_rand(),
            'slug'    => 'test-attr-' . wp_rand(),
            'type'    => 'select',
            'orderby' => 'menu_order',
        ];

        $id = wc_create_attribute( wp_parse_args( $args, $defaults ) );

        if ( is_wp_error( $id ) ) {
            $this->fail( 'Failed to create test attribute: ' . $id->get_error_message() );
        }

        $this->created_attribute_ids[] = $id;

        return $id;
    }

    protected function cleanup_test_attributes(): void {
        foreach ( $this->created_attribute_ids as $id ) {
            wc_delete_attribute( $id );
        }
        $this->created_attribute_ids = [];
    }
}

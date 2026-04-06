<?php

use XWC\Data\Entity;

/**
 * Attribute Taxonomy Data Store.
 *
 * @template TaxObj of XWC_Attribute_Tax
 */
class XWC_Attribute_Tax_Data_Store extends XWC_Data_Store_XT {
    public function initialize( Entity $e ): static {
        \add_action( 'woocommerce_attribute_added', array( $this, 'register_tax' ), 10, 2 );

        return parent::initialize( $e );
    }

    /**
     * Register the attribute taxonomy on attribute creation.
     *
     * @param  int                  $attribute_id          The attribute id.
     * @param  array<string, mixed> $data The attribute data.
     */
    public function register_tax( $attribute_id, $data ) {
        $taxonomy = \wc_attribute_taxonomy_name( $data['attribute_name'] );

        if ( \taxonomy_exists( $taxonomy ) ) {
            return;
        }

        $args = array(
            array(
                'hierarchical' => true,
                'labels'       => array( 'name' => $data['attribute_label'] ),
                'query_var'    => true,
                'rewrite'      => false,
                'show_ui'      => false,
            ),
        );

        \register_taxonomy(
            $taxonomy,
            // Documented in woocommerce.
            \apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy, array( 'product' ) ),
            // Documented in woocommerce.
            \apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy, $args ),
        );
    }

    /**
     * Delete an attribute.
     *
     * @param  TaxObj $data The data object.
     * @param  array  $args
     * @return bool
     */
    public function delete( &$data, $args = array() ) {
        if ( ! \wc_delete_attribute( $data->get_id() ) ) {
            return false;
        }

        $this->delete_all_meta( $data );

        $data->set_id( 0 );

        return true;
    }

    /**
     * Reformats data for insert and update.
     *
     * Functions `wc_create_attribute` and `wc_update_attribute` expect data in a different format:
     * * `label` => `name`
     * * `name` => `slug`
     *
     * So we remap the keys, and remove the label key.
     *
     * @param  $data Data object.
     * @return array
     */
    protected function reformat_data( array $data ): array {
        $data['name'] = $data['label'] ?? '';
        $data['slug'] = $data['name'];

        unset( $data['label'] );

        return \array_filter(
            $data,
            static fn( $v ) => '' !== $v,
        );
    }

    protected function persist_save( string $callback, array $args ): int|bool {
        $data = $this->reformat_data( $args['data'] );

        if ( 'insert' === $callback ) {
            return $this->format_return( wc_create_attribute( $data ) );
        }

        $id = current( $args['where'] );

        return $this->format_return( wc_update_attribute( $id, $data ) );
    }

    protected function format_return( $mixed ): int|bool {
        if ( \is_wp_error( $mixed ) ) {
            throw new \RuntimeException( $mixed->get_error_message() );
        }

        return $mixed;
    }
}

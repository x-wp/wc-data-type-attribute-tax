<?php

use XWC_Attribute_Tax_Data_Store as Data_Store;

if ( ! function_exists( 'xwc_att_ds' ) ) :
    /**
     * Get the Attribute Taxonomy Data Store.
     *
     * @return Data_Store
     */
    function xwc_att_ds(): Data_Store {
        return xwc_ds( 'attribute_tax', Data_Store::class );
    }

endif;

if ( ! function_exists( 'xwc_get_attribute_tax' ) ) :
    /**
     * Get an Attribute Taxonomy object.
     *
     * @param  mixed          $id  Attribute ID.
     * @param  int|false|null $def Default value.
     * @return XWC_Attribute_Tax|int|false|null
     */
    function xwc_get_attribute_tax( mixed $id, int|false|null $def = false ): XWC_Attribute_Tax|int|false|null {
        return xwc_get_object( $id, 'attribute_tax', $def );
    }

endif;

if ( ! function_exists( 'xwc_get_att_tax' ) ) :
    /**
     * Get an Attribute Taxonomy object.
     *
     * @param  mixed          $id  Attribute ID.
     * @param  int|false|null $def Default value.
     * @return XWC_Attribute_Tax|int|false|null
     */
    function xwc_get_att_tax( mixed $id, int|false|null $def = false ): XWC_Attribute_Tax|int|false|null {
        return xwc_get_attribute_tax( $id, $def );
    }
endif;

if ( ! function_exists( 'xwc_get_attribute_tax_object' ) ) :
    /**
     * Get an Attribute Taxonomy object by ID.
     *
     * @param  int $id Attribute ID.
     * @return XWC_Attribute_Tax
     */
    function xwc_get_attribute_tax_object( int $id ): XWC_Attribute_Tax {
        // @phpstan-ignore return.type
        return xwc_get_object_instance( $id, 'attribute_tax' );
    }
endif;

if ( ! function_exists( 'xwc_get_att_tax_obj' ) ) :
    /**
     * Get an Attribute Taxonomy object by ID.
     *
     * @param  int $id Attribute ID.
     * @return XWC_Attribute_Tax
     */
    function xwc_get_att_tax_obj( int $id ): XWC_Attribute_Tax {
        return xwc_get_attribute_tax_object( $id );
    }
endif;

if ( ! function_exists( 'xwc_get_attribute_tax_id_by_label' ) ) :
    /**
     * Get a product attribute ID by label.
     *
     * @param  ?string $label Attribute label.
     * @return int
     */
    function xwc_get_attribute_tax_id_by_label( ?string $label ): int {
        global $wpdb;

        if ( ! $label ) {
            return 0;
        }

        $table = $wpdb->prefix . 'woocommerce_attribute_taxonomies';
        $id    = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT attribute_id FROM {$table} WHERE attribute_label = %s LIMIT 1",
                $label,
            )
        );

        return absint( $id );
    }
endif;

if ( ! function_exists( 'xwc_get_att_tax_by_label' ) ) :
    /**
     * Get a product attribute ID by label.
     *
     * @param  ?string $label Attribute label.
     * @return int
     */
    function xwc_get_att_tax_by_label( ?string $label ): int {
        return xwc_get_attribute_tax_id_by_label( $label );
    }
endif;

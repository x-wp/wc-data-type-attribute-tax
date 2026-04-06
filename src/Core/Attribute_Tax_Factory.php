<?php

class XWC_Attribute_Tax_Factory extends XWC_Object_Factory {
    /**
     * Get the attribute ID by name or label
     *
     * @param  string $ident Attribute name / slug.
     * @return int
     */
    public function get_attribute_tax_by_string( string $ident ): int {
        $att_id = wc_attribute_taxonomy_id_by_name( $ident );

        return $att_id ? $att_id : xwc_get_attribute_tax_id_by_label( $ident );
    }

    /**
     * Resolve attribute IDs from strings before falling back to base object handling.
     *
     * @param  string|int|WC_Product_Attribute|XWC_Attribute_Tax|false $att_id Attribute ID, Attribute object or Attribute name / slug.
     * @return int|false
     */
    public function get_id( mixed $att_id ): int|bool {
        if ( \is_string( $att_id ) ) {
            return $this->get_attribute_tax_by_string( $att_id );
        }

        return parent::get_id( $att_id );
    }
}

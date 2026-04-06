<?php // phpcs:disable SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys

use XWC\Data\Decorators\Model;

/**
 * Attribute Taxonomy Entity.
 *
 * * Setters
 *
 * @method void set_label( string $label )     Set the label.
 * @method void set_name( string $name )       Set the name.
 * @method void set_orderby( string $orderby ) Set the orderby.
 * @method void set_public( int|bool $public ) Set the attribute public flag
 * @method void set_type( string $type )       Set the attribute selector type
 *
 * * Getters
 *
 * @method string get_label( string $context='view' )   Get the label.
 * @method string get_name( string $context='view' )    Get the name.
 * @method string get_orderby( string $context='view' ) Get the orderby.
 * @method bool   get_public( string $context='view' )  Get the public flag.
 * @method string get_type( string $context='view' )    Get the type.
 */
#[Model(
    name: 'attribute_tax',
    table: '{{PREFIX}}woocommerce_attribute_taxonomies',
    meta_table: '',
    id_field: 'attribute_id',
    data_store: XWC_Attribute_Tax_Data_Store::class,
    factory: XWC_Attribute_Tax_Factory::class,
    core_props: array(
        'label'   => array(
            'name' => 'attribute_label',
            'type' => 'string',
        ),
        'name'    => array(
            'name'    => 'attribute_name',
            'type'    => 'string',
            'default' => 'select',
            'unique'  => true,
        ),
        'type'    => array(
            'name' => 'attribute_type',
            'type' => 'string',
        ),
        'orderby' => array(
            'name'    => 'attribute_orderby',
            'type'    => 'string',
            'default' => 'menu_order',
        ),
        'public'  => array(
            'name'    => 'attribute_public',
            'type'    => 'bool_int',
            'default' => true,
        ),
    ),
)]
class XWC_Attribute_Tax extends XWC_Data {
    protected $object_type = 'attribute_tax';
    protected $cache_group = 'attribute_tax';

    /**
     * Create an attribute taxonomy from a label.
     *
     * @param string $label Label.
     */
    public static function from_label( string $label ): static {
        $att = \xwc_get_attribute_tax( $label );

        if ( ! $att ) {
            $att = \xwc_get_attribute_tax_object( 0 );
            $att->set_label( $label );
            $att->save();
            $att->get_data_store()->read( $att );
        }

        return $att;
    }

    /**
     * We backport the dynamic invoker so the prefixed props work.
     *
     * {@inheritDoc}
     */
    public function __call( string $name, array $args ): mixed {
        $name = \str_replace( 'attribute_', '', $name );

        return parent::__call( $name, $args );
    }

    /**
     * Backport for ID setter.
     *
     * @param  int $id ID.
     */
    public function set_attribute_id( $id ): void {
        $this->set_id( $id );
    }

    /**
     * Backport for ID getter.
     *
     * @return int
     */
    public function get_attribute_id(): int {
        return $this->get_id();
    }

    /**
     * Get the taxonomy name.
     *
     * @return string
     */
    public function get_taxonomy_name() {
        return \wc_attribute_taxonomy_name( $this->get_name() );
    }

    /**
     * Get the terms for this attribute.
     *
     * @param  array $args Query args.
     * @return array<int,WP_Term>
     */
    public function get_terms( array $args = array() ): array {
        $args['taxonomy'] = $this->get_taxonomy_name();

        $terms = \get_terms( $args );

        return ! is_wp_error( $terms ) ? $terms : array();
    }

    /**
     * Get the `WC_Product_Attribute` object.
     *
     * @param  int                $position      Attribute position.
     * @param  array<int, string> $options       Attribute options.
     * @param  bool               $for_variation If is used for variations.
     * @param  bool               $visible       If is visible on Product's additional info tab.
     * @return WC_Product_Attribute
     */
    public function get_wc_attribute(
        int $position,
        array $options = array(),
        bool $for_variation = true,
        bool $visible = true,
    ): WC_Product_Attribute {
        $att = new WC_Product_Attribute();

        $att->set_id( $this->get_id() );
        $att->set_name( $this->get_taxonomy_name() );
        $att->set_position( $position );
        $att->set_visible( $visible );
        $att->set_variation( $for_variation );
        $att->set_options( $options );

        // We use get_terms to get the term ids.
        $terms = \wp_list_pluck( $att->get_terms(), 'term_id' );
        $att->set_options( $terms );

        return $att;
    }
}

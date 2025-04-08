<?php
function show_current_subcategories_shortcode( $atts ) {
    if ( ! is_product_category() ) {
        return 'Updating...';
    }

    $current_cat = get_queried_object();

    if ( ! $current_cat || ! is_a( $current_cat, 'WP_Term' ) ) {
        return '';
    }

    $args = array(
        'taxonomy'     => 'product_cat',
        'parent'       => $current_cat->term_id,
        'hide_empty'   => false,
        'orderby'      => 'name',
        'order'        => 'ASC',
    );

    $subcategories = get_terms( $args );

    if ( empty( $subcategories ) || is_wp_error( $subcategories ) ) {
        return 'Subcategories not found!';
    }

    $output = '<div class="wc-subcats">';
    foreach ( $subcategories as $subcategory ) {
        $thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );
        $image_url = wp_get_attachment_url( $thumbnail_id );
        if ( ! $image_url ) {
            $image_url = wc_placeholder_img_src();
        }
        $term_link = get_term_link( $subcategory );
        ?>
            <div class="subcategory-box">
                <div class="image-box">
                    <img src="<?php echo $image_url ?>" alt="product-image">
                </div>
                <div class="content-box">
                   <h3><a href="<?php $term_link ?>"><?php echo $subcategory->name ?></a></h3>
                   <div><?php echo $subcategory->description ?></div>
                   <a class="elementor-button elementor-button-link elementor-size-sm custom-btn" href="<?php echo $term_link ?>">
                    <span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Order Now</span>
					</span>
                   </a>
                </div>
            </div>
        <?php
        
    }
    $output .= '</div>';

    return $output;
}
add_shortcode( 'current_subcategories', 'show_current_subcategories_shortcode' );

function get_parent_product_category_shortcode() {
    if ( ! is_product_category() ) {
        return '';
    }

    $current_term = get_queried_object();

    if ( $current_term && $current_term->parent ) {
        $parent_term = get_term( $current_term->parent, 'product_cat' );

        if ( $parent_term && ! is_wp_error( $parent_term ) ) {
            return '<a style="color: #7f7f7f" href="' . esc_url( get_term_link( $parent_term ) ) . '">' . esc_html( $parent_term->name ) . ' /</a>';
        }
    }

    return '';
}
add_shortcode( 'parent_product_category', 'get_parent_product_category_shortcode' );
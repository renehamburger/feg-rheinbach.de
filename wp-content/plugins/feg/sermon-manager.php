<?php
/*
This file replaces the default excerpt function of the Sermon Manager plugin

[list_sermons] => displaySermonsList()
[sermon_images] => displayImages()
[latest_series] => displayLatestSeriesImage()
[sermons] => displaySermons()
[sermon_sort_fields] => displaySermonSorting()

*/
//add_action( 'init' , 'feg_replace_wpfc_sermon_excerpt' , 15);
function feg_replace_wpfc_sermon_excerpt() {
	echo('action replaced');
	remove_action( 'sermon_excerpt', 'wpfc_sermon_excerpt' );
	add_action( 'sermon_excerpt', 'feg_wpfc_sermon_excerpt' );
}

function feg_wpfc_sermon_excerpt( $return = false ) {
	global $post;
	echo "our action called";
	ob_start();
	?>
    <div class="wpfc_sermon_wrap cf">
        <div class="wpfc_sermon_image">
			<?php render_sermon_image( apply_filters( 'wpfc_sermon_excerpt_sermon_image_size', 'sermon_small' ) ); ?>
        </div>
		<h1>
			I was here!
		</h1>
        <div class="wpfc_sermon_meta cf">
            <p>
				<?php
				sm_the_date( '', '<span class="sermon_date">', '</span> ' );
				the_terms( $post->ID, 'wpfc_service_type', ' <span class="service_type">(', ' ', ')</span>' );
				?>
            </p>
            <p>
				<?php
				wpfc_sermon_meta( 'bible_passage', '<span class="bible_passage">' . __( 'Bible Text: ', 'sermon-manager-for-wordpress' ), '</span> | ' );
				the_terms( $post->ID, 'wpfc_preacher', '<span class="preacher_name">', ', ', '</span>' );
				?>
            </p>
            <p>
				<?php the_terms( $post->ID, 'wpfc_sermon_series', '<span class="sermon_series">' . __( 'Series: ', 'sermon-manager-for-wordpress' ), ' ', '</span>' ); ?>
            </p>
        </div>
		<?php if ( \SermonManager::getOption( 'archive_player' ) ): ?>
            <div class="wpfc_sermon cf">
				<?php echo wpfc_sermon_media(); ?>
            </div>
		<?php endif; ?>
    </div>
	<?php

	$output = ob_get_clean();

	/**
	 * Allows you to modify the sermon HTML on archive pages
	 *
	 * @param string  $output The HTML that will be outputted
	 * @param WP_Post $post   The sermon
	 *
	 * @since 2.10.1
	 */
	$output = apply_filters( 'wpfc_sermon_excerpt', $output, $post );

	if ( ! $return ) {
		echo $output;
	}

	return $output;
}
?>
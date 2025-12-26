<?php
/**
 * The template for displaying comments
 *
 * @package OyunHaber
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$oyunhaber_comment_count = get_comments_number();
			if ( '1' === $oyunhaber_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( '1 Yorum', 'oyunhaber' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s Yorum', '%1$s Yorum', $oyunhaber_comment_count, 'comments title', 'oyunhaber' ) ),
					number_format_i18n( $oyunhaber_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 50,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'oyunhaber' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Önceki Yorumlar', 'oyunhaber' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Sonraki Yorumlar', 'oyunhaber' ) ); ?></div>
				</div><!-- .nav-links -->
			</nav><!-- .comment-navigation -->
			<?php
		endif;
	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Yorumlar kapalı.', 'oyunhaber' ); ?></p>
		<?php
	endif;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(
		'author' =>
			'<div class="comment-form-row"><p class="comment-form-author"><input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Adınız', 'oyunhaber' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30"' . $aria_req . ' /></p>',

		'email' =>
			'<p class="comment-form-email"><input id="email" name="email" type="email" placeholder="' . esc_attr__( 'E-posta', 'oyunhaber' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" size="30"' . $aria_req . ' /></p></div>',

		'url' =>
			'<p class="comment-form-url"><input id="url" name="url" type="url" placeholder="' . esc_attr__( 'Web Sitesi (Opsiyonel)', 'oyunhaber' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" /></p>',
	);

	comment_form( array(
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>',
		'title_reply'        => __( 'Düşüncelerini Paylaş', 'oyunhaber' ),
		'fields'             => $fields,
		'comment_field'      => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="5" placeholder="' . esc_attr__( 'Yorumunuzu buraya yazın...', 'oyunhaber' ) . '" aria-required="true"></textarea></p>',
		'class_submit'       => 'submit btn-comment-submit',
		'label_submit'       => __( 'Yorumu Gönder', 'oyunhaber' ),
	) );
	?>

</div><!-- #comments -->

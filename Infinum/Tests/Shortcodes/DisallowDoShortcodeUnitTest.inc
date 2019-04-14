<?php
// Taken from https://developer.wordpress.org/reference/functions/do_shortcode/

echo do_shortcode( '' ); // Warning.
echo do_shortcode( '[iscorrect]' . $text_to_be_wrapped_in_shortcode . '[/iscorrect]' ); // Warning.
add_filter( 'widget_text', 'do_shortcode' ); // Warning.
echo do_shortcode( '[contact-form-7 id="91" title="quote"]' ); // Warning.
echo do_shortcode('[contact-form-7 id="91" title="quote"]'); // Warning.
$var = do_shortcode( '' ); // Warning.
echo $var;

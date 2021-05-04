<?php
// Taken from https://developer.wordpress.org/reference/functions/do_shortcode/

echo do_shortcode(''); // Warning.
echo do_shortcode('[iscorrect]' . $text_to_be_wrapped_in_shortcode . '[/iscorrect]'); // Warning.
add_filter('widget_text', 'do_shortcode'); // Warning.
echo do_shortcode('[contact-form-7 id="91" title="quote"]'); // Warning.
echo do_shortcode('[contact-form-7 id="91" title="quote"]'); // Warning.
$var = do_shortcode(''); // Warning.
echo $var;

echo something_do_shortcode(''); // Ok.
echo something_do_shortcodes_from_plugin(''); // Ok.
echo do_shortcode_fromOtherPlugin(''); // Ok.

echo
do_shortcode(''); // Warning.

add_filter(
	'widget_text',
	function () {
		return do_shortcode('[text content="Cool!"]'); // Warning.
	}
);

add_action('loop_start', 'shortcode_before_entry');

function shortcode_before_entry()
{
	if (!is_singular('post')) {
		return;
	}

	echo do_shortcode('[your_shortcode_handle]'); // Warning.
}

add_filter(
	'get_comment_text',
	function ($comment) {
		$finalComment = '';
		$allowed = ['snippet', 'quote'];
		$parts = preg_split('/(\[\/?\w+\])/', $comment, null, PREG_SPLIT_DELIM_CAPTURE);

		for ($i = 0; $i < sizeof($parts); $i++) {
			if (preg_match('/\[\w+\]/', $parts[$i])) {
				$shortcodeName = substr($parts[$i], 1, -1);
				if (in_array($shortcodeName, $allowed)) {
					$finalComment .= do_shortcode($parts[$i] . $parts[$i + 1] . $parts[$i + 2]); // Warning.
					echo '++' . $parts[$i] . $parts[$i + 1] . $parts[$i + 2] . '++' . $i;
					$i += 2;
				} else {
					$finalComment .= $parts[$i];
				}
			} else {
				$finalComment .= $parts[$i];
			}
		}
		return $finalComment;
	}
);

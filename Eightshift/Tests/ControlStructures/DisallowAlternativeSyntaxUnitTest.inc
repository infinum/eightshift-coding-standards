<?php

$items = [1, 2, 3];
$condition = true;
$i = 0;
$x = 1;

// Bad: alternative syntax.
foreach ($items as $item) : // Ok - opening is fine, error is on closing keyword.
	echo $item;
endforeach; // Error.

if ($condition) : // Ok.
	echo 'yes';
endif; // Error.

while ($i < 3) : // Ok.
	$i++;
endwhile; // Error.

for ($i = 0; $i < 3; $i++) : // Ok.
	echo $i;
endfor; // Error.

switch ($x) : // Ok.
	case 1:
		echo 'one';
		break;
endswitch; // Error.

// Good: curly brace syntax.
foreach ($items as $item) { // Ok.
	echo $item;
}

if ($condition) { // Ok.
	echo 'yes';
}

while ($i < 3) { // Ok.
	$i++;
}

for ($i = 0; $i < 3; $i++) { // Ok.
	echo $i;
}

switch ($x) { // Ok.
	case 1:
		echo 'one';
		break;
}

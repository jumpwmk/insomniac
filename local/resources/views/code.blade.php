<?php
use App\Config;
use App\Submit;
if($submit_id == 0) exit(0);

$submit = Submit::find($submit_id);
$code = '';

if($submit->user_id == Auth::user()->id or Auth::isAdmin())
{
	$file = fopen("judge/codes/".$submit_id.".cpp", "r");
	while(!feof($file)) {
		$code .= str_replace('    ', '	', fgets($file));
	}
	fclose($file);
}
?>

<link class="codestyle" rel="stylesheet" href="{{Config::root()}}/frontend/css/styles/{{Auth::user()->codestyle}}.css">
<script src="{{Config::root()}}/frontend/js/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<pre style="font-size:14px"><code class="cpp">{{$code}}</code></pre>

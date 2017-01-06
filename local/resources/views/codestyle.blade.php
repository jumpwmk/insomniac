<?php 
use App\Config;
?>

<link class="codestyle" rel="stylesheet" href="{{Config::root()}}/frontend/css/styles/{{$style}}.css">
<script src="{{Config::root()}}/frontend/js/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<pre style="font-size:14px"><code class="cpp">#include &lt;stdio.h>

int main()
{
	printf("Hello World!");
	return 0;
}</code></pre>

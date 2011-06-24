<?php
include('./functions.php');

$tpl = <<<EOT
<?xml version="1.0"?>
<rss version="2.0">
	<channel>
		<title>{TITLE}</title>
		<description>{DESCRIPTION}</description>
		<link>{URI}</link>
		<generator>News System (winged.info)</generator>
		<!-- BEGIN -->
		<item>
			<title>{SUBJECT}</title>
			<link>{URI}?post={ID}</link>
			<description>{MESSAGE}</description>
			<pubDate>{DATE}</pubDate>
			<guid>{URI}?post={ID}</guid>
		</item>
		<!-- END -->
	</channel>
</rss>
EOT;

$news = new WingedNews();
$info = array (
			'uri' => $news->configs['uri'],
			'title' => $news->configs['title'],
			'description' => $news->configs['description']
		);


$posts = $news->getPostList(10);
for ($i=0, $n=count($posts); $i<$n; $i++) {
	$posts[$i]['MESSAGE'] = preg_replace('/(.*?)(\.(\n+|\r+))(.|\s|\n|\r)*/', '$1$2', $news->parseBB($posts[$i]['MESSAGE']));
	$posts[$i]['MESSAGE'] = htmlspecialchars(html_entity_decode($posts[$i]['MESSAGE'], ENT_QUOTES), ENT_QUOTES);
	$posts[$i]['DATE'] = gmdate('D, d M Y H:i:s T', $posts[$i]['ID']);
	unset($posts[$i]['CATS']);
}

header('Content-type: text/xml');
print $news->parseRecTemplate($news->parseTemplate($tpl, $info), '<!-- BEGIN -->', '<!-- END -->', $posts);
?>
<?php
class WingedNews {
	var $path;
	var $configs;
	var $postpath;
	var $commentpath;
	var $catpath;
	var $catfile;
	var $configfile;
	var $lockfile;
	
	function WingedNews() {
		//$this->path = str_replace('\\', '/', dirname(__FILE__));
		//$this->path = "../../data/modules/Blog";
		$this->path = "data/modules/Blog";
		$this->configfile = $this->path . '/data/config.txt';
		$this->postpath = $this->path . '/data/posts/';
		$this->commentpath = $this->path . '/data/comments/';
		$this->catpath = $this->path . '/data/categories/';
		$this->catfile = $this->path . '/data/categories.txt';
		$this->lockfile = $this->path . '/data/post_locked.txt';
		$this->configs = $this->getConfigs();
		$this->setLocale();
	}

	function getConfigs() {
		$configs = array(
					'tpl' => 'default',
					'display' => 'basic',
					'flood' => 120,
					'comment' => 1,
					'filter' => '',
					'list' => '',
					'zone' => 0,
					'date' => '%d %B %Y',
					'time' => '%H:%M',
					'locale' => 'en_GB gbr en_GB.ISO8859-1 en_GB.ISO8859-15 en_GB.ISO_8859-1 en_GB.ISO_8859-15',
					'daylight' => 1,
					'alias' => 0,
					'uri' => 'index.php?system=Blog',
					'title' => 'Blog',
					'description' => 'Blog at LotusCMS'
					);
		return array_merge($configs, parse_ini_file($this->configfile));
	}
	
	function setLocale() {
		setlocale(LC_TIME, explode(' ', $this->configs['locale']));
	}

	function getDateArray($time=0) {
		if ($time==0) {
			$time = $this->configs['daylight']? time()+3600 : time();
		}
		return getdate($time + $this->configs['zone']*3600-intval(date('Z')));
	}

	function getZoneDate($format, $time) {
		return gmstrftime($format, $time + $this->configs['zone']*3600);
	}
	
	function getLockedPosts() {
		$posts = array();
		$lines = file($this->lockfile);
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<post>') {
				$posts[] = trim($lines[$i+1]);
				$i+=2;
			}
		}
		return $posts;
	}
	
	function formatAlias($text) {
		$vars = array('!', '?', '.', '/', ' ', ',', ':');
		while (in_array(substr($text, -1, 1), $vars)) {
			$text = substr($text, 0, -1);
		}
		return strtolower(str_replace(array(' ', '&', '/', ',', ':', '"' , "'", '\\'), array('-', '-', '-', '-', '-'), html_entity_decode($text, ENT_QUOTES)));
	}
	
	function getPostList($max=0, $categories=array(), $all=false, $year=0, $month=0, $day=0) {
		$details = array();
		$files = array();
		if ($year==0 && $month==0) {
			$handle = opendir($this->postpath);
			while (($file=readdir($handle))!==false) {
				if ($file[0]!='.') $files[] = $file;
			}
			closedir($handle);
			natsort($files);
			$files = array_reverse($files);
		} else if ($year!=0 && $month==0) {
			$handle = opendir($this->postpath);
			while (($file=readdir($handle))!==false) {
				if (array_shift(explode('-', $file))==$year) $files[] = $file;
			}
			closedir($handle);
			natsort($files);
			$files = array_reverse($files);
		} else if ($year!=0 && $month!=0 && is_file($this->postpath . $year . '-' . $month . '.txt')) {
			$files = array($year . '-' . $month . '.txt');
		}
		$count = 0;
		$sorts = array();
		$current = $this->configs['daylight']? time()+3600 : time();
		$locked_posts = $this->getLockedPosts();
		for ($i=0, $n=count($files); $i<$n && ($count<$max || $max==0); $i++) {
			$lines = file($this->postpath . $files[$i]);
			for ($j=0, $m=count($lines); $j<$m; $j++) {
				if (trim($lines[$j])=='<post>') {
					$id = trim($lines[$j+1]);
					$dates = $this->getDateArray($id);
					if (!$all && ($id>$current || ($day!=0 && $dates['mday']!=$day))) {
						$j+=4;
						continue;
					}
					$allcats = $this->getPostCats($id);
					$cats = array();
					$catids = array();
					for ($k=0, $l=count($allcats); $k<$l; $k++) {
						if ($allcats[$k]['CHECK']) {
							$cats[] = $allcats[$k];
							$catids[] = $allcats[$k]['CAT_ID'];
						}
					}
					if (!empty($categories)) {
						if (in_array(0, $categories) && count($cats)>1) {
							$j+=4;
							continue;
						} else if (count(array_intersect($categories, $catids))<1) {
							$j+=4;
							continue;
						}
					}
					$details[$count]['CATS'] = $cats;
					$details[$count]['ID'] = $sorts[] = $id;
					$details[$count]['SUBJECT'] = htmlspecialchars(stripslashes(trim($lines[$j+2])), ENT_QUOTES);
					$details[$count]['DATE'] = $this->getZoneDate($this->configs['date'], $id);
					$details[$count]['TIME'] = $this->getZoneDate($this->configs['time'], $id);
					$details[$count]['LOCKED'] = in_array($id, $locked_posts)? 'locked' : '';
					$message = '';
					for ($j+=3;; $j++) {
						if (trim($lines[$j])=='</post>') break;
						$message .= $lines[$j];
					}
					$details[$count]['MESSAGE'] = htmlspecialchars(stripslashes($message), ENT_QUOTES);
					
					$comments = $this->getPostComments($id);
					$details[$count]['NUM_COMMENT'] = count($comments);
					$details[$count]['LAST_COMMENT'] = '-';
					$details[$count]['C_ID'] = 0;
					$c_key = $details[$count]['NUM_COMMENT'] - 1;
					if ($c_key>-1) {
						$details[$count]['C_ID'] = $comments[$c_key]['C_ID'];
						$details[$count]['LAST_COMMENT'] = $comments[$c_key]['DATE'] . ', ' . $comments[$c_key]['TIME'];
					}
					$count++;
				}
			}
		}
		array_multisort($sorts, SORT_DESC, $details);
		if ($count>$max && $max>0) array_splice($details, $max);
		return $details;
	}

	function getPostDetails($id) {
		$details = array();
		$dates = $this->getDateArray($id);
		$file = $this->postpath . $dates['year'] . '-' . $dates['mon'] . '.txt';
		if (!is_file($file)) return false;
		$lines = file($file);
		for ($j=0, $m=count($lines); $j<$m; $j++) {
			if (trim($lines[$j])=='<post>') {
				if (trim($lines[$j+1])==$id) {
					$details['ID'] = $id;
					$details['LOCKED'] = in_array($id, $this->getLockedPosts())? 'checked' : '';
					$details['SUBJECT'] = htmlspecialchars(stripslashes(trim($lines[$j+2])), ENT_QUOTES);
					$details['DATE'] = $this->getZoneDate($this->configs['date'], $id);
					$details['TIME'] = $this->getZoneDate($this->configs['time'], $id);
					$message = '';
					for ($j+=3;; $j++) {
						if (trim($lines[$j])=='</post>') break;
						$message .= $lines[$j];
					}
					$details['MESSAGE'] = htmlspecialchars(stripslashes($message), ENT_QUOTES);
					$details['CATS'] = $this->getPostCats($id);
					return $details;
				} else $j+=4;
			}
		}
		return false;
	}

	function getPostComments($id, $formatted=false) {
		$details = array();
		$file = $this->commentpath . $id . '.txt';
		if (!is_file($file)) return $details;
		$lines = file($file);
		$count = 0;
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<comment>') {
				$details[$count]['C_ID'] = trim($lines[$i+1]);
				$details[$count]['NAME'] = htmlspecialchars(stripslashes(trim($lines[$i+2])), ENT_QUOTES);
				$details[$count]['WEBSITE'] = htmlspecialchars(trim($lines[$i+3]), ENT_QUOTES);
				$details[$count]['DATE'] = $this->getZoneDate($this->configs['date'], $details[$count]['C_ID']);
				$details[$count]['TIME'] = $this->getZoneDate($this->configs['time'], $details[$count]['C_ID']);
				$message = '';
				for ($i+=4;; $i++) {
					if (trim($lines[$i])=='</comment>') break;
					$message .= $lines[$i];
				}
				$details[$count]['MESSAGE'] = $formatted? $this->formatComment(htmlspecialchars(stripslashes($message), ENT_QUOTES)) : '';
				$count++;
			}
		}
		return $details;
	}

	function getPostCats($id) {
		$details = $this->getCats();
		$file = $this->catpath . $id . '.txt';
		if (!is_file($file)) return $details;
		$lines = file($file);
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<cat>') {
				$catid = trim($lines[++$i]);
				for ($j=0, $m=count($details); $j<$m; $j++) {
					if ($catid==$details[$j]['CAT_ID']) {
						$details[$j]['CHECK'] = 'checked';
						break;
					}
				}
			}
		}
		return $details;
	}

	function getCats($getpost=false) {
		$cats = array();
		$lines = file($this->catfile);
		$count = 0;
		$sorts = array();
		$posts = $getpost? $this->getCatsPosts() : array();
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<cat>') {
				$id = trim($lines[$i+1]);
				$cats[$count]['CAT_ID'] = $id;
				$cats[$count]['CAT_NAME'] = htmlspecialchars(stripslashes(trim($lines[$i+2])), ENT_QUOTES);
				$cats[$count]['CAT_TYPE'] = 'checkbox';
				$cats[$count]['CHECK'] = '';
				$cats[$count]['POSTS'] = array_key_exists($id, $posts)? $posts[$id] : array();
				$cats[$count]['NUM_POST'] = count($cats[$count]['POSTS']);
				$sorts[] = $cats[$count]['CAT_NAME'];
				$count++;
				$i+=3;
			}
		}
		array_multisort($sorts, SORT_REGULAR, $cats);
		$cats[] = array(
					'CAT_ID' => 0,
					'CAT_NAME' => 'Uncategorized',
					'CAT_TYPE' => 'hidden',
					'CHECK' => 'disabled',
					'POSTS' => array(),
					'NUM_POST' => 0
					);
		return $cats;
	}

	function getCatsPosts($cats=null) {
		$count = count($cats);
		$details = array();
		$handle = opendir($this->catpath);
		while (($file=readdir($handle))!==false) {
			if ($file[0]=='.') continue;
			$lines = file($this->catpath . $file);
			for ($i=0, $n=count($lines); $i<$n; $i++) {
				if (trim($lines[$i])=='<cat>') {
					$index = $cats==null? trim($lines[$i+1]) : array_search(trim($lines[$i+1]), $cats);
					if ($index!==false) {
						$details[$index][] = basename($file, '.txt');
					}
					$i+=2;
				}
			}
		}
		closedir($handle);
		return $details;
	}
	
	function getOutputPost($posts, $tpl_post, $tpl_comment, $tpl_form=null, $dir=null, $url=null) {
		$output = '';
		$posts['MESSAGE'] = $this->str2para($this->parseBB($posts['MESSAGE']));
		$posts['CATEGORY'] = '';
		for ($i=0, $n=count($posts['CATS'])-1; $i<$n; $i++) {
			if ($posts['CATS'][$i]['CHECK']) {
				if ($this->configs['alias'] && $dir!=null) {
					$posts['CATEGORY'] .= '<a href="' . $dir . 'category/' .
											$this->formatAlias($posts['CATS'][$i]['CAT_NAME']) . '">' .
											$posts['CATS'][$i]['CAT_NAME'] . '</a>, ';
				} else {
					$posts['CATEGORY'] .= '<a href="' . $url . 'category=' .
											$posts['CATS'][$i]['CAT_ID'] . '">' .
											$posts['CATS'][$i]['CAT_NAME'] . '</a>, ';
				}
			}
		}
		if (empty($posts['CATEGORY'])) {
			$uncats = array_pop($posts['CATS']);
			if ($this->configs['alias'] && $dir!=null) {
				$posts['CATEGORY'] = '<a href="' . $dir . 'category/' .
										$this->formatAlias($uncats['CAT_NAME']) . '">' .
										$uncats['CAT_NAME'] . '</a>';
			} else {
				$posts['CATEGORY'] = '<a href="' . $url . 'category=0">' . $uncats['CAT_NAME'] . '</a>';
			}
		} else {
			$posts['CATEGORY'] = substr($posts['CATEGORY'], 0, -2);
		}
		unset($posts['CATS']);
		$output = $this->parseTemplate(file_get_contents($tpl_post), $posts);
		$comments = $this->getPostComments($posts['ID'], true);
		if (!empty($comments)) {
			$output .= $this->parseRecTemplate($this->parseTemplate(file_get_contents($tpl_comment), array('ID'=>$posts['ID'])), '<!-- BEGIN -->', '<!-- END -->', $comments);
		}
		if ($tpl_form!=null && $this->commentsAllowed($posts['ID'])) {
			$output .= $this->parseTemplate(file_get_contents($tpl_form), array('ID'=>$posts['ID']));
		}
		return $output;
	}

	function parseBB($content, $compact=false) {
		$codes['bb'][] = '@\[b\](.*?)\[/b\]@i';
		$codes['bb'][] = '@\[i\](.*?)\[/i\]@i';
		$codes['bb'][] = '@\[u\](.*?)\[/u\]@i';
		$codes['bb'][] = '@\[url=\s*(.*?)\](.*?)\[/url\]@i';
		$codes['bb'][] = '@\[url\]((ht|f)tps?://.*?)\[/url\]@i';
		$codes['bb'][] = '@\[img\](.*?)\[/img\]@i';
		$codes['bb'][] = '@\[size=\s*(\d+)\](.*?)\[/size\]@i';
		$codes['bb'][] = '@\[colou?r=\s*(.*?)\](.*?)\[/colou?r\]@i';
		$codes['bb'][] = '@\[acronym=\s*(.*?)\](.*?)\[/acronym\]@i';
		$codes['bb'][] = '@\[embed\](.*?)\[/embed\]@is';
		$codes['bb'][] = '@\[quote\](.*?)\[/quote\]@is';
		$codes['bb'][] = '@\[del\](.*?)\[/del\]@i';
		$codes['bb'][] = '@\[more\](.*?)\[/more\]@ise';
		$codes['bb'][] = '@\[more=\s*(.*?)\](.*?)\[/more\]@ise';
		$codes['bb'][] = '@\[cent(er|re)\](.*?)\[/cent(er|re)\]@is';
		$codes['bb'][] = '@\[code\](.*?)\[/code\]@is';
		$codes['bb'][] = '@\[list(=\s*(1|a))?\](.*?)\[/list\]@ise';
		$codes['bb'][] = '@\[img=\s*(left|right)\](.*?)\[/img\]@i';
		$codes['bb'][] = '@\[raw\](.*?)\[/raw\]@ise';
		$codes['bb'][] = '@\[sub\](.*?)\[/sub\]@i';
		$codes['bb'][] = '@\[sup\](.*?)\[/sup\]@i';
		
		$codes['html'][] = '<b>$1</b>';
		$codes['html'][] = '<i>$1</i>';
		$codes['html'][] = '<span style="text-decoration:underline">$1</span>';
		$codes['html'][] = '<a href="$1">$2</a>';
		$codes['html'][] = '<a href="$1">$1</a>';
		$codes['html'][] = '<img alt="$1" src="$1" />';
		$codes['html'][] = '<span style="font-size:$1px">$2</span>';
		$codes['html'][] = '<span style="color:$1">$2</span>';
		$codes['html'][] = '<acronym title="$1">$2</acronym>';
		$codes['html'][] = '<object type="application/x-shockwave-flash" width="350" height="285" data="$1"><param name="movie" value="$1" /><!--[if lt ie 7]<embed src="$1" /><![endif]--></object>';
		$codes['html'][] = "<blockquote>$1\n\n</blockquote>";
		$codes['html'][] = '<del>$1</del>';
		$codes['html'][] = $compact? "'( <a href=\"{LINK}#more' . crc32('$1') . '\">Read more...</a> )'" : "'<a name=\"more' . crc32('$1') . '\"></a>' . \"$1\"";
		$codes['html'][] = $compact? "'( <a href=\"{LINK}#more' . crc32('$2') . '\">$1</a> )'" : "'<a name=\"more' . crc32('$2') . '\"></a>' . \"$2\"";
		$codes['html'][] = '<div style="text-align:center">$2</div>';
		$codes['html'][] = '<pre>$1</pre>';
		$codes['html'][] = '$this->parseBBList("$3", "$2")';
		$codes['html'][] = '<img alt="$2" src="$2" style="float:$1" />';
		$codes['html'][] = "'<div>' . html_entity_decode('$1') . '</div>';";
		$codes['html'][] = '<sub>$1</sub>';
		$codes['html'][] = '<sup>$1</sup>';
		
		return preg_replace($codes['bb'], $codes['html'], $content);
	}

	function formatComment($text) {
		$codes['bb'][] = '@\[b\](.*?)\[/b\]@i';
		$codes['bb'][] = '@\[i\](.*?)\[/i\]@i';
		$codes['bb'][] = '@\[u\](.*?)\[/u\]@i';
		$codes['bb'][] = '@\[url=\s*(.*?)\](.*?)\[/url\]@i';
		$codes['bb'][] = '@\[url\]((ht|f)tps?://.*?)\[/url\]@i';
		$codes['bb'][] = '@\[quote\](\r|\n)*?(.*?)(\r|\n)*?\[/quote\]@i';
		$codes['bb'][] = '/\n/';
		
		$codes['html'][] = '<b>$1</b>';
		$codes['html'][] = '<i>$1</i>';
		$codes['html'][] = '<span style="text-decoration:underline">$1</span>';
		$codes['html'][] = '<a href="$1">$2</a>';
		$codes['html'][] = '<a href="$1">$1</a>';
		$codes['html'][] = '<blockquote><div class="quote">$2</div></blockquote>';
		$codes['html'][] = '<br />';
		
		return preg_replace($codes['bb'], $codes['html'], $text);
	}
	
	function parseBBList($text, $type) {
		$items = explode('[*]', $text);
		$output = $type=='1'? '<ul style="list-style-type:decimal">' : ($type=='a'? '<ul style="list-style-type:lower-alpha">' : '<ul>');
		for ($i=1, $n=count($items); $i<$n; $i++) {
			$output .= '<li>' . $items[$i] . '</li>';
		}
		return $output . '</ul>';
	}

	function str2para($text) {
		$pattern = '@(.*?)((\n|\r){2,}|<ul.*?/ul>|<div.*?/div>|<pre.*?/pre>|<blockquote>|</blockquote>)@isS';
		$replace = '<p>$1</p>$2';
		
		return str_replace('<p></p>', '', preg_replace($pattern, $replace, $text . "\n\n"));
	}

	function parseTemplate($tpl, $keys) {
		$vars = array();
		foreach($keys as $key => $val) {
			$vars[] = '{' . strtoupper($key) . '}';
		}
		return str_replace($vars, $keys, $tpl);
	}

	function parseRecTemplate($tpl, $begin, $end, $keys) {
		$content = '';
		$begin_pos = strpos($tpl, $begin) + strlen($begin);
		$end_pos = strpos($tpl, $end);
		$length = $end_pos - $begin_pos;
		$subtpl = substr($tpl, $begin_pos, $length);
		$vars = array();
		if (!empty($keys)) {
			foreach($keys[0] as $key => $val) {
				$vars[] = '{' . strtoupper($key) . '}';
			}
		}
		for ($i=0, $n=count($keys); $i<$n; $i++) {
			$content .= str_replace($vars, $keys[$i], $subtpl);
		}
		return substr_replace($tpl, $content, $begin_pos, $length);
	}
}
?>

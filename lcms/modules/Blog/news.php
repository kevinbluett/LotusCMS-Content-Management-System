<?php
include(str_replace('\\', '/', dirname(__FILE__)) . '/functions.php');

class NewsDisplay extends WingedNews {
	var $tplpath;
	var $notes;
	var $message;
	var $logfile;
	var $dir;
	var $url;
	var $phpURL;
	
	function NewsDisplay() {
		$this->WingedNews();
		//$this->tplpath = $this->path . '/display/' . $this->configs['display'] . '/';
		$this->tplpath = 'modules/Blog/display/' . $this->configs['display'] . '/';
		$this->logfile = $this->path . '/data/logs.txt';
		$this->notes = parse_ini_file($this->tplpath . 'note.tpl', true);
		$this->message = '';
		$dir = str_replace('\\', '/', dirname($this->url));
		$this->dir = $dir=='/'? '/' : $dir . '/';
		$this->url = "index.php?system=Blog&";
	}
	
	function commentsAllowed($postid) {
		$posts = $this->getPostDetails($postid);
		if ($posts===false) return false;
		return (empty($posts['LOCKED']) && $this->configs['comment']);
	}
	
	function getNews($max, $cats) {
		$posts = $this->getPostList($max, $cats);
		return $this->getOutputPostList($posts);
	}
	
	function getArchives() {
		$details = array();
		$sorts_y = array();
		$sorts_m = array();
		$handle = opendir($this->postpath);
		while (($file=readdir($handle))!==false) {
			if ($file[0]=='.') continue;
			$name = basename($file, '.txt');
			list($year, $month) = explode('-', $name);
			$posts = $this->getPostList(0, array(), false, $year, $month);
			if (empty($posts)) continue;
			$details[] = array (
							'YEAR' => $year,
							'MONTH' => $this->notes['month'][$month],
							'LINK' => $this->configs['alias']? $this->dir . $year . '/' . $month . '/' : $this->url . 'archive=' . $name
							);
			$sorts_y[] = $year;
			$sorts_m[] = $month;
		}
		closedir($handle);
		array_multisort($sorts_y, SORT_DESC, $sorts_m, SORT_DESC, $details);
		return $this->parseRecTemplate(file_get_contents($this->tplpath . 'archives.tpl'), '<!-- BEGIN -->', '<!-- END -->', $details);
	}

	function getCategories() {
		$cats = $this->getCats(true);
		$posts = array();
		$num_cat = count($cats);
		for ($i=0; $i<$num_cat; $i++) {
			$cats[$i]['LINK'] = $this->url . 'category=' . $cats[$i]['CAT_ID'];
			if ($this->configs['alias']) {
				$cats[$i]['LINK'] = $this->dir . 'category/' . $this->formatAlias($cats[$i]['CAT_NAME']);
			}
			$posts = array_merge($posts, $cats[$i]['POSTS']);
			unset($cats[$i]['POSTS']);
		}
		$diff = count($this->getPostList()) - count(array_unique($posts));
		if ($diff) {
			$cats[$num_cat-1]['NUM_POST'] = $diff;
		} else {
			unset($cats[$num_cat-1]);
		}
		return $this->parseRecTemplate(file_get_contents($this->tplpath . 'categories.tpl'), '<!-- BEGIN -->', '<!-- END -->', $cats);
	}
			
	function addComment($post, $name, $website, $message) {
		if (get_magic_quotes_gpc()==0) {
			$name = addslashes($name);
			$message = addslashes($message);
		}
		$id = $this->configs['daylight']? time()+3600 : time();
		$content = "\n<comment>\n" . $id . "\n" . $name . "\n" . $website . "\n" . $message . "\n</comment>";
		$handle = fopen($this->commentpath . $post . '.txt', 'a') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
	}

	function checkFlood($ip) {
		$current = time();
		$ips = parse_ini_file($this->logfile);
		if (array_key_exists($ip, $ips) && $current-$ips[$ip] < $this->configs['flood']) return false;
		$mode = filesize($this->logfile)<10240? 'a' : 'w';
		$handle = fopen($this->logfile, $mode) or exit('error1');
		$content = $ip . " = " . $current . "\n";
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
		return true;
	}

	function checkBlackList($ip) {
		$ips = explode(',', $this->configs['list']);
		for ($i=0, $n=count($ips); $i<$n; $i++) {
			$text = trim($ips[$i]);
			if (!empty($text)) {
				$pattern = '/' . str_replace('.', '\.', $text) . '/';
				if (preg_match($pattern, $ip)>0) return false;
			}
		}
		return true;
	}

	function wordFilter($message) {
		$patterns = array();
		$words = explode(',', $this->configs['filter']);
		for ($i=0, $n=count($words); $i<$n; $i++) {
			$text = trim($words[$i]);
			if (!empty($text)) $patterns[] = '/' . $text . '/i';
		}
		return preg_replace($patterns, '****', $message);
	}
	
	function getOutputPostList($posts) {
		for ($i=0, $n=count($posts); $i<$n; $i++) {
			$posts[$i]['LINK'] = $this->url . 'post=' . $posts[$i]['ID'];
			if ($this->configs['alias']) {
				$dates = $this->getDateArray($posts[$i]['ID']);
				$posts[$i]['LINK'] = $this->dir . $dates['year'] . '/' . $dates['mon'] . '/' . $dates['mday'] . '/' .
									$this->formatAlias($posts[$i]['SUBJECT']) . '-' . $dates['seconds'];
			}
			$posts[$i]['MESSAGE'] = $this->str2para($this->parseBB($posts[$i]['MESSAGE'], true));
			$posts[$i]['CATEGORY'] = '';
			for ($j=0, $m=count($posts[$i]['CATS'])-1; $j<$m; $j++) {
				if ($posts[$i]['CATS'][$j]['CHECK']) {
					if ($this->configs['alias']) {
						$posts[$i]['CATEGORY'] .= '<a href="' . $this->dir . 'category/' .
												$this->formatAlias($posts[$i]['CATS'][$j]['CAT_NAME']) . '">' .
												$posts[$i]['CATS'][$j]['CAT_NAME'] . '</a>, ';
					} else {
						$posts[$i]['CATEGORY'] .= '<a href="' . $this->url . 'category=' .
												$posts[$i]['CATS'][$j]['CAT_ID'] . '">' .
												$posts[$i]['CATS'][$j]['CAT_NAME'] . '</a>, ';
					}
				}
			}
			if (empty($posts[$i]['CATEGORY'])) {
				$uncats = array_pop($posts[$i]['CATS']);
				if ($this->configs['alias']) {
					$posts[$i]['CATEGORY'] = '<a href="' . $this->dir . 'category/' .
												$this->formatAlias($uncats['CAT_NAME']) . '">' .
												$uncats['CAT_NAME'] . '</a>';
				} else {
					$posts[$i]['CATEGORY'] = '<a href="' . $this->url . 'category=0">' .
												$uncats['CAT_NAME'] . '</a>';
				}
			} else {
				$posts[$i]['CATEGORY'] = substr($posts[$i]['CATEGORY'], 0, -2);
			}
			unset($posts[$i]['CATS']);
		}
		return $this->parseRecTemplate(file_get_contents($this->tplpath . 'general.tpl'), '<!-- BEGIN -->', '<!-- END -->', $posts);
	}
	
	function getPostByAlias($year, $month, $day, $alias, $sec) {
		$details = array();
		$file = $this->postpath . $year . '-' . $month . '.txt';
		if (!is_file($file)) return false;
		$lines = file($file);
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<post>') {
				$id = trim($lines[$i+1]);
				$dates = $this->getDateArray($id);
				$subject = trim($lines[$i+2]);
				if ($dates['mday']==$day && $dates['seconds']==$sec && $this->formatAlias($subject)==$alias) {
					$details['ID'] = $id;
					$details['LOCKED'] = in_array($id, $this->getLockedPosts())? 'checked' : '';
					$details['SUBJECT'] = htmlspecialchars(stripslashes($subject), ENT_QUOTES);
					$details['DATE'] = $this->getZoneDate($this->configs['date'], $id);
					$details['TIME'] = $this->getZoneDate($this->configs['time'], $id);
					$message = '';
					for ($i+=3;; $i++) {
						if (trim($lines[$i])=='</post>') break;
						$message .= $lines[$i];
					}
					$details['MESSAGE'] = htmlspecialchars(stripslashes($message), ENT_QUOTES);
					$details['CATS'] = $this->getPostCats($id);
					return $details;
				} else $i+=4;
			}
		}
		return false;
	}
	
	function setMessageById($id) {
		$posts = $this->getPostDetails($_GET['post']);
		if ($posts!==false) {
			$posts['LINK'] = $this->url . 'post=' . $_GET['post'];
			$this->message .= $this->getOutputPost($posts, $this->tplpath . 'view_post.tpl', $this->tplpath . 'comments.tpl', $this->tplpath . 'comment_form.tpl', $this->dir, $this->url);
		}
	}
	
	function setMessageByArchive($archive) {
		$dates = explode('-', $archive);
		$year = isset($dates[0])? intval($dates[0]) : 0;
		$month = isset($dates[1])? intval($dates[1]) : 0;
		$day = isset($dates[2])? intval($dates[2]) : 0;
		$posts = $this->getPostList(0, array(), false, $year, $month, $day);
		$this->message = $this->getOutputPostList($posts);
	}
	
	function setMessageByCatAlias($alias) {
		$cats = $this->getCats();
		for ($i=0, $n=count($cats); $i<$n; $i++) {
			if ($this->formatAlias($cats[$i]['CAT_NAME'])==$alias) {
				$posts = $this->getPostList(0, array($cats[$i]['CAT_ID']));
				$this->message = $this->getOutputPostList($posts);
				return;
			}
		}
	}
	
	function setMessageByPostAlias($input) {
		$gets = explode(',', $input);
		$year = isset($gets[0])? intval($gets[0]) : 0;
		$month = isset($gets[1])? intval($gets[1]) : 0;
		$day = isset($gets[2])? intval($gets[2]) : 0;
		$alias = isset($gets[3])? $gets[3] : '';
		$sec = isset($gets[4])? intval($gets[4]) : 0;
		$posts = $this->getPostByAlias($year, $month, $day, $alias, $sec);
		if ($posts!==false) {
			$this->message .= $this->getOutputPost($posts, $this->tplpath . 'view_post.tpl', $this->tplpath . 'comments.tpl', $this->tplpath . 'comment_form.tpl', $this->dir);
		}
	}
	
	function setMessageByPostedComment($postid, $name, $website, $message) {
		$post_name = trim($name);
		$post_website = trim($website);
		$post_message = trim($message);
		if (empty($post_name) || empty($post_message) || strpos($post_message, '</comment>')!==false || strpos($post_message, '<comment>')!==false) {
			$this->message = $this->notes['notice']['invalid'];
		} else if ($this->checkBlackList($_SERVER['REMOTE_ADDR'])) {
			if ($this->checkFlood($_SERVER['REMOTE_ADDR'])) {
				$this->addComment($postid, $post_name, $post_website, $post_message);
				$this->message = $this->notes['notice']['confirm'];
			} else {
				$this->message = $this->notes['notice']['flood'];
			}
		} else {
			$this->message = $this->notes['notice']['block'];
		}
	}
	
}
?>

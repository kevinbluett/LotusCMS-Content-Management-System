<?php
include('modules/Blog/functions.php');
date_default_timezone_set("GMT");

class NewsPanel extends WingedNews {
	var $tpl;
	var $userfile;
	var $notes;
	var $message;
	
	function NewsPanel() {
		$this->WingedNews();
		$this->tpl = 'modules/Blog/template/' . $this->configs['tpl'] . '/';
		$this->userfile = './data/users.txt';
		$this->notes = parse_ini_file($this->tpl . 'note.tpl');
		$this->message = $this->notes['welcome'];
	}
	
	function delElements($keys, $path, $begin, $end) {
		$output = '';
		$lines = file($path);
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])==$begin) {
				if (!in_array(trim($lines[$i+1]), $keys)) {
					for (;; $i++) {
						$output .= $lines[$i];
						if (trim($lines[$i])==$end) break;
					}
				} else $i+=2;
			}
		}
		return $output;
	}
	
	function isDuplicate($input, $array, $key) {
		if (get_magic_quotes_gpc()) {
			$input = htmlspecialchars(stripslashes($input), ENT_QUOTES);
		} else {
			$input = htmlspecialchars($input, ENT_QUOTES);
		}
		for ($i=0, $n=count($array); $i<$n; $i++) {
			if (strcasecmp($array[$i][$key], $input)===0) return true;
		}
		return false;
	}
	
	function isAuthorised() {
		return $_SESSION['login'];
	}

	function login($user, $pass) {
		if ($_SESSION['login']){
			return true;	
		}else{
			return false;	
		}
	}

	function getUserDetails($id) {
		/*$users = array();
		$lines = file($this->userfile);
		for ($i=0, $n=count($lines); $i<$n; $i++) {
			if (trim($lines[$i])=='<user>') {
				if (trim($lines[$i+1])==$id) {
					$users['id'] = $id;
					$users['user'] = htmlspecialchars(stripslashes(trim($lines[$i+2])), ENT_QUOTES);
					$users['pass'] = trim($lines[$i+3]);
					break;
				} else $i+=4;
			}
		}*/
		return $users;
	}

	function addPost($subject, $message, $cats=null, $locked=false, $time=null) {
		if (!get_magic_quotes_gpc()) {
			$subject = addslashes($subject);
			$message = addslashes($message);
		}
		$id = $time==null? ($this->configs['daylight']? time()+3600 : time()) : $time;
		$dates = $this->getDateArray($id);
		$file = $this->postpath . $dates['year'] . '-' . $dates['mon'] . '.txt';
		$content = "\n<post>\n" . $id . "\n" . $subject . "\n" . $message . "\n</post>";
		$handle = fopen($file, 'a') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		if ($cats!=null) {
			$content = '';
			for ($i=0, $n=count($cats); $i<$n; $i++) {
				$content .= "\n<cat>\n" . $cats[$i] . "\n</cat>";
			}
			$handle = fopen($this->catpath . $id . '.txt', 'w') or exit('error1');
			if (fwrite($handle, $content)===false) {
				fclose($handle);
				exit('error2');
			}
		}
		if ($locked) {
			$handle = fopen($this->lockfile, 'a') or exit('error1');
			if (fwrite($handle, "\n<post>\n" . $id . "\n</post>")===false) {
				fclose($handle);
				exit('error2');
			}
		}
		fclose($handle);
	}

	function delPosts($ids) {
		$archives = array();
		$locked_posts = $this->getLockedPosts();
		$num_lock = count($locked_posts);
		for ($i=0, $n=count($ids); $i<$n; $i++) {
			if (is_file($this->commentpath . $ids[$i]. '.txt')) {
				if (unlink($this->commentpath . $ids[$i]. '.txt')===false) exit('error4');
			}
			if (is_file($this->catpath . $ids[$i]. '.txt')) {
				if (unlink($this->catpath . $ids[$i]. '.txt')===false) exit('error4');
			}
			$dates = $this->getDateArray($ids[$i]);
			$key = $dates['year'] . '-' . $dates['mon'];
			$archives[$key][] = $ids[$i];
			$index = array_search($ids[$i], $locked_posts);
			if ($index!==false) unset($locked_posts[$index]);
		}
		if (count($locked_posts)<$num_lock) {
			$content = '';
			foreach ($locked_posts as $post) {
				$content .= "\n<post>\n" . $post . "\n</post>";
			}
			$handle = fopen($this->lockfile, 'w') or exit('error1');
			if (fwrite($handle, $content)===false) {
				fclose($handle);
				exit('error2');
			}
			fclose($handle);
		}
		foreach ($archives as $key => $vals) {
			$content = $this->delElements($vals, $this->postpath . $key . '.txt', '<post>' , '</post>');
			if (empty($content)) {
				if (unlink($this->postpath . $key . '.txt')===false) exit('error4');
			} else {
				$handle = fopen($this->postpath . $key . '.txt', 'w') or exit('error1');
				if (fwrite($handle, $content)===false) {
					fclose($handle);
					exit('error2');
				}
				fclose($handle);
			}
		}
	}

	function editPost($id, $subject, $message, $cats=null, $locked=false) {
		$dates = $this->getDateArray($id);
		$file = $this->postpath . $dates['year'] . '-' . $dates['mon'] . '.txt';
		$content = $this->delElements(array($id), $file, '<post>', '</post>');
		if (!get_magic_quotes_gpc()) {
			$subject = addslashes($subject);
			$message = addslashes($message);
		}
		$content .= "\n<post>\n" . $id . "\n" . $subject . "\n" . $message . "\n</post>";
		$handle = fopen($file, 'w') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		if ($cats!=null) {
			$content = '';
			for ($i=0, $n=count($cats); $i<$n; $i++) {
				$content .= "\n<cat>\n" . $cats[$i] . "\n</cat>";
			}
			$handle = fopen($this->catpath . $id . '.txt', 'w') or exit('error1');
			if (fwrite($handle, $content)===false) {
				fclose($handle);
				exit('error2');
			}
		} else if (is_file($this->catpath . $id . '.txt')) {
			if (unlink($this->catpath . $id . '.txt')===false) {
				fclose($handle);
				exit('error4');
			}
		}
		$locked_posts = $this->getLockedPosts();
		if ($locked) {
			if (!in_array($id, $locked_posts)) {
				$handle = fopen($this->lockfile, 'a') or exit('error1');
				if (fwrite($handle, "\n<post>\n" . $id . "\n</post>")===false) {
					fclose($handle);
					exit('error2');
				}
			}
		} else {
			$index = array_search($id, $locked_posts);
			if ($index!==false) {
				array_splice($locked_posts, $index, 1);
				$content = '';
				for ($i=0, $n=count($locked_posts); $i<$n; $i++) {
					$content .= "\n<post>\n" . $locked_posts[$i] . "\n</post>";
				}
				$handle = fopen($this->lockfile, 'w') or exit('error1');
				if (fwrite($handle, $content)===false) {
					fclose($handle);
					exit('error2');
				}
			}
		}
		fclose($handle);
	}

	function delComments($post, $ids) {
		$content = $this->delElements($ids, $this->commentpath . $post . '.txt', '<comment>', '</comment>');
		if (empty($content)) {
			if (unlink($this->commentpath . $post . '.txt')===false) exit('error4');
			return;
		}
		$handle = fopen($this->commentpath . $post . '.txt', 'w') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($hanlde);
			exit('error2');
		}
		fclose($handle);
	}

	function editUser($id, $user, $pass) {
		$content = $this->delElements(array($id), $this->userfile, '<user>', '</user>');
		if (get_magic_quotes_gpc()) {
			$pass = stripslashes($pass);
		} else {
			$user = addslashes($user);
		}
		$content .= "\n<user>\n" . $id . "\n" . $user . "\n" . md5($pass) . "\n</user>";
		$handle = fopen($this->userfile, 'w') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
	}

	function getConfigArray() {
		$cfgs = array(
					'tpl' => $this->configs['tpl'],
					'display' => $this->configs['display'],
					'flood' => $this->configs['flood'],
					'comment' => $this->configs['comment']? 'checked' : '',
					'filter' => $this->configs['filter'],
					'list' => $this->configs['list'],
					'date' => $this->configs['date'],
					'time' => $this->configs['time'],
					'daylight' => $this->configs['daylight']? 'checked' : '',
					'alias' => $this->configs['alias']? 'checked' : '',
					'uri' => $this->configs['uri'],
					'title' => $this->configs['title'],
					'description' => $this->configs['description'],
					'langs' => array(
								array(
									'"' . $this->configs['locale'] . '"',
									'<option value="' . $this->configs['zone'] . '"'
								),
								array(
									'"' . $this->configs['locale'] . '" selected ',
									'<option value="' . $this->configs['zone'] . '" selected '
									)
								)
				);
		return $cfgs;
	}

	function editConfig($uri, $title, $description, $zone, $date, $time, $tpl, $display, $flood, $filter, $list, $locale, $daylight=0, $comment=0, $alias=0) {
		$filter = str_replace(array("\r", "\n"), '', $filter);
		$list = str_replace(array("\r", "\n"), '', $list);
		$content = "zone = $zone
					date = \"$date\"
					time = \"$time\"
					tpl = \"$tpl\"
					display = \"$display\"
					flood = $flood
					comment = $comment
					filter = \"$filter\"
					list = \"$list\"
					locale = \"$locale\"
					daylight = $daylight
					alias = $alias
					uri = $uri
					title = $title
					description = $description
					";
		$handle = fopen($this->configfile, 'w') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
	}

	function previewPost($subject, $message) {
		$posts['subject'] = get_magic_quotes_gpc()? htmlspecialchars(stripslashes($subject), ENT_QUOTES) : htmlspecialchars($subject, ENT_QUOTES);
		$posts['message'] = get_magic_quotes_gpc()? $this->str2para($this->parseBB(htmlspecialchars(stripslashes($message), ENT_QUOTES))) : $this->str2para($this->parseBB(htmlspecialchars($message, ENT_QUOTES)));
		return $this->parseTemplate(file_get_contents($this->tpl . 'preview.tpl'), $posts);
	}

	function addCat($catname) {
		if (!get_magic_quotes_gpc()) {
			$catname = addslashes($catname);
		}
		$cats = $this->getCats();
		$id = 1;
		for ($i=0, $n=count($cats); $i<$n; $i++) {
			if ($cats[$i]['CAT_ID']>=$id) $id = $cats[$i]['CAT_ID'] + 1;
		}
		$content = "\n<cat>\n" . $id . "\n" . $catname . "\n</cat>";
		$handle = fopen($this->catfile, 'a') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
	}

	function editCats($edit, $cats, $catnames=null) {
		$content = $this->delElements($cats, $this->catfile, '<cat>', '</cat>');
		if ($edit=='r') {	// rename
			for ($i=0, $n=count($cats); $i<$n; $i++) {
				$name = get_magic_quotes_gpc()? $catnames[$i] : addslashes($catnames[$i]);
				$content .= "\n<cat>\n" . $cats[$i] . "\n" . $name . "\n</cat>";
			}
		} else if ($edit=='d') {	// delete
			$catposts = $this->getCatsPosts($cats);
			$posts = array();
			for ($i=0, $n=count($catposts); $i<$n; $i++) {
				for ($j=0, $m=count($catposts[$i]); $j<$m; $j++) {
					if (!in_array($catposts[$i][$j], $posts)) $posts[] = $catposts[$i][$j];
				}
			}
			$this->delPosts($posts);
		}
		$handle = fopen($this->catfile, 'w') or exit('error1');
		if (fwrite($handle, $content)===false) {
			fclose($handle);
			exit('error2');
		}
		fclose($handle);
	}
}

?>

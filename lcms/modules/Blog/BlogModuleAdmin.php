<?php
include("modules/Blog/index.php");
/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	protected $news;

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin(){
		
		//Sets the unix name of the plugin
		$this->setUnix("Blog");	
		
		//Create a news panel.
		$this->news = new NewsPanel();
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		$data = "<ul style='font-size: 12px;'><li style='padding-bottom: 10px;'><a href='".$this->toRequest("createPost")."'>Create New Post</a></li><li style='padding-bottom: 10px;'><a href='".$this->toRequest("managePosts")."'>Manage Posts</a></li><li style='padding-bottom: 10px;'><a href='".$this->toRequest("manageCategories")."'>Manage Categories</a></li><li style='padding-bottom: 10px;'><a href='".$this->toRequest("settings")."'>Change Blog Settings</a></li></ul>";
		
		$this->setContent($data);
	}
	
	/**
	 * Allows the deleting of a comment.
	 */
	public function deleteCommentRequest(){
		
		$id = $this->getInputString("id", null, "P");
		$ids  = $this->getInputString("ids", null, "P");
		if(empty($id)) exit("Error 3");
			if (!empty($ids)) {
				$this->getNews()->delComments($id, $ids);
				$this->getNews()->message = $this->getNews()->notes['comment_del'];
			} else {
				$this->getNews()->message = $this->getNews()->notes['invalid'];
			}
			
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Change Blog Settings.
	 */
	public function settingsSubmitRequest(){
		$uri = $this->getInputString("uri", null, "P");
		$title = $this->getInputString("title", null, "P");
		$description = $this->getInputString("description", null, "P");
		$zone = $this->getInputString("zone", null, "P");
		$date = $this->getInputString("date", null, "P");
		$time = $this->getInputString("time", null, "P");
		$display = $this->getInputString("display", null, "P");
		$flood = $this->getInputString("flood", null, "P");
		$filter = $this->getInputString("filter", null, "P");
		$list = $this->getInputString("list", null, "P");
		$locale = $this->getInputString("locale", null, "P");
		$daylight = $this->getInputString("daylight", "", "P");
		$comment = $this->getInputString("comment", "", "P");
		$alias = $this->getInputString("alias", "", "P");
		

		
			if (is_dir('modules/Blog/template/' . $_POST['tpl']) && is_dir('modules/Blog/display/' . $_POST['display'])) {
				$post_daylight = (!empty($daylight))? 1 : 0;
				$post_comment = (!empty($comment))? 1 : 0;
				$post_alias = (!empty($alias))? 1 : 0;
				$this->getNews()->editConfig($uri, $title, $description, $zone, $_POST['date'], $time, "default", "basic", $flood, $filter, $list, $locale, $post_daylight, $post_comment, $post_alias);
				$this->getNews()->message = $this->getNews()->notes['conf_edit'];
			} else {
				$this->getNews()->message = $this->getNews()->notes['invalid'];
			}
		
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Change Blog Settings.
	 */
	public function settingsRequest(){
			$configs = $this->getNews()->getConfigArray();
			$langs = array_pop($configs);
			$this->setContent($this->getNews()->parseTemplate(str_replace($langs[0], $langs[1], file_get_contents($this->getNews()->tpl . 'settings.tpl')), $configs));
	}
	
	/**
	 * Manage Post Categories.
	 */
	public function manageCategoriesSubmitRequest(){
		$new = $this->getInputString("new", null, "P");
		$rename = $this->getInputString("rename", null, "P");
		$cats = $this->getInputString("cats", null, "P");
		$del = $this->getInputString("del", null, "P");
		
		if (!empty($new)) {
				$post_catname = trim($this->getInputString("cat_name"));
				if (empty($post_catname) || strpos($post_catname, '<cat>')!==false || strpos($post_catname, '</cat>')!==false || $this->getNews()->isDuplicate($post_catname, $this->getNews()->getCats(), 'CAT_NAME')) {
					$this->getNews()->message = $this->getNews()->notes['invalid'];
				} else {
					$this->getNews()->addCat($post_catname);
					$this->getNews()->message = $this->getNews()->notes['cat_add'];
				}
			} else if (!empty($rename)) {
				if (!empty($cats)) {
					$post_cats = array('cat_id'=>array(), 'cat_name'=>array());
					$flag = true;
					$catlist = $this->getNews()->getCats();
					for ($i=0, $n=count($cats); $i<$n; $i++) {
						list($catid, $catname) = array_map('trim', explode("\n", $cats[$i]));
						if (empty($catname) || strpos($catname, '<cat>')!==false || strpos($catname, '</cat>')!==false || $this->getNews()->isDuplicate($catname, $catlist, 'CAT_NAME')) {
							$this->getNews()->message = $this->getNews()->notes['invalid'];
							$flag = false;
							break;
						} else {
							$post_cats['cat_id'][] = $catid;
							$post_cats['cat_name'][] = $catname;
						}
					}
					if ($flag) {
						$this->getNews()->editCats('r', $post_cats['cat_id'], $post_cats['cat_name']);
						$this->getNews()->message = $this->getNews()->notes['cat_rename'];
					}
				} else {
					$this->getNews()->message = $this->getNews()->notes['invalid'];
				}
			} else if (!empty($del)) {
				if (!empty($cats)) {
					$this->getNews()->editCats('d', $_POST['cats']);
					$this->getNews()->message = $this->getNews()->notes['cat_del'];
				} else {
					$this->getNews()->message = $this->getNews()->notes['invalid'];
				}
			}	
			
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Manage Post Categories.
	 */
	public function manageCategoriesRequest(){
		
		$cats = $this->getNews()->getCats(true);
		$last_cat = count($cats)-1;
		
		for ($i=0; $i<$last_cat; $i++) {
			unset($cats[$i]['POSTS']);
		}
		
		unset($cats[$last_cat]);
		
		$this->setContent($this->getNews()->parseRecTemplate(file_get_contents($this->getNews()->tpl . 'category_list.tpl'), '<!-- BEGIN -->', '<!-- END -->', $cats));
		
	}
	
	/**
	 * Edit Post
	 */
	public function editPostRequest(){
		
		$subject = $this->getInputString("subject", null, "P");
		$id = $this->getInputString("id", null, "P");
		$message = $this->getInputString("message", null, "P");
		$submit = $this->getInputString("submit", null, "P");
		$cats = $this->getInputString("cats", null, "P");
		
		if (!( (!empty($subject)) && (!empty($id)) && (!empty($message)))) {
			
			$out = $this->openFile("core/fragments/hacking_attempt.phtml");
			
			exit($out);
		}
		if (!empty($submit)) {
				$lock = $this->getInputString("locked", "", "P");
				$post_subject = trim($subject);
				$post_message = trim($message);
				$post_cats = (!empty($cats))? $cats : null;
				$post_locked = (!empty($lock));
				if (empty($post_subject) || empty($post_message) || strpos($post_message, '</post>')!==false || strpos($post_message, '<post>')!==false) {
					$this->getNews()->message = $this->getNews()->notes['invalid'];
				} else {
					$this->getNews()->editPost($id, $post_subject, $post_message, $post_cats, $post_locked);
					$this->getNews()->message = $this->getNews()->notes['post_edit'];
				}	
		} else if (isset($_POST['preview'])) {
			print $this->getNews()->previewPost($_POST['subject'], $_POST['message']);
			exit('<!-- preview -->');
		}
		
		//Message set to Content
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Saves the manage post requests.
	 */
	public function managePostsSubmitRequest(){
		$del = $this->getInputString("del", null, "P");
		$ids = $this->getInputString("ids", null, "P");
		$edit = $this->getInputString("edit", null, "P");
		$view = $this->getInputString("view", null, "P");
		
		if (!empty($del)) {
				if (!empty($ids)) {
					$this->getNews()->delPosts($ids);
					$this->getNews()->message = $this->getNews()->notes['post_del'];
				} else {
					$this->getNews()->message = $this->getNews()->notes['invalid'];
				}
		} else if (!empty($edit)) {
			if (!empty($ids)) {
				$posts = $this->getNews()->getPostDetails($ids[0]);
				$cats = $posts['CATS'];
				$num_cat = count($cats);
				for ($i=0; $i<$num_cat; $i++) {
					unset($cats[$i]['POSTS'], $cats[$i]['NUM_POST']);
				}
				if ($num_cat>1) unset($cats[$num_cat-1]);
				unset($posts['CATS']);
				$this->getNews()->message = $this->getNews()->parseTemplate($this->getNews()->parseRecTemplate(file_get_contents($this->getNews()->tpl . 'post_detail.tpl'), '<!-- BEGIN -->', '<!-- END -->', $cats), $posts);
			} else {
				$this->getNews()->message = $this->getNews()->notes['invalid'];
			}
		} else if (!empty($view)) {
			if (!empty($ids)) {
				$posts = $this->getNews()->getPostDetails($_POST['ids'][0]);
				if ($posts!==false) {
					$this->getNews()->message = $this->getNews()->getOutputPost($posts, $this->getNews()->tpl . 'post_view.tpl', $this->getNews()->tpl . 'post_comments.tpl');
				}
			} else {
				$this->getNews()->message = $this->getNews()->notes['invalid'];
			}
		}	
		
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Allows the managing of Posts in the Blogging system.
	 */
	public function managePostsRequest(){
		
		$posts = $this->getNews()->getPostList(0, array(), true);
					for ($i=0, $n=count($posts); $i<$n; $i++) {
						$posts[$i]['CATEGORY'] = '';
						$last_cat = count($posts[$i]['CATS'])-1;
						for ($j=0; $j<$last_cat; $j++) {
							if ($posts[$i]['CATS'][$j]['CHECK']) {
								$posts[$i]['CATEGORY'] .= $posts[$i]['CATS'][$j]['CAT_NAME'] . ', ';
							}
						}
						$posts[$i]['CATEGORY'] = empty($posts[$i]['CATEGORY'])? $posts[$i]['CATS'][$last_cat]['CAT_NAME'] : substr($posts[$i]['CATEGORY'], 0, -2);
						unset($posts[$i]['CATS'], $posts[$i]['MESSAGE']);
					}
		
		$this->setContent($this->getNews()->parseRecTemplate(file_get_contents($this->getNews()->tpl . 'post_list.tpl'), '<!-- BEGIN -->', '<!-- END -->', $posts));
	}
	
	/**
	 * Default Requestion
	 */
	public function createPostRequest(){
		
			$cats = $this->getNews()->getCats();
			$num_cat = count($cats);
			for ($i=0; $i<$num_cat; $i++) {
				unset($cats[$i]['POSTS'], $cats[$i]['NUM_POST']);
			}
			if ($num_cat>1) unset($cats[$num_cat-1]);	
			$todays = $this->getNews()->getDateArray();
			$dates = array(
						array(
							'"da' . $todays['mday'] . '"',
							'"mo' . $todays['mon'] . '"',
							'"hr' . $todays['hours'] . '"',
							'"mi' . $todays['minutes'] . '"'
						),
						array(
							'"da' . $todays['mday'] . '" selected ',
							'"mo' . $todays['mon'] . '" selected ',
							'"hr' . $todays['hours'] . '" selected ',
							'"mi' . $todays['minutes'] . '" selected '
						)
					);
			$years = array();
			for ($i=$todays['year'], $n=$todays['year']+5; $i<$n; $i++) {
				$years[]['year'] = $i;
			}
			$tpl = str_replace($dates[0], $dates[1], file_get_contents($this->getNews()->tpl . 'newpost.tpl'));
			$this->setContent($this->getNews()->parseRecTemplate($this->getNews()->parseRecTemplate($tpl, '<!-- BEGIN1 -->', '<!-- END1 -->', $years), '<!-- BEGIN -->', '<!-- END -->', $cats));
	}
	
	/**
	 * Creates the post after submission
	 */ 
	public function submitPostRequest(){
		
		$post_subject = trim($this->getInputString('subject', "", "P"));
		$post_message = trim($this->getInputString('message', "", "P"));
		$post_cats = $this->getInputString('cats', null, "P");
		$post_locked = $this->getInputString('locked', false, "P");
		$post_day = intval(substr($this->getInputString('day', 1, "P"), 2));
		$post_month = intval(substr($this->getInputString('month', 1, "P"), 2));
		$post_year = intval($this->getInputString('year', 1, "P"));
		$post_hr = intval(substr($this->getInputString('hr', 1, "P"), 2));
		$post_min = intval(substr($this->getInputString('min', 1, "P"), 2));
		
		$time = $this->getInputString('time', '', 'P');
		 
		if (empty($post_subject) || empty($post_message) ||
			strpos($post_message, '</post>')!==false || strpos($post_message, '<post>')!==false ||
			(!empty($time) && (!checkdate($post_month, $post_day, $post_year) ||
			$post_hr<0 || $post_hr>23 || $post_min<0 || $post_min>59))
			)
		{
			$this->getNews()->message = $this->getNews()->notes['invalid'];
		} else if (!empty($time)) {
			$post_time = gmmktime($post_hr, $post_min, 0, $post_month, $post_day, $post_year) - $this->getNews()->configs['zone']*3600;
			while ($this->getNews()->getPostDetails($post_time)!==false) {
				$post_time++;
			}
			$this->getNews()->addPost($post_subject, $post_message, $post_cats, $post_locked, $post_time);
			$this->getNews()->message = $this->getNews()->notes['post_add'];
		} else {
			$this->getNews()->addPost($post_subject, $post_message, $post_cats, $post_locked);
			$this->getNews()->message = $this->getNews()->notes['post_add'];
		}
		
		//Prints whatever message the news plugin decided on.
		$this->setContent($this->getNews()->message);
	}
	
	/**
	 * Gets the news panel
	 */
	public function getNews(){
		return $this->news;	
	}
	
	/**
	 * Default Requestion
	 */
	public function manageRequest(){
		
		$data = "This Plugin's Administration has yet to be built by the LotusCMS team.";
		
		$this->setContent($data);
	}
	
	/**
	 * Default Requestion
	 */
	public function settingRequest(){
		
		$data = "This Plugin's Administration has yet to be built by the LotusCMS team.";
		
		$this->setContent($data);
	}
}

?>
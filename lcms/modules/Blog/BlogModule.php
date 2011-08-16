<?php
date_default_timezone_set("GMT");
// include the script at the _top_
include('modules/Blog/news.php');

class BlogModule extends Module{

	protected $news;

	/**
	 * Sets up the news module
	 */
	public function BlogModule($page){
		
		//Create news loader
		$news = new NewsDisplay();
		
		//Set this news yoke
		$this->news = $news;
	}

	/**
	 * Sets the requests of the system
	 */
	public function putRequests(){
		
		//Create the array of request
		$requests = array(
					"index",
					"rss"
				);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 *
	 */
	public function defaultRequest(){
		
		$data = "";

		$post = $this->getModel()->getInputString("post", null, "G");
		$archive = $this->getModel()->getInputString("archive", null, "G");
		$category = $this->getModel()->getInputString("category", null, "G");
		$cat_alias = $this->getModel()->getInputString("cat_alias", null, "G");
		$post_alias = $this->getModel()->getInputString("post_alias", null, "G");
		$do = $this->getModel()->getInputString("do", null, "P");
		
		//If the request to process a comment or similar is set.
		if(!empty($do)){
			//Process the do request
			$this->processSubmission($do);
		}
		
		if(!empty($post)){
			$data = $this->getPostViaID($post);
			$this->getView()->setContentTitle("Blog");
		}else if(!empty($archive)){
			$data = $this->getArchive($archive);
			$this->getView()->setContentTitle("Archive");
		}else if(!empty($category)){
			$data = $this->getCategoryViaID($category);
			$this->getView()->setContentTitle("Category");
		}else if(!empty($cat_alias)){
			$data = $this->getCategoryViaAlias($cat_alias);
			$this->getView()->setContentTitle("Category: ".$cat_alias);
		}else if(!empty($post_alias)){
			$data = $this->getPostViaAlias($post_alias);
			$this->getView()->setContentTitle("Blog");
		}else{
			$data = $this->getLatestPosts();
			$this->getView()->setContentTitle("Blog");
		}
		$this->getView()->setContent($data);
		$this->getView()->setLeftTitle("Archive");
		$this->getView()->setLeftContent($this->news->getArchives());
	}
	
	/**
	 * Get RSS Feed
	 */
	public function rssRequest(){
		$tpl = '<?xml version="1.0"?>
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
		</rss>';
		$info = array (
					'uri' => $this->news->configs['uri'],
					'title' => $this->news->configs['title'],
					'description' => $this->news->configs['description']
				);
		
		
		$posts = $this->news->getPostList(10);
		for ($i=0, $n=count($posts); $i<$n; $i++) {
			$posts[$i]['MESSAGE'] = preg_replace('/(.*?)(\.(\n+|\r+))(.|\s|\n|\r)*/', '$1$2', $this->news->parseBB($posts[$i]['MESSAGE']));
			$posts[$i]['MESSAGE'] = htmlspecialchars(html_entity_decode($posts[$i]['MESSAGE'], ENT_QUOTES), ENT_QUOTES);
			$posts[$i]['DATE'] = gmdate('D, d M Y H:i:s T', $posts[$i]['ID']);
			unset($posts[$i]['CATS']);
		}
		
		header('Content-type: text/xml');
		print $this->news->parseRecTemplate($this->news->parseTemplate($tpl, $info), '<!-- BEGIN -->', '<!-- END -->', $posts);
		exit;
	}
	
	/**
	 * Load Latest Posts
	 */
	public function getLatestPosts(){
		return $this->news->getNews(10, array());
	}
	
	/**
	 * Gets an ID
	 */
	public function getPostViaID($id){
		$this->news->setMessageById($id);	
		
		return $this->news->message;
	}
	
	/**
	 * Gets an ID
	 */
	public function getPostViaAlias($id){
		
		$this->news->setMessageByPostAlias($id);	
		
		return $this->news->message;
	}
	
	/**
	 * Gets an ID
	 */
	public function getCategoryViaID($id){		
		return $this->news->getOutputPostList($this->news->getPostList(0, array($id)));	
	}
	
	/**
	 * Gets an ID
	 */
	public function getArchive($id){
		$this->news->setMessageByArchive($id);
		
		return $this->news->message;
	}
	
	/**
	 * Gets an ID
	 */
	public function getCategoryViaAlias($id){
		$this->news->setMessageByCatAlias($id);	
		
		return $this->news->message;
	}
	
	/**
	 * Process Submission
	 */
	public function processSubmission($do){
		
		$id = $this->getModel()->getInputString("id", null, "P");
		$name = $this->getModel()->getInputString("name", null, "P");
		$website = $this->getModel()->getInputString("website", null, "P");
		$message = $this->getModel()->getInputString("message", null, "P");
		
		
		if ($do=='comment') {
			if (empty($id) || empty($name) || empty($website) || empty($message) || !($this->news->commentsAllowed($id))) {
				exit('Blogging System: error3');
			}
			$this->news->setMessageByPostedComment($id, $name, $website, $message);
		}
	}
}

?>
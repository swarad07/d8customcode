<?php
/**
 * @file
 * Contains Drupal\swarad\Controller\TwitterFeed.
 */ 
namespace Drupal\swarad\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwitterFeed extends ControllerBase {
	
	protected $twitter;

	public function __construct($twitter) {
		$this->twitter = $twitter;
	}

	public static function create(ContainerInterface $container) {
		return new static(
	      $container->get('swarad.twitterservice')
	    );
	}


	/**
	 *  Return the the twitter feed.
	 */ 
	public function getFeed($username) {
		//Call some service here to return content.
    $data = $this->twitter->renderData($username);
		$content = array(
      '#markup' => $data,
    );

    return $content;
	}

	public function twitterTitleCallbak() {
		return 'Latest 10 tweets from ' . $username;
	}
}
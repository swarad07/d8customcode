<?php
/**
 * @file
 * Contains Drupal\swarad\Controller\TwitterFeed.
 */ 
namespace Drupal\swarad\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwitterFeed extends ControllerBase {
	
	private $twitter;

	private $logger;

	public function __construct($twitter, $logger) {
		$this->twitter = $twitter;
		$this->logger = $logger;
	}

	public static function create(ContainerInterface $container) {
		return new static(
	      $container->get('swarad.twitterservice'),
	      $container->get('logger.factory')
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
	$this->logger->get('default')->debug($data);
	//\Drupal::logger('swarad')->notice('Simple Page was displayed');

    return $content;
	}

	public function twitterTitleCallbak($username) {
		return 'Latest 10 tweets from ' . $username;
	}
}
<?php

namespace Drupal\swarad\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\swarad\TwitterService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TwitterFeed.
 *
 * @package Drupal\swarad\Controller
 */
class TwitterFeed extends ControllerBase {
  private $twitter;

  private $logger;

  /**
   * TwitterFeed constructor.
   *
   * @param TwitterService $twitter
   *   Twitter service object.
   * @param LoggerInterface $logger
   *   Logger service.
   */
  public function __construct(TwitterService $twitter, LoggerInterface $logger) {
    $this->twitter = $twitter;
    $this->logger = $logger;
  }

  /**
   * Create function.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The services container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('swarad.twitterservice'),
      $container->get('logger.factory')
    );
  }

  /**
   * Return the the twitter feed.
   */
  public function getFeed($username) {
    // Call some service here to return content.
    $data = $this->twitter->renderData($username);
    $content = array(
      '#markup' => $data,
    );
    $this->logger->get('default')->debug($data);
    // \Drupal::logger('swarad')->notice('Simple Page was displayed');.
    return $content;
  }

  /**
   * Twitter title callback.
   *
   * @param string $username
   *   The twitter handle to display tweets for.
   *
   * @return string
   *   Return the title of the twitter feed.
   */
  public function twitterTitleCallbak($username) {
    return 'Latest 10 tweets from ' . $username;
  }
}
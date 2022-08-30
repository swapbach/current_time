<?php

namespace Drupal\current_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\current_time\CurrentTimeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to show Current Time based on the timezone in config.
 *
 * @Block(
 *   id = "current_time_block",
 *   admin_label = @Translation("Current Time Block"),
 *   category = @Translation("Custom blocks")
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Current Time service.
   *
   * @var \Drupal\current_time\CurrentTimeService
   */
  protected $currentTime;

  /**
   * Current time block constructor.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\current_time\CurrentTimeService $current_time
   *   The current time service.
   */
  final public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory,
    CurrentTimeService $current_time,
    ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->currentTime = $current_time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('current_time.current_time_service'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build() {
    // Get the admin config form.
    $config = $this->configFactory->get('timezone.settings');

    return [
      '#theme' => 'current_time_block',
      '#city' => $config->get('city'),
      '#country' => $config->get('country'),
      '#currentTime' => $this->currentTime->getTimezone(),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}

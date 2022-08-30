<?php

namespace Drupal\current_time;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Service to get the current time based on the selected timezone.
 */
class CurrentTimeService {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Current time service constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get the Timezone from the config.
   */
  public function getTimezone() {
    // Get the admin config form.
    $config = $this->configFactory->get('timezone.settings');

    // Get the value of the timezone from config.
    $timezone = $config->get('timezone');

    return $this->getCurrentTime($timezone);
  }

  /**
   * Get the Current Time.
   */
  public function getCurrentTime($timezone) {
    $date = new DrupalDateTime('now');
    $date->setTimezone(new \DateTimeZone($timezone));
    // Render the date in the format : 27th Aug 2022 - 12:50 PM.
    return $date->format('jS M Y - g:i A');
  }

}

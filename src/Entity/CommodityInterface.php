<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Commodity entities.
 *
 * @ingroup bar_exchange
 */
interface CommodityInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Commodity name.
   *
   * @return string
   *   Name of the Commodity.
   */
  public function getName();

  /**
   * Sets the Commodity name.
   *
   * @param string $name
   *   The Commodity name.
   *
   * @return \Drupal\bar_exchange\Entity\CommodityInterface
   *   The called Commodity entity.
   */
  public function setName($name);

  /**
   * Gets the Commodity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Commodity.
   */
  public function getCreatedTime();

  /**
   * Sets the Commodity creation timestamp.
   *
   * @param int $timestamp
   *   The Commodity creation timestamp.
   *
   * @return \Drupal\bar_exchange\Entity\CommodityInterface
   *   The called Commodity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Commodity published status indicator.
   *
   * Unpublished Commodity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Commodity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Commodity.
   *
   * @param bool $published
   *   TRUE to set this Commodity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bar_exchange\Entity\CommodityInterface
   *   The called Commodity entity.
   */
  public function setPublished($published);

}

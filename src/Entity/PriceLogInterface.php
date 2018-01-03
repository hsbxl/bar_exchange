<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Price log entities.
 *
 * @ingroup bar_exchange
 */
interface PriceLogInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Price log name.
   *
   * @return string
   *   Name of the Price log.
   */
  public function getName();

  /**
   * Sets the Price log name.
   *
   * @param string $name
   *   The Price log name.
   *
   * @return \Drupal\bar_exchange\Entity\PriceLogInterface
   *   The called Price log entity.
   */
  public function setName($name);

  /**
   * Gets the Price log creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Price log.
   */
  public function getCreatedTime();

  /**
   * Sets the Price log creation timestamp.
   *
   * @param int $timestamp
   *   The Price log creation timestamp.
   *
   * @return \Drupal\bar_exchange\Entity\PriceLogInterface
   *   The called Price log entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Price log published status indicator.
   *
   * Unpublished Price log are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Price log is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Price log.
   *
   * @param bool $published
   *   TRUE to set this Price log to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bar_exchange\Entity\PriceLogInterface
   *   The called Price log entity.
   */
  public function setPublished($published);

}

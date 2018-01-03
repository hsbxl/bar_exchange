<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Sale item entities.
 *
 * @ingroup bar_exchange
 */
interface SaleItemInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Sale item name.
   *
   * @return string
   *   Name of the Sale item.
   */
  public function getName();

  /**
   * Sets the Sale item name.
   *
   * @param string $name
   *   The Sale item name.
   *
   * @return \Drupal\bar_exchange\Entity\SaleItemInterface
   *   The called Sale item entity.
   */
  public function setName($name);

  /**
   * Gets the Sale item creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Sale item.
   */
  public function getCreatedTime();

  /**
   * Sets the Sale item creation timestamp.
   *
   * @param int $timestamp
   *   The Sale item creation timestamp.
   *
   * @return \Drupal\bar_exchange\Entity\SaleItemInterface
   *   The called Sale item entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Sale item published status indicator.
   *
   * Unpublished Sale item are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Sale item is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Sale item.
   *
   * @param bool $published
   *   TRUE to set this Sale item to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bar_exchange\Entity\SaleItemInterface
   *   The called Sale item entity.
   */
  public function setPublished($published);

}

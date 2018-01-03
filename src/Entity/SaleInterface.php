<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Sale entities.
 *
 * @ingroup bar_exchange
 */
interface SaleInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Sale name.
   *
   * @return string
   *   Name of the Sale.
   */
  public function getName();

  /**
   * Sets the Sale name.
   *
   * @param string $name
   *   The Sale name.
   *
   * @return \Drupal\bar_exchange\Entity\SaleInterface
   *   The called Sale entity.
   */
  public function setName($name);

  /**
   * Gets the Sale creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Sale.
   */
  public function getCreatedTime();

  /**
   * Sets the Sale creation timestamp.
   *
   * @param int $timestamp
   *   The Sale creation timestamp.
   *
   * @return \Drupal\bar_exchange\Entity\SaleInterface
   *   The called Sale entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Sale published status indicator.
   *
   * Unpublished Sale are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Sale is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Sale.
   *
   * @param bool $published
   *   TRUE to set this Sale to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bar_exchange\Entity\SaleInterface
   *   The called Sale entity.
   */
  public function setPublished($published);

}

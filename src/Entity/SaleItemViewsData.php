<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Sale item entities.
 */
class SaleItemViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}

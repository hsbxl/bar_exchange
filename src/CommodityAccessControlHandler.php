<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Commodity entity.
 *
 * @see \Drupal\bar_exchange\Entity\Commodity.
 */
class CommodityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bar_exchange\Entity\CommodityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished commodity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published commodity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit commodity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete commodity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add commodity entities');
  }

}

<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Sale item entity.
 *
 * @see \Drupal\bar_exchange\Entity\SaleItem.
 */
class SaleItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bar_exchange\Entity\SaleItemInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished sale item entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published sale item entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit sale item entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete sale item entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add sale item entities');
  }

}

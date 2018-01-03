<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Price log entity.
 *
 * @see \Drupal\bar_exchange\Entity\PriceLog.
 */
class PriceLogAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bar_exchange\Entity\PriceLogInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished price log entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published price log entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit price log entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete price log entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add price log entities');
  }

}

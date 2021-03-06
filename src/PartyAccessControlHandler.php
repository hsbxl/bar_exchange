<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Party entity.
 *
 * @see \Drupal\bar_exchange\Entity\Party.
 */
class PartyAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bar_exchange\Entity\PartyInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished party entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published party entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit party entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete party entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add party entities');
  }

}

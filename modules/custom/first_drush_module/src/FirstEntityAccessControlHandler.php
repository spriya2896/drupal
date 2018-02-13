<?php

namespace Drupal\first_drush_module;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the First entity entity.
 *
 * @see \Drupal\first_drush_module\Entity\FirstEntity.
 */
class FirstEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\first_drush_module\Entity\FirstEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished first entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published first entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit first entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete first entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add first entity entities');
  }

}

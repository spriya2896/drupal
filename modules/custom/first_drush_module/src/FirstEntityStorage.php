<?php

namespace Drupal\first_drush_module;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\first_drush_module\Entity\FirstEntityInterface;

/**
 * Defines the storage handler class for First entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * First entity entities.
 *
 * @ingroup first_drush_module
 */
class FirstEntityStorage extends SqlContentEntityStorage implements FirstEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(FirstEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {first_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {first_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(FirstEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {first_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('first_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}

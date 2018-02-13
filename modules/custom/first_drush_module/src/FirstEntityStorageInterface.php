<?php

namespace Drupal\first_drush_module;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface FirstEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of First entity revision IDs for a specific First entity.
   *
   * @param \Drupal\first_drush_module\Entity\FirstEntityInterface $entity
   *   The First entity entity.
   *
   * @return int[]
   *   First entity revision IDs (in ascending order).
   */
  public function revisionIds(FirstEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as First entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   First entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\first_drush_module\Entity\FirstEntityInterface $entity
   *   The First entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FirstEntityInterface $entity);

  /**
   * Unsets the language for all First entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

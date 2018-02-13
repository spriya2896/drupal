<?php

namespace Drupal\first_drush_module\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining First entity entities.
 *
 * @ingroup first_drush_module
 */
interface FirstEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the First entity name.
   *
   * @return string
   *   Name of the First entity.
   */
  public function getName();

  /**
   * Sets the First entity name.
   *
   * @param string $name
   *   The First entity name.
   *
   * @return \Drupal\first_drush_module\Entity\FirstEntityInterface
   *   The called First entity entity.
   */
  public function setName($name);

  /**
   * Gets the First entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the First entity.
   */
  public function getCreatedTime();

  /**
   * Sets the First entity creation timestamp.
   *
   * @param int $timestamp
   *   The First entity creation timestamp.
   *
   * @return \Drupal\first_drush_module\Entity\FirstEntityInterface
   *   The called First entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the First entity published status indicator.
   *
   * Unpublished First entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the First entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a First entity.
   *
   * @param bool $published
   *   TRUE to set this First entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\first_drush_module\Entity\FirstEntityInterface
   *   The called First entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the First entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the First entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\first_drush_module\Entity\FirstEntityInterface
   *   The called First entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the First entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the First entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\first_drush_module\Entity\FirstEntityInterface
   *   The called First entity entity.
   */
  public function setRevisionUserId($uid);

}

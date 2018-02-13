<?php

namespace Drupal\first_drush_module\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for First entity entities.
 */
class FirstEntityViewsData extends EntityViewsData {

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

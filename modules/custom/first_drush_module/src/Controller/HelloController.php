<?php

namespace Drupal\first_drush_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HelloController.
 */
class HelloController extends ControllerBase {

  /**
   * Greet.
   *
   * @return string
   *   Return Hello string.
   */
  public function greet($world) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: greet with parameter(s): $world'),
    ];
  }

}

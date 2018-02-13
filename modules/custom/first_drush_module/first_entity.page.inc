<?php

/**
 * @file
 * Contains first_entity.page.inc.
 *
 * Page callback for First entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for First entity templates.
 *
 * Default template: first_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_first_entity(array &$variables) {
  // Fetch FirstEntity Entity Object.
  $first_entity = $variables['elements']['#first_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

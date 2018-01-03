<?php

/**
 * @file
 * Contains commodity.page.inc.
 *
 * Page callback for Commodity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Commodity templates.
 *
 * Default template: commodity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_commodity(array &$variables) {
  // Fetch Commodity Entity Object.
  $commodity = $variables['elements']['#commodity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

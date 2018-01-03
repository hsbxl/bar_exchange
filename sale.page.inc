<?php

/**
 * @file
 * Contains sale.page.inc.
 *
 * Page callback for Sale entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Sale templates.
 *
 * Default template: sale.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_sale(array &$variables) {
  // Fetch Sale Entity Object.
  $sale = $variables['elements']['#sale'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

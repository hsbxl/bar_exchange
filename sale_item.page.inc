<?php

/**
 * @file
 * Contains sale_item.page.inc.
 *
 * Page callback for Sale item entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Sale item templates.
 *
 * Default template: sale_item.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_sale_item(array &$variables) {
  // Fetch SaleItem Entity Object.
  $sale_item = $variables['elements']['#sale_item'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

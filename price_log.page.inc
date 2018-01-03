<?php

/**
 * @file
 * Contains price_log.page.inc.
 *
 * Page callback for Price log entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Price log templates.
 *
 * Default template: price_log.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_price_log(array &$variables) {
  // Fetch PriceLog Entity Object.
  $price_log = $variables['elements']['#price_log'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

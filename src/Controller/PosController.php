<?php
namespace Drupal\bar_exchange\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\bar_exchange\BarExchangeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the Example module.
 */
class PosController extends ControllerBase {

  public function __construct(BarExchangeService $barExchange) {
    $this->barexchange = $barExchange;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bar_exchange.bar_exchange')
    );
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function pos() {

    //ksm($this->barexchange->getCurrentParty());
    //ksm($this->barexchange->getCommodities());

    $order = [
      'items' => [
        '2' => 3,
        '3' => 4
      ],
    ];

    ksm($this->barexchange->newSale($order));

    $element = array(
      '#markup' => 'Hello, world',
    );
    return $element;
  }

}
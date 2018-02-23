<?php

namespace Drupal\bar_exchange\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bar_exchange\BarExchangeService;

/**
 * Class ChartsController.
 */
class ChartsController extends ControllerBase {

  /**
   * Drupal\bar_exchange\BarExchangeService definition.
   *
   * @var \Drupal\bar_exchange\BarExchangeService
   */
  protected $barExchangeBarExchange;

  /**
   * Constructs a new ChartsController object.
   */
  public function __construct(BarExchangeService $bar_exchange_bar_exchange) {
    $this->barExchange = $bar_exchange_bar_exchange;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bar_exchange.bar_exchange')
    );
  }

  /**
   * All.
   *
   * @return string
   *   Return Hello string.
   */
  public function all() {

    //ksm($this->barExchange->getLogs());

    return [
      '#type' => 'markup',
      '#markup' => '<div id="chart"></div>',
      '#attached' => array(
        'library' =>  array(
          'bar_exchange/charts',
          'bar_exchange/googlecharts',
        ),
      ),
    ];
  }
}

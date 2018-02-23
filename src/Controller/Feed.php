<?php


namespace Drupal\bar_exchange\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\bar_exchange\BarExchangeService;


class Feed extends ControllerBase implements ContainerInjectionInterface {

  public function __construct(BarExchangeService $barExchange) {
    $this->barexchange = $barExchange;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bar_exchange.bar_exchange')
    );
  }

  public function all() {

    $logs = $this->barexchange->getLogs();

    $feed = [];
    foreach ($logs as $log) {
      $feed[$log->commodity][$log->date] = $log->price;
    }

    $response = new Response();
    $response->setContent(json_encode([$feed]));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }
}
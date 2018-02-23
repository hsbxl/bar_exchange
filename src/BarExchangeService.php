<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityTypeManagerInterface;


class BarExchangeService {

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->config = \Drupal::config('bar_exchange.settings');
    $this->partyStorage = $this->entityTypeManager->getStorage('party');
    $this->commodityStorage = $this->entityTypeManager->getStorage('commodity');
    $this->saleStorage = $this->entityTypeManager->getStorage('sale');
    $this->saleitemStorage = $this->entityTypeManager->getStorage('sale_item');
    $this->pricelogStorage = $this->entityTypeManager->getStorage('price_log');
  }

  public function getCurrentParty() {
    $party_ids = $this->partyStorage->getQuery()
      ->condition('current', TRUE)
      ->execute();

    return current($this->partyStorage->loadMultiple($party_ids));
  }

  public function getCommodities() {
    if($this->getCurrentParty()) {
      return $this->getCurrentParty()->get('commodities')->referencedEntities();
    }
  }

  public function newSale($order) {
    // todo: first check if all products are available.
    foreach($order['items'] as $key => $item) {
      $available[$key] = $item;
    }

    $price = 0;
    foreach ($available as $commodity => $amount) {
      $sale_item = $this->newSaleItem([
        'commodity' => $commodity,
        'amount' => $amount
      ]);
      $sale_items[] = $sale_item;
      $price = $price + ($sale_item->amount->value * $sale_item->price_unit->value);
      $name[] = $sale_item->label();
    }

    $this->saleStorage->create([
      'name' => implode(', ', $name),
      'price' => $price,
      'party' => $this->getCurrentParty(),
      'sale_items' => $sale_items,
      //'date' => \Drupal::service('date.formatter')->format(DATETIME_STORAGE_TIMEZONE),
    ])->save();

    $this->priceTrigger($order);

    return 'foo bar';
  }

  public function newSaleItem($item) {
    $commodity = $this->commodityStorage->load($item['commodity']);

    $item['price_unit'] = $commodity->price_current->value;
    $item['name'] = $commodity->label();
    $sale_item = $this->saleitemStorage->create($item);
    $sale_item->save();

    return $sale_item;
  }


  public function priceTrigger($order) {
    $all = $this->getCommodities();
    foreach ($all as $item) {
      $allKeys[] = $item->ID();
    }

    foreach ($order['items'] as $amount) {
      $totalAmount += $amount;
    }

    $soldKeys = array_keys($order['items']);
    $nonSoldKeys = array_diff($allKeys, $soldKeys);

    $soldCommodities = entity_load_multiple('commodity', $soldKeys);
    $nonSoldCommodities = entity_load_multiple('commodity', $nonSoldKeys);

    foreach ($soldCommodities as $soldCommodity) {
      $max = $soldCommodity->get('price_max')->value;
      $amount = $order['items'][$soldCommodity->ID()];
      $price_current = $soldCommodity->get('price_current')->value;
      $price_new = $price_current + (($price_current / 100) * ($this->config->get('perc_sale_up') * $amount));
      $price_new = ($price_new > $max) ? $max : $price_new;
      $soldCommodity->set('price_current', $price_new)->save();
      $this->pricelog($soldCommodity, $price_new);
    }

    foreach ($nonSoldCommodities as $nonSoldCommodity) {
      $min = $nonSoldCommodity->get('price_min')->value;
      $price_current = $nonSoldCommodity->get('price_current')->value;
      $price_new = $price_current - (($price_current / 100) * ($this->config->get('perc_sale_down') * $totalAmount));
      $price_new = ($price_new < $min) ? $min : $price_new;
      $nonSoldCommodity->set('price_current', $price_new)->save();
      $this->pricelog($nonSoldCommodity, $price_new);
    }

    //$this->getLogs();

    //ksm($soldCommodities);
    //ksm($nonSoldCommodities);
  }

  public function pricelog($commodity, $price) {
    db_insert('bar_exchange_price_log')
      ->fields(
        array(
          'commodity' => $commodity->id(),
          'date' => time(),
          'price' => $price,
        )
      )->execute();
  }

  public function getLogs() {
    $query = \Drupal::database()->select('bar_exchange_price_log', 'pl');
    $query->addField('pl', 'commodity');
    $query->addField('pl', 'date');
    $query->addField('pl', 'price');
    return $query->execute()->fetchAll();
  }
}


<?php

namespace Drupal\bar_exchange;

use Drupal\Core\Entity\EntityTypeManagerInterface;


class BarExchangeService {

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->partyStorage = $this->entityTypeManager->getStorage('party');
    $this->commodityStorage = $this->entityTypeManager->getStorage('commodity');
    $this->saleStorage = $this->entityTypeManager->getStorage('sale');
    $this->saleitemStorage = $this->entityTypeManager->getStorage('sale_item');
  }

  public function getCurrentParty() {
    $party_ids = $this->partyStorage->getQuery()
      ->condition('current', TRUE)
      ->execute();

    return current($this->partyStorage->loadMultiple($party_ids));
  }

  public function getCommodities() {
    return $this->getCurrentParty()->get('commodities')->referencedEntities();
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

    //ksm(\Drupal::service('date.formatter')->format(DATETIME_STORAGE_TIMEZONE));

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


}


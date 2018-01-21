<?php

namespace Drupal\bar_exchange\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\bar_exchange\BarExchangeService;


class BarExchangePOS extends FormBase {
  protected $account;

  public function getFormId() {
    return 'bar_exchange_pos';
  }

  public function __construct(AccountInterface $account, BarExchangeService $barExchange) {
    $this->account = $account;
    $this->barexchange = $barExchange;
    $this->currentparty = $barExchange->getCurrentParty();
    $this->commodities = $barExchange->getCommodities();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('bar_exchange.bar_exchange')
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['current_party'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->currentparty->label(),
    ];


    $form['commodities'] = [
      '#type' => 'table',
      '#title' => $this->t('Commodities'),
      '#header' => [
        'id',
        $this->t('Drink'),
        $this->t('Price'),
      ],
    ];

    $i = 0;
    foreach($this->commodities as $commodity) {
      $form['commodities'][$i]['ID'] = [
        '#type' => 'hidden',
        '#default_value' => $commodity->id(),
      ];
      $form['commodities'][$i]['drink'] = [
        '#markup' => $commodity->label(),
      ];
      $form['commodities'][$i]['price'] = [
        '#markup' => '€' . $commodity->get('price_current')->value,
      ];
      $form['commodities'][$i]['amount'] = [
        '#type' => 'select',
        '#title' => t('Amount'),
        '#title_display' => 'invisible',
        '#options' => [0,1,2,3,4,5]
      ];
      $i++;
    }

    $form['total'] = [
      '#prefix' => '<div class="total">',
      '#markup' => '<strong>Total: </strong>€<span>0</span>',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Checkout'),
    ];

    $form['#attached']['library'][] = 'bar_exchange/POS';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach($form_state->getValue('commodities') as $commodity) {
      if($commodity['amount'] > 0) {
        $order['items'][$commodity['ID']] = $commodity['amount'];
        //$commodity = entity_load('commodity', $commodity['ID']);
        //ksm($amount, $commodity->label());
      }
    }

    $this->barexchange->newSale($order);
    ksm($order);
  }
}
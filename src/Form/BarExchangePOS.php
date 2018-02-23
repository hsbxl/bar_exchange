<?php

namespace Drupal\bar_exchange\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\bar_exchange\BarExchangeService;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Session\SessionManagerInterface;


class BarExchangePOS extends FormBase {
  protected $account;
  protected $tempStoreFactory;


  public function getFormId() {
    return 'bar_exchange_pos';
  }

  public function __construct(AccountInterface $account,
                              BarExchangeService $barExchange,
                              PrivateTempStoreFactory $temp_store_factory,
                              SessionManagerInterface $session_manager,
                              AccountInterface $current_user) {
    $this->account = $account;
    $this->barexchange = $barExchange;
    $this->tempStoreFactory = $temp_store_factory;
    $this->currentparty = $barExchange->getCurrentParty();
    $this->commodities = $barExchange->getCommodities();
    $this->store = $this->tempStoreFactory->get('multistep_data');
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('bar_exchange.bar_exchange'),
      $container->get('user.private_tempstore'),
      $container->get('session_manager'),
      $container->get('current_user')
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = true;
      $this->sessionManager->start();
    }

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

    $form['submitheader'] = [
      '#markup' => '<h4>Checkout</h4>',
    ];

    $form['submitcash'] = [
      '#type' => 'submit',
      '#value' => $this->t('Pay cash'),
      '#suffix' => '&nbsp;',
    ];
    $form['submitbarcode'] = [
      '#type' => 'submit',
      '#value' => $this->t('Pay with a barcode'),
    ];

    $form['#attached']['library'][] = 'bar_exchange/POS';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach($form_state->getValue('commodities') as $commodity) {
      if($commodity['amount'] > 0) {
        $order['items'][$commodity['ID']] = $commodity['amount'];
      }
    }

    switch($form_state->getTriggeringElement()['#id']) {
      case 'edit-submitcash':
        $this->barexchange->newSale($order);
        drupal_set_message('cash');
        break;
      case 'edit-submitbarcode':
        drupal_set_message('barcode');
        $form_state->setRedirect('bar_exchane.barcode');
        break;
    }
  }
}
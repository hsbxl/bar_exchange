<?php

namespace Drupal\bar_exchange\form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bar_exchange\BarExchangeService;


/**
 * Configure example settings for this site.
 */
class AdminForm extends ConfigFormBase {

  public function __construct(BarExchangeService $barExchange) {
    $this->barexchange = $barExchange;
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
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bar_exchange_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bar_exchange.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bar_exchange.settings');

    $form['numbers'] = array(
      '#type' => 'details',
      '#title' => $this->t('Numbers'),
      '#open' => TRUE,
    );

    $form['numbers']['perc_sale_up'] = [
      '#type' => 'number',
      '#title' => $this->t('Sale percentage up'),
      '#description' => $this->t('How many percentage a commodity price needs to rice per item sold?'),
      '#default_value' => $config->get('perc_sale_up') ? : 0,
    ];

    $form['numbers']['perc_sale_down'] = [
      '#type' => 'number',
      '#title' => $this->t('Sale percentage down'),
      '#description' => $this->t('How many percentage the other commodity prices needs to lower per item sold?'),
      '#default_value' => $config->get('perc_sale_down') ? : 0,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('bar_exchange.settings')
      ->set('perc_sale_up', $form_state->getValue('perc_sale_up'))
      ->set('perc_sale_down', $form_state->getValue('perc_sale_down'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}

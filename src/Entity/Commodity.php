<?php

namespace Drupal\bar_exchange\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Commodity entity.
 *
 * @ingroup bar_exchange
 *
 * @ContentEntityType(
 *   id = "commodity",
 *   label = @Translation("Commodity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bar_exchange\CommodityListBuilder",
 *     "views_data" = "Drupal\bar_exchange\Entity\CommodityViewsData",
 *     "translation" = "Drupal\bar_exchange\CommodityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\bar_exchange\Form\CommodityForm",
 *       "add" = "Drupal\bar_exchange\Form\CommodityForm",
 *       "edit" = "Drupal\bar_exchange\Form\CommodityForm",
 *       "delete" = "Drupal\bar_exchange\Form\CommodityDeleteForm",
 *     },
 *     "access" = "Drupal\bar_exchange\CommodityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bar_exchange\CommodityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "commodity",
 *   data_table = "commodity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer commodity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/bar_exchange/commodity/{commodity}",
 *     "add-form" = "/bar_exchange/commodity/add",
 *     "edit-form" = "/bar_exchange/commodity/{commodity}/edit",
 *     "delete-form" = "/bar_exchange/commodity/{commodity}/delete",
 *     "collection" = "/bar_exchange/commodity",
 *   },
 *   field_ui_base_route = "commodity.settings"
 * )
 */
class Commodity extends ContentEntityBase implements CommodityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Commodity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Commodity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Commodity is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));


    $fields['price_min'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Minimum price'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_max'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Maximum price'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_reg'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Regular price'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_current'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Current price'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_crash'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Crash price'))
      ->setDescription(t('How much revenue should this commodity generate before the price crashes to the minimum?'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['revenue_current'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Revenue current'))
      ->setDescription(t('The current revenue. Is set to 0 when price crashes.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['revenue_total'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Revenue total'))
      ->setDescription(t('The total revenue.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
        'prefix' => '€',
        'min' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}

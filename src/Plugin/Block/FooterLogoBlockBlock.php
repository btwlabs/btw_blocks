<?php

declare(strict_types=1);

namespace Drupal\btw_blocks\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a footer logo block block.
 */
#[Block(
  id: 'btw_blocks_footer_logo_block',
  admin_label: new TranslatableMarkup('Footer Logo Block'),
  category: new TranslatableMarkup('Custom'),
)]
final class FooterLogoBlockBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'logo_html' => $this->t('Hello world!'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['logo_html'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Logo HTML'),
      '#default_value' => $this->configuration['logo_html'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['logo_html'] = $form_state->getValue('logo_html');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->configuration['logo_html'],
    ];
    return $build;
  }

}

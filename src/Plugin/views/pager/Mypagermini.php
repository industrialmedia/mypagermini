<?php


namespace Drupal\mypagermini\Plugin\views\pager;


use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\pager\SqlBase;


/**
 * Views pager plugin.
 *
 * @ViewsPager(
 *  id = "mypagermini",
 *  title = @Translation("Mypagermini"),
 *  short_title = @Translation("Mypagermini"),
 *  help = @Translation("A views plugin which provides mypagermini."),
 *  theme = "mypagermini_pager"
 * )
 */
class Mypagermini extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function render($input) {
    $build['mypagermini'] = [
      '#theme' => $this->themeFunctions(),
      '#options' => $this->options['mypagermini'],
      '#element' => $this->options['id'],
      '#parameters' => $input,
    ];
    if ($this->options['mypagermini']['is_not_link']) {
      $build['mypagermini']['#attached']['library'][] = 'mypagermini/views-mypagermini';
    }
    return $build;
  }


  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();

    $options['tags']['contains']['previous']['default'] = '‹‹';
    $options['tags']['contains']['next']['default'] = '››';
    $options['mypagermini'] = [
      'contains' => [
        'pager_title' => [
          'default' => $this->t('Pager title'),
        ],
        'pager_subtitle' => [
          'default' => '@current_items / @total_items',
        ],
        'is_not_link' => [
          'default' => FALSE,
        ],
        'nofollow' => [
          'default' => TRUE,
        ],
      ],
    ];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $options = $this->options['mypagermini'];
    $form['mypagermini'] = [
      '#title' => $this->t('Mypagermini Options'),
      '#description' => $this->t('Note: The mypagermini option overrides.'),
      '#type' => 'details',
      '#open' => TRUE,
      '#tree' => TRUE,
      '#input' => TRUE,
      '#weight' => -100,
      'pager_title' => [
        '#type' => 'textfield',
        '#title' => $this->t('Pager title'),
        '#default_value' => $options['pager_title'],
        '#size' => 90,
        '#maxlength' => 255,
        '#description' => 'Токены для замены: @total_items - общее кол-во элементов, @current_items - текущие кол-во элементов, @current - номер текущей страницы',
      ],
      'pager_subtitle' => [
        '#type' => 'textfield',
        '#title' => $this->t('Pager sub title'),
        '#default_value' => $options['pager_subtitle'],
        '#size' => 90,
        '#maxlength' => 255,
        '#description' => 'Токены для замены: @total_items - общее кол-во элементов, @current_items - текущие кол-во элементов, @current - номер текущей страницы',
      ],
      'is_not_link' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Not links'),
        '#default_value' => $options['is_not_link'],
        '#description' => 'Вместо ссылок навигации будут div.link (обязательно нужно включить AJAX для этого представления)',
      ],
      'nofollow' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Nofollow'),
        '#default_value' => $options['nofollow'],
        '#description' => 'К ссылкам навигации будут добавлено - <em>rel="nofollow"</em>',
        '#states' => [
          'visible' => [
            ':input[name="pager_options[mypagermini][is_not_link]"]' => ['checked' => FALSE],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    if (!empty($this->options['offset'])) {
      return $this->formatPlural($this->options['items_per_page'], 'Mini pager, @count item, skip @skip', 'Mini pager, @count items, skip @skip', [
        '@count' => $this->options['items_per_page'],
        '@skip' => $this->options['offset']
      ]);
    }
    return $this->formatPlural($this->options['items_per_page'], 'Mini pager, @count item', 'Mini pager, @count items', ['@count' => $this->options['items_per_page']]);
  }


}

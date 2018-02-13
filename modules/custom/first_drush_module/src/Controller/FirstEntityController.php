<?php

namespace Drupal\first_drush_module\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\first_drush_module\Entity\FirstEntityInterface;

/**
 * Class FirstEntityController.
 *
 *  Returns responses for First entity routes.
 */
class FirstEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a First entity  revision.
   *
   * @param int $first_entity_revision
   *   The First entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($first_entity_revision) {
    $first_entity = $this->entityManager()->getStorage('first_entity')->loadRevision($first_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('first_entity');

    return $view_builder->view($first_entity);
  }

  /**
   * Page title callback for a First entity  revision.
   *
   * @param int $first_entity_revision
   *   The First entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($first_entity_revision) {
    $first_entity = $this->entityManager()->getStorage('first_entity')->loadRevision($first_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $first_entity->label(), '%date' => format_date($first_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a First entity .
   *
   * @param \Drupal\first_drush_module\Entity\FirstEntityInterface $first_entity
   *   A First entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FirstEntityInterface $first_entity) {
    $account = $this->currentUser();
    $langcode = $first_entity->language()->getId();
    $langname = $first_entity->language()->getName();
    $languages = $first_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $first_entity_storage = $this->entityManager()->getStorage('first_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $first_entity->label()]) : $this->t('Revisions for %title', ['%title' => $first_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all first entity revisions") || $account->hasPermission('administer first entity entities')));
    $delete_permission = (($account->hasPermission("delete all first entity revisions") || $account->hasPermission('administer first entity entities')));

    $rows = [];

    $vids = $first_entity_storage->revisionIds($first_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\first_drush_module\FirstEntityInterface $revision */
      $revision = $first_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $first_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.first_entity.revision', ['first_entity' => $first_entity->id(), 'first_entity_revision' => $vid]));
        }
        else {
          $link = $first_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.first_entity.translation_revert', ['first_entity' => $first_entity->id(), 'first_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.first_entity.revision_revert', ['first_entity' => $first_entity->id(), 'first_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.first_entity.revision_delete', ['first_entity' => $first_entity->id(), 'first_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['first_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}

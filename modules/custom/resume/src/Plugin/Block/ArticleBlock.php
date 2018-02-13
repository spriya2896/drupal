<?php

namespace Drupal\resume\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ArticleBlock' block.
 *
 * @Block(
 *  id = "article_block",
 *  admin_label = @Translation("Article block"),
 * )
 */
class ArticleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['article_block']['#markup'] = 'First custom block created by VIMAL';

    return $build;
  }

}

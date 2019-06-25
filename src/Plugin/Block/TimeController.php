<?php
namespace Drupal\omerblock\Plugin\Block;
use Drupal\block\Entity\Block;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\omerblock\Plugin\Block\Lib\EventTimeCalculator;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "omerblock",
 *   admin_label = @Translation("Omer Block"),
 * )
 */

class TimeController extends BlockBase
{
    /**
     * {@inheritdoc}
     */


    public function build() {

        $t = new EventTimeCalculator();
        $t->setEventInstance(\Drupal::routeMatch()->getParameter('node'));

        return [
            '#markup' => $t->printResult(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function blockAccess(AccountInterface $account) {
        return AccessResult::allowedIfHasPermission($account, 'access content');
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
        $config = $this->getConfiguration();

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->configuration['my_block_settings'] = $form_state->getValue('my_block_settings');
    }

    public function getCacheMaxAge()
    {
        // prevent block to be cached //
        return 0;
    }

}
<?php

/**
 * @file
 * Contains \Drupal\custom_example\Form\FirstForm.
 */

namespace Drupal\custom_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;

class FirstForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'firstform';
  }

  /**
   * {@inheritdoc}
   */

  /**
   * Passing Variable $username into JS.
   * Attached library to form element 
   * Check Email Validate using ajax DB
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // get user id 
    // list foreach 
//    $username = 'username@gmail.com';
//    $form['#attached']['library'][] = 'custom_example/firstlibraries';
//    $form['#attached']['drupalSettings']['custom_example']['name'] = $username;
//    $form['#tree'] = TRUE;

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => 'User or Email',
      '#required' => TRUE,
      '#description' => 'Please enter in a user or email',
    );


    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#title' => t('submit'),
      '#value' => $this->t('Submit'),
      '#ajax' => array(
        'callback' => '::display_email',
        'wrapper' => 'user-email-result',
        'event' => 'click',
        'progress' => array(
          'type' => 'throbber',
          'message' => NULL,
        ),
      ),
    );

    //$row[] = $this->get_data();

    $header = array('Id', 'Email');

    $db = \Drupal::database();
    $data = $db->select('email_example', 'l')
        ->fields('l', array('id', 'email'))
        ->execute()
        ->fetchAll();
    foreach ($data as $value) {
      $id = $value->id;
      $email = $value->email;

      $rows[] = array(
        'data' => array($id, $email));
    }

    if (!empty($rows)) {
      $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#prefix' => '<div id="user-email-result">',
        '#suffix' => '</div>',
      ];
    }

    $username = $email;
    $form['#attached']['library'][] = 'custom_example/firstlibraries';
    $form['#attached']['drupalSettings']['custom_example']['name'] = $username;
    $form['#tree'] = TRUE;
    return $form;
  }

  /**
   * this function is used to callback ajax 
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function display_email(array $form, FormStateInterface $form_state) {
    return $form['table'];
//    $ajax_response = new AjaxResponse();
//
//    // Check if User or email exists or not
//    if (user_load_by_name($form_state->getValue(user_email)) || user_load_by_mail($form_state->getValue(user_email))) {
//      $text = 'User or Email is exists';
//    }
//    else {
//      $text = 'User or Email does not exists';
//    }
//    $ajax_response->addCommand(new HtmlCommand('#user-email-result', $form[table]));
//    return array(
//      '#type' => 'ajax',
//      '#commands' => $ajax_response,
//    );
  }

  /**
   * this function is used to submit values in database
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValues()['email'];
    $field = array(
      'email' => $email,
    );
    db_insert('email_example')
        ->fields($field)
        ->execute();
  }

}

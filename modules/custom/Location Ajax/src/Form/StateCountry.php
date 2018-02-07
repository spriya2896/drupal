<?php

namespace Drupal\location_ajax\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StateCountry extends FormBase {

  public function getFormId() {
    return 'location';
  }

  /**
   *  
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $row[] = $this->get_data();
    $row1 = $this->get_location_country();
    
    $header = array('ID', 'Country', 'City');
    if (!empty($row)) {
      $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $row,
      ];
    }

    $country = array('none_country' => 'None', 'india' => 'India', 'pakistan' => 'Pakistan', 'srilanka' => 'Srilanka', 'bangladesh' => 'Bangladesh');
    $default_state = array('Maharashtra', 'Gujrat');
    $form['country'] = array(
      '#type' => 'select',
      '#title' => 'Country',
      '#options' => $row1,
      '#ajax' => array(
        'callback' => '::dependent_state',
        'event' => 'change',
        'wrapper' => 'state',
      ),
    );

     $submitted_values = $form_state->getValues();
    $selected_country = !empty($submitted_values['country']) ? $submitted_values['country'] : 'none_country';
     $row2 = $this->get_child_tid($selected_country);
//    if ($selected_country == 'none_country') {
//      $options = array('none_state' => 'None');
//    }
//    else if ($selected_country == 'india') {
//      $options = array('maharashtra' => 'Maharashtra', 'gujrat' => 'Gujrat');
//    }
//    else if ($selected_country == 'pakistan') {
//      $options = array('lahore' => 'Lahore', 'karachi' => 'Karachi');
//    }
//    else if ($selected_country == 'srilanka') {
//      $options = array('colombo' => 'Colombo', 'kandy' => 'Kandy');
//    }
//    else if ($selected_country == 'bangladesh') {
//      $options = array('dhaka' => 'Dhaka', 'chittagong' => 'Chittagong');
//    }


    $form['state'] = array(
      '#type' => 'select',
      '#title' => 'State',
      '#options' => $row2,
      '#prefix' => '<div id="state">',
      '#suffix' => '</div>',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

  /**
   * submit handler for form
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = $form_state->getValues();
    $country = $data['country'];
    $state = $data['state'];
    db_merge('location')
        ->insertFields(array(
          'country' => ucfirst($country),
          'state' => ucfirst($state),
        ))
        ->updateFields(array(
          'country' => ucfirst($country),
          'state' => ucfirst($state),
        ))
        ->key(array('id' => 1))
        ->execute();
    /* $data = $form_state->getValues();
      $connection = Database::getConnection();
      $connection ->insert('location')->fields(
      [
      'country' => $data['country'],
      'state' => $data['state'],
      ]
      )->execute() */;
  }
  

  /**
   * ajax callback function form id
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function dependent_state(array $form, FormStateInterface $form_state) {


    return $form['state'];
  }

  /**
   * 
   * @return type
   */
  public function get_data() {
    $db = \Drupal::database();
    $data = $db->select('location', 'l')
        ->fields('l')
        ->execute()
        ->fetchAssoc();
    return $data;
  }

  /**
   * 
   * @return type
   */
  public function get_location_country() {
    $db = \Drupal::database();
    $query = $db->select('taxonomy_term_field_data', 't');
    $query->join('taxonomy_term_hierarchy', 'n', 'n.tid = t.tid');
    $result = $query
        ->fields('t', array('tid', 'name'))
        ->condition('t.vid', 'location', '=')
        ->condition('n.parent', 'parent', '0')
        ->execute()
        ->fetchAllKeyed(0, 1);
    return $result;
  }

//  public function get_location_state() {
//    $db = \Drupal::database();
//    $query = $db->select('taxonomy_term_field_data', 't');
//    $query->join('taxonomy_term_hierarchy', 'n', 'n.tid = t.tid');
//    $result = $query
//        ->fields('t', array('tid', 'name'))
//        ->condition('t.vid', 'location', '=')
//        ->fields('n', array('parent'))
//        ->condition('n.parent', '0', '!=')
//        ->execute()
//        ->fetchAllKeyed(0, 1);
//    return $result;
//    return $form['state'];
//  }

  /**
   * function returns child terms
   * @param type $tid
   * @return type
   */
  function get_child_tid($tid) {
    $db = \Drupal::database();
    $query = $db->select('taxonomy_term_field_data', 't');
    $query->join('taxonomy_term_hierarchy', 'n', 'n.tid = t.tid');
    $result = $query
        ->fields('t', array('tid', 'name'))
        ->condition('n.parent', $tid, '=')
        ->execute()
        ->fetchAllKeyed(0, 1);
    return $result;
  }

}

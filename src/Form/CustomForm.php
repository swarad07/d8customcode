<?php
/**
 * @file
 * Contains \Drupal\swarad\Form\CustomForm.
 */

namespace Drupal\swarad\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 *  Implements the custom form at /swarad/custom-form.
 */
class CustomForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */  
  public function getFormId() {
    return 'custom_form_swarad';
  }

  /**
   * {@inheritdoc}
   */ 
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Enter Username'),
      '#description' => $this->t('Enter Username'),
      '#ajax' => array(
        // Function to call when the event is fired.
        'callback' => 'Drupal\swarad\Form\CustomForm::usernameCheck',
        // The JS event to trigger the above function.
        'event' => 'change',
        // Optional things.
        // Effect.
        'effect' => 'fade',
        // progress handling.
        'progress' => array(
          // Graphic shown to indicate progress
          'type' => 'bar',
          // Message
          'message' => 'Loading..' 
        ),
      ),
    );
    
    $form['actions']['#type'] = 'actions';
    $form['actions']['random_button'] = array(
      '#type' => 'button',
      '#value' => 'Random Username',
      '#ajax' => array(
        'callback' => 'Drupal\swarad\Form\CustomForm::randomUsername',
        'event' => 'click',
        'progress' => array(
          'type' => 'bar',
          'message' => 'Getting Random Username',
        ),
      ),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */ 
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // if (strlen($form_state->getValue('phone_number')) < 3) {
    //   $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
    // }
  }

  /**
   * {@inheritdoc}
   */ 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Your phone number is @number', array('@number' => $form_state->getValue('phone_number'))));
  }

  /**
   * {@inheritdoc}
   */ 
  public function usernameCheck(array &$form, FormStateInterface $form_state) {
    // We have to return AjaxResponse object.
    $AjaxResponse = new AjaxResponse();

    // Check if Username exists and is not Anonymous User (''). 
    if (user_load_by_name($form_state->getValue('username')) && $form_state->getValue('username') != false) {
      $text = 'User Found';
      $color = 'green';
    } 
    else {
      $text = 'No User Found';
      $color = 'red';
    }

    // Add a command to execute on form, jQuery .html() replaces content between tags.
    // In this case, we replace the desription with wheter the username was found or not.
    $AjaxResponse->addCommand(new HtmlCommand('#edit-username--description', $text));
  
    // CssCommand did not work.
    //$ajax_response->addCommand(new CssCommand('#edit-user-name--description', array('color', $color)));
    
    // Add a command, InvokeCommand, which allows for custom jQuery commands.
    // In this case, we alter the color of the description.
    $AjaxResponse->addCommand(new InvokeCommand('#edit-username--description', 'css', array('color', $color)));
    
    // Return the AjaxResponse Object.
    return $AjaxResponse;
  }

  /**
   * {@inheritdoc}
   */ 
  public function randomUsername(array &$form, FormStateInterface $form_state) {
    // We have to return AjaxResponse object.
    $AjaxResponse = new AjaxResponse();

    // Get all User Entities.
    $all_users = entity_load_multiple('user');
    
    // Remove Anonymous User.
    array_shift($all_users);
    
    // Pick Random User.
    $random_user = $all_users[array_rand($all_users)];

    // ValCommand does not exist, so we can use InvokeCommand.
    $AjaxResponse->addCommand(new InvokeCommand('#edit-username', 'val' , array($random_user->get('name')->getString())));
    
    // ChangedCommand did not work.
    //$ajax_response->addCommand(new ChangedCommand('#edit-user-name', '#edit-user-name'));
    
    // We can still invoke the change command on #edit-user-name so it triggers Ajax on that element to validate username.
    $AjaxResponse->addCommand(new InvokeCommand('#edit-username', 'change'));
    
    // Return the AjaxResponse Object.
    return $AjaxResponse;
  }
}

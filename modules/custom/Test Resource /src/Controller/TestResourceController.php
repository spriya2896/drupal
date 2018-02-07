<?php


namespace Drupal\test_resource\Controller;


use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Access\AccessResult;


/*
* Process Resource REST request.
*/

class TestResourceController {


/**
 * Allow access for logged-in, authenticated users.
 *
 * @param \Drupal\Core\Session\AccountInterface $account
 *   Run access checks for this account.
 *
 * @return bool
 *   Return true or false on the basis of criteria specified.
 */

public function access(AccountInterface $account) {
  return AccessResult::allowedIf($account->hasPermission('access_webservice'));

}


/**
 * Process REST request.
 */

public function create(Request $request) {
  
  return new JsonResponse('Return data', 200);
}

/**
 * Validate the POST data.
 */

private function validate(Request $request) {
  $error = '';
  // Material Id is mandatory.
  if ($request->request->get(metadata) == '') {
    $error = t('Invalid post data, metadata was missing.');
    return $error;
  }
     return TRUE;

}
}
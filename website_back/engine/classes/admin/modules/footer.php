<?php
class Admin_PageFooter {
  public static function getModel($request) {

    $user = AdminUser::getCurrentUser();

    return (object) array( "tpl" => "footer", "data" => (object) array( 'rights' => explode(':', $user->rights) ) );
  }
}

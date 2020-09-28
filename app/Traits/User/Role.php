<?php
namespace App\Traits\User;

trait Role
{
  public function getRole()
  {
    return $this->determineRole(get_class($this));
  }

  private function determineRole($class){
    switch ($class) {
      case 'App\\User':
        return 'user';
      case 'App\\Customer':
        return 'customer';
      default:
        return false;
    }
  }
}

?>

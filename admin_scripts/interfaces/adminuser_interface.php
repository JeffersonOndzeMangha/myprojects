<?php
/*Adminuser Interface, for signup, login, and managing website content.
Author: Jefferson Ondze Mangha
Date: 04/03/2018
*/

/**
 *
 */
  interface AdminUserInterface
  {
    public function SignUp(dbConnection_ $connection, $data);
    public function LogIn(dbConnection_ $connection, $data);
  }

?>

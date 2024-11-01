<?php

\spl_autoload_register(function ($class) {
  static $map = array (
  'Sukellos\\WP_Sukellos_Login_Style_Loader' => 'sukellos-login-style.php',
  'Sukellos\\WP_Sukellos_Login_Style' => 'class-wp-sukellos-login-style.php',
  'Sukellos\\Admin\\WP_Sukellos_Login_Style_Admin' => 'admin/class-wp-sukellos-login-style-admin.php',
  'Sukellos\\Login_Style_Manager' => 'includes/managers/class-login-style-manager.php',
);

  if (isset($map[$class])) {
    require_once __DIR__ . '/' . $map[$class];
  }
}, true, false);
<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMaps(array (
  'w' => 
  array (
    0 => '\\Map\\AuthenticationCodeTableMap',
    1 => '\\Map\\UserTableMap',
  ),
));

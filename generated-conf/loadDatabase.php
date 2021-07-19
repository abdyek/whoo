<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMaps(array (
  'whoo' => 
  array (
    0 => '\\Map\\AuthenticationCodeTableMap',
    1 => '\\Map\\MemberTableMap',
  ),
));

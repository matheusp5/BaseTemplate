<?php

require '../model/class/Render.class.php';

echo Renderization::renderClientTemplate('Home', 'Home');
$database = Renderization::renderDatabase('conf', null);
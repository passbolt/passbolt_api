<?php

$tree = $this->ApiDoc->generatePackageJsonTree($packageIndex);
echo json_encode($tree);
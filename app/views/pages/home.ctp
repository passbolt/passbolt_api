<?php
if(array_key_exists(0, $categories)) {
    echo $nestedtree->getCategories(0, $categories[0], $categories);
}
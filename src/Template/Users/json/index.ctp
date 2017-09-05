<?php

function formatLegacyEntity($entity, $name) {
    $result = [];
    foreach ($entity->visibleProperties() as $property) {
        $value = $entity->get($property);
        if (is_string($value) || is_bool($value) || is_numeric($value) || is_null($value)) {
            $result[$name][$property] = $value;
        }  elseif (is_object($value) && get_class($value) === 'Cake\I18n\FrozenTime')  {
            $result[$name][$property] = $value->toDateTimeString();
        } elseif(is_object($value) && get_parent_class($value) === 'Cake\ORM\Entity') {
            $name = ucfirst($property);
            $result[$name] = formatLegacyEntity($value, $name)[$name];
        } elseif(is_object($value) && get_class($value) === 'Cake\ORM\Entity') {
            $name = ucfirst($property);
            $result[$name] = formatLegacyEntity($value, $name)[$name];
        }
    }
    return $result;
}

function formatLegacySet($resultSet) {
    $i = 0;
    $results = [];
    foreach ($resultSet as $entity) {
        $name = substr(get_class($entity), strrpos(get_class($entity), '\\') + 1);
        $results[$i] = formatLegacyEntity($entity, $name);
        $i++;
    }
    return $results;
}

//pr($body); die;
$results = formatLegacySet($body);

echo json_encode(['header' => $header, 'body' => $results], $_jsonOptions);

<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class Entity implements \ArrayAccess {

    protected $errors = array(),
            $id;

    public function __construct(array $donnees = array()) {
        if (!empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    public function isNew() {
        return empty($this->id);
    }

    public function errors() {
        return $this->errors;
    }

    public function id() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $attribut => $valeur) {
            $methode = 'set' . ucfirst($attribut);

            if (is_callable(array($this, $methode))) {
                $this->$methode($valeur);
            }
        }
    }

    public function offsetGet($var) {
        if (isset($this->$var) && is_callable(array($this, $var))) {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value) {
        $method = 'set' . ucfirst($var);

        if (isset($this->$var) && is_callable(array($this, $method))) {
            $this->$method($value);
        }
    }

    public function offsetExists($var) {
        return isset($this->$var) && is_callable(array($this, $var));
    }

    public function offsetUnset($var) {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }

  /* TODO: Implement \JsonSerializable interface
   public function jsonSerialize() {
    $serializableArray = array();
    $reflectionPropertiesArray = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
    foreach($reflectionPropertiesArray as $property) {
      $propertyName= $property->name;
      $serializableArray[$propertyName] = $this->$propertyName;
    }
    return $serializableArray;
  }
   *
   */

}

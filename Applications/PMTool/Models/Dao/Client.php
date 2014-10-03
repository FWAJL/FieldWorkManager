<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Client extends \Library\Entity{  public     $client_id,    $client_name,    $client_address,    $client_contact_name,    $client_contact_phone,    $client_contact_email;
  const     CLIENT_ID_ERR = 0,    CLIENT_NAME_ERR = 1,    CLIENT_ADDRESS_ERR = 2,    CLIENT_CONTACT_NAME_ERR = 3,    CLIENT_CONTACT_PHONE_ERR = 4,    CLIENT_CONTACT_EMAIL_ERR = 5;
  // SETTERS //  public function setClient_id($client_id) {      $this->client_id = $client_id;  }
  public function setClient_name($client_name) {      $this->client_name = $client_name;  }
  public function setClient_address($client_address) {      $this->client_address = $client_address;  }
  public function setClient_contact_name($client_contact_name) {      $this->client_contact_name = $client_contact_name;  }
  public function setClient_contact_phone($client_contact_phone) {      $this->client_contact_phone = $client_contact_phone;  }
  public function setClient_contact_email($client_contact_email) {      $this->client_contact_email = $client_contact_email;  }
  // GETTERS //  public function client_id() {    return $this->client_id;  }
  public function client_name() {    return $this->client_name;  }
  public function client_address() {    return $this->client_address;  }
  public function client_contact_name() {    return $this->client_contact_name;  }
  public function client_contact_phone() {    return $this->client_contact_phone;  }
  public function client_contact_email() {    return $this->client_contact_email;  }
}
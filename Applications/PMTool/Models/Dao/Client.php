<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Client extends \Library\Entity{  public     $client_id,    $project_id,    $client_company_name,    $client_address,    $client_contact_name,    $client_contact_phone,    $client_contact_email;
  const     CLIENT_ID_ERR = 0,    PROJECT_ID_ERR = 1,    CLIENT_COMPANY_NAME_ERR = 2,    CLIENT_ADDRESS_ERR = 3,    CLIENT_CONTACT_NAME_ERR = 4,    CLIENT_CONTACT_PHONE_ERR = 5,    CLIENT_CONTACT_EMAIL_ERR = 6;
  // SETTERS //  public function setClient_id($client_id) {    if (empty($client_id)) {      $this->erreurs[] = self::CLIENT_ID_ERR;    } else {      $this->client_id = $client_id;    }  }
  public function setProject_id($project_id) {    if (empty($project_id)) {      $this->erreurs[] = self::PROJECT_ID_ERR;    } else {      $this->project_id = $project_id;    }  }
  public function setClient_company_name($client_company_name) {    if (empty($client_company_name)) {      $this->erreurs[] = self::CLIENT_COMPANY_NAME_ERR;    } else {      $this->client_company_name = $client_company_name;    }  }
  public function setClient_address($client_address) {    if (empty($client_address)) {      $this->erreurs[] = self::CLIENT_ADDRESS_ERR;    } else {      $this->client_address = $client_address;    }  }
  public function setClient_contact_name($client_contact_name) {    if (empty($client_contact_name)) {      $this->erreurs[] = self::CLIENT_CONTACT_NAME_ERR;    } else {      $this->client_contact_name = $client_contact_name;    }  }
  public function setClient_contact_phone($client_contact_phone) {    if (empty($client_contact_phone)) {      $this->erreurs[] = self::CLIENT_CONTACT_PHONE_ERR;    } else {      $this->client_contact_phone = $client_contact_phone;    }  }
  public function setClient_contact_email($client_contact_email) {    if (empty($client_contact_email)) {      $this->erreurs[] = self::CLIENT_CONTACT_EMAIL_ERR;    } else {      $this->client_contact_email = $client_contact_email;    }  }
  // GETTERS //  public function client_id() {    return $this->client_id;  }
  public function project_id() {    return $this->project_id;  }
  public function client_company_name() {    return $this->client_company_name;  }
  public function client_address() {    return $this->client_address;  }
  public function client_contact_name() {    return $this->client_contact_name;  }
  public function client_contact_phone() {    return $this->client_contact_phone;  }
  public function client_contact_email() {    return $this->client_contact_email;  }
}
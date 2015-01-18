<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Encryption Class
 *
 * @package     Library
 * @subpackage	BL\Core
 * @category	Encryption
 * @author      FWM DEV Team
 * @link		
 */

namespace Library\BL\Core;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Encryption {

  /**
   * Encrypt some data
   * 
   * @param string $public_key
   * @param object $data
   * @return string
   */
  public function Encrypt($public_key, $data) {
    return substr(sha1($data) . $public_key, 0, 50);
    //Below: mcrypt method (NOT USED FOR THE MOMENT)
    //    $args = $this->InitEncryption($public_key);
    //    $enc = $this->mcrypt_encode($args, $data, $public_key);
    //    return base64_encode($enc);
  }

  /**
   * Encrypt using Mcrypt
   *
   * @access	public
   * @param	string
   * @param	string
   * @return	string
   */
  function mcrypt_encode($args, $data, $key) {
    $init_size = mcrypt_get_iv_size($args["cipher"], $args["mode"]);
    $init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
    return $this->_add_cipher_noise($init_vect . mcrypt_encrypt($args["cipher"], $key, $data, $args["mode"], $init_vect), $key);
  }

  /**
   * Decrypt some data with mcrypt (NOT USED FOR THE MOMENT)
   * 
   * @param string $public_key
   * @param object $data
   * @return string
   */
  public function Decrypt($public_key, $data) {
  //    $args = $this->InitEncryption($public_key);
  //
  //    $data = $this->_remove_cipher_noise($data, $public_key);
  //    $init_size = mcrypt_get_iv_size($args["cipher"], $args["mode"]);
  //
  //    if ($init_size > strlen($data)) {
  //      return FALSE;
  //    }
  //
  //    $init_vect = substr($data, 0, $init_size);
  //    $data = substr($data, $init_size);
  //    return rtrim(mcrypt_decrypt($args["cipher"], $public_key, $data, $args["mode"], $init_vect), "\0");
  }

  /**
   * Initialize the values to encrypt
   * 
   * @param string $public_key
   * @return array
   */
  private function InitEncryption($public_key) {
    //generate the private key
    $args = array(
      "cipher" => "blowfish",
      "mode" => "cbc",
      "public_key" => $public_key,
      "private_key" => ""
    );
    //Update args["private_key"] value
    $args = $this->GeneratePrivateKey($args);
    //Create iv
    return $args;
  }

  /**
   * Generate the private key to use for encryption/decryption
   * 
   * @param array $params
   * @return array $params
   */
  private function GeneratePrivateKey($params) {
    // Max size for the key by chosen algorhythm
    $size = mcrypt_module_get_algo_key_size($params["cipher"]);

    // reduce key to max. size supported
    $params["private_key"] = substr($params["public_key"], 0, $size);

    return $params;
  }
  /**
   * Removes permuted noise from the IV + encrypted data, reversing
   * _add_cipher_noise()
   *
   * Function description
   *
   * @access	public
   * @param	type
   * @return	type
   */
  function _remove_cipher_noise($data, $key) {
    $keyhash = sha1($key);
    $keylen = strlen($keyhash);
    $str = '';

    for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j) {
      if ($j >= $keylen) {
        $j = 0;
      }

      $temp = ord($data[$i]) - ord($keyhash[$j]);

      if ($temp < 0) {
        $temp = $temp + 256;
      }

      $str .= chr($temp);
    }

    return $str;
  }

  /**
   * Adds permuted noise to the IV + encrypted data to protect
   * against Man-in-the-middle attacks on CBC mode ciphers
   * http://www.ciphersbyritter.com/GLOSSARY.HTM#IV
   *
   * Function description
   *
   * @access	private
   * @param	string
   * @param	string
   * @return	string
   */
  function _add_cipher_noise($data, $key) {
    $keyhash = sha1($key);
    $keylen = strlen($keyhash);
    $str = '';

    for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j) {
      if ($j >= $keylen) {
        $j = 0;
      }

      $str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
    }

    return $str;
  }

}

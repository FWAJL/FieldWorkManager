<?php
/**
 *
 * @package		Basic MVC framework
 * @author		FWA DEV Team
 * @copyright	Copyright (c) 2014
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
 * @author      FWA Dev Team
 * @link		
 */

namespace Library\BL\Core;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Encryption {
    /**
     * Encrypt some data
     * 
     * @param string $public_key
     * @param object $data
     * @return string
     */
    public function Encrypt($public_key, $data) {
        $args = $this->InitEncryption($public_key);        
        //Encrypt the data after serializing it (usefull for arrays)
        $data_encrypted = mcrypt_encrypt($args["algo"], $args["private_key"], serialize($data), $args["mode"], $iv);
        //Encode the encrypted data (usefull when storing it in a db
        return trim(base64_encode($data_encrypted));
    }
        /**
     * Decrypt some data
     * 
     * @param string $public_key
     * @param object $data
     * @return string
     */
    public function Decrypt($public_key, $data) {
        $args = $this->InitEncryption($public_key);        
        //Encrypt the data after serializing it (usefull for arrays)
        $data_encrypted = mcrypt_decrypt($args["algo"], $args["private_key"], base64_decode($data), $args["mode"], $iv);
        //Encode the encrypted data (usefull when storing it in a db
        return unserialize($data_encrypted);
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
          "algo" => "blowfish",
          "mode" => "cbc",
          "public_key" => $public_key,
          "private_key" => "",
          "iv" => ""
        );
        //Update args["private_key"] value
        $args = $this->GeneratePrivateKey($args);
        //Create iv
        $args["iv"] = $this->MakeIv($args);
        
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
        $size = mcrypt_module_get_algo_key_size($params["algo"]);

        // reduce key to max. size supported
        $params["private_key"] = substr($params["public_key"], 0, $size);

        return $params;
    }
    
    /**
     * What is IV?
     * 
     * @param array $params
     * @return string
     */
    private function MakeIv($params) {
        $source = ""; // can be empty, dev_random, dev_urandom
        switch($source) {
                case "dev_random":
                        // on créé un vecteur d'initialisation selon la taille maximale possible
                        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algo, $mode), MCRYPT_DEV_RANDOM);
                break;
                case "dev_urandom":
                        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algo, $mode), MCRYPT_DEV_URANDOM);
                break;
                default:
                        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algo, $mode), MCRYPT_RAND);
                break;
        }

        return $iv;
    }
}

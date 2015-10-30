<?php

if(function_exists('mcrypt_encrypt') && defined('MCRYPT_MODE_CFB')) {
  class MCryptWrapper {
    public $cipher, $key, $iv, $key_size, $block_size;


    function __construct($cipher) {
      $this->cipher = $cipher;
      $this->key_size = mcrypt_module_get_algo_key_size($cipher);
      $this->block_size = mcrypt_module_get_algo_block_size($cipher);
      $this->iv = str_repeat("\0", mcrypt_get_iv_size($cipher, 'ncfb'));
    }

    function setKey($key) {
      $this->key = $key;
    }

    function setIV($iv) {
      $this->iv = $iv;
    }

    function encrypt($data) {
      return mcrypt_encrypt($this->cipher, $this->key, $data, 'ncfb', $this->iv);
    }

    function decrypt($data) {
      return mcrypt_decrypt($this->cipher, $this->key, $data, 'ncfb', $this->iv);
    }
  }
}

<?php
// This is free and unencumbered software released into the public domain.
/**
 * OpenPGP.php is a pure-PHP implementation of the OpenPGP Message Format
 * (RFC 4880).
 *
 * @package OpenPGP
 * @version 0.0.1
 * @author  Arto Bendiken <arto.bendiken@gmail.com>
 * @author  Stephen Paul Weber <singpolyma@singpolyma.net>
 * @see     http://github.com/bendiken/openpgp-php
 */

//////////////////////////////////////////////////////////////////////////////
// OpenPGP utilities

/**
 * @see http://tools.ietf.org/html/rfc4880
 */
class OpenPGP {
  /**
   * @see http://tools.ietf.org/html/rfc4880#section-6
   * @see http://tools.ietf.org/html/rfc4880#section-6.2
   * @see http://tools.ietf.org/html/rfc2045
   */
  static function enarmor($data, $marker = 'MESSAGE', array $headers = array()) {
    $text = self::header($marker) . "\n";
    foreach ($headers as $key => $value) {
      $text .= $key . ': ' . (string)$value . "\n";
    }
    $text .= "\n" . base64_encode($data);
    $text .= "\n".'=' . base64_encode(substr(pack('N', self::crc24($data)), 1)) . "\n";
    $text .= self::footer($marker) . "\n";
    return $text;
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-6
   * @see http://tools.ietf.org/html/rfc2045
   */
  static function unarmor($text, $header = 'PGP PUBLIC KEY BLOCK') {
    $header = self::header($header);
    $text = str_replace(array("\r\n", "\r"), array("\n", ''), $text);
    if (($pos1 = strpos($text, $header)) !== FALSE &&
        ($pos1 = strpos($text, "\n\n", $pos1 += strlen($header))) !== FALSE &&
        ($pos2 = strpos($text, "\n=", $pos1 += 2)) !== FALSE) {
      return base64_decode($text = substr($text, $pos1, $pos2 - $pos1));
    }
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-6.2
   */
  static function header($marker) {
    return '-----BEGIN ' . strtoupper((string)$marker) . '-----';
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-6.2
   */
  static function footer($marker) {
    return '-----END ' . strtoupper((string)$marker) . '-----';
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-6
   * @see http://tools.ietf.org/html/rfc4880#section-6.1
   */
  static function crc24($data) {
    $crc = 0x00b704ce;
    for ($i = 0; $i < strlen($data); $i++) {
      $crc ^= (ord($data[$i]) & 255) << 16;
      for ($j = 0; $j < 8; $j++) {
        $crc <<= 1;
        if ($crc & 0x01000000) {
          $crc ^= 0x01864cfb;
        }
      }
    }
    return $crc & 0x00ffffff;
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-12.2
   */
  static function bitlength($data) {
    return (strlen($data) - 1) * 8 + (int)floor(log(ord($data[0]), 2)) + 1;
  }

  static function decode_s2k_count($c) {
    return ((int)16 + ($c & 15)) << (($c >> 4) + 6);
  }

  static function encode_s2k_count($iterations) {
    if($iterations >= 65011712) return 255;

    $count = $iterations >> 6;
    $c = 0;
    while($count >= 32) {
      $count = $count >> 1;
      $c++;
    }
    $result = ($c << 4) | ($count - 16);

    if(OpenPGP::decode_s2k_count($result) < $iterations) {
      return $result + 1;
    }

    return $result;
  }
}

class OpenPGP_S2K {
  public $type, $hash_algorithm, $salt, $count;

  function __construct($salt='BADSALT', $hash_algorithm=10, $count=65536, $type=3) {
    $this->type = $type;
    $this->hash_algorithm = $hash_algorithm;
    $this->salt = $salt;
    $this->count = $count;
  }

  static function parse(&$input) {
    $s2k = new OpenPGP_S2k();
    switch($s2k->type = ord($input{0})) {
      case 0:
        $s2k->hash_algorithm = ord($input{1});
        $input = substr($input, 2);
        break;
      case 1:
        $s2k->hash_algorithm = ord($input{1});
        $s2k->salt = substr($input, 2, 8);
        $input = substr($input, 10);
        break;
      case 3:
        $s2k->hash_algorithm = ord($input{1});
        $s2k->salt = substr($input, 2, 8);
        $s2k->count = OpenPGP::decode_s2k_count(ord($input{10}));
        $input = substr($input, 11);
        break;
    }

    return $s2k;
  }

  function to_bytes() {
    $bytes = chr($this->type);
    switch($this->type) {
      case 0:
        $bytes .= chr($this->hash_algorithm);
        break;
      case 1:
        $bytes .= chr($this->hash_algorithm);
        $bytes .= $this->salt;
        break;
      case 3:
        $bytes .= chr($this->hash_algorithm);
        $bytes .= $this->salt;
        $bytes .= chr(OpenPGP::encode_s2k_count($this->count));
        break;
    }
    return $bytes;
  }

  function raw_hash($s) {
    return hash(strtolower(OpenPGP_SignaturePacket::$hash_algorithms[$this->hash_algorithm]), $s, true);
  }

  function sized_hash($s, $size) {
    $hash = $this->raw_hash($s);
    while(strlen($hash) < $size) {
      $s = "\0" . $s;
      $hash .= $this->raw_hash($s);
    }

    return substr($hash, 0, $size);
  }

  function iterate($s) {
    if(strlen($s) >= $this->count) return $s;
    $s = str_repeat($s, ceil($this->count / strlen($s)));
    return substr($s, 0, $this->count);
  }

  function make_key($pass, $size) {
    switch($this->type) {
      case 0:
        return $this->sized_hash($pass, $size);
      case 1:
        return $this->sized_hash($this->salt . $pass, $size);
      case 3:
        return $this->sized_hash($this->iterate($this->salt . $pass), $size);
    }
  }
}

//////////////////////////////////////////////////////////////////////////////
// OpenPGP messages

/**
 * @see http://tools.ietf.org/html/rfc4880#section-4.1
 * @see http://tools.ietf.org/html/rfc4880#section-11
 * @see http://tools.ietf.org/html/rfc4880#section-11.3
 */
class OpenPGP_Message implements IteratorAggregate, ArrayAccess {
  public $uri = NULL;
  public $packets = array();

  static function parse_file($path) {
    if (($msg = self::parse(file_get_contents($path)))) {
      $msg->uri = preg_match('!^[\w\d]+://!', $path) ? $path : 'file://' . realpath($path);
      return $msg;
    }
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-4.1
   * @see http://tools.ietf.org/html/rfc4880#section-4.2
   */
  static function parse($input) {
    if (is_resource($input)) {
      return self::parse_stream($input);
    }
    if (is_string($input)) {
      return self::parse_string($input);
    }
  }

  static function parse_stream($input) {
    return self::parse_string(stream_get_contents($input));
  }

  static function parse_string($input) {
    $msg = new self;
    while (($length = strlen($input)) > 0) {
      if (($packet = OpenPGP_Packet::parse($input))) {
        $msg[] = $packet;
      }
      if ($length == strlen($input)) { // is parsing stuck?
        break;
      }
    }
    return $msg;
  }

  function __construct(array $packets = array()) {
    $this->packets = $packets;
  }

  function to_bytes() {
    $bytes = '';
    foreach($this as $p) {
      $bytes .= $p->to_bytes();
    }
    return $bytes;
  }

  /**
   * Extract signed objects from a well-formatted message
   *
   * Recurses into CompressedDataPacket
   *
   * @see http://tools.ietf.org/html/rfc4880#section-11
   */
  function signatures() {
    $msg = $this;
    while($msg[0] instanceof OpenPGP_CompressedDataPacket) $msg = $msg[0]->data;

    $key = NULL;
    $userid = NULL;
    $subkey = NULL;
    $sigs = array();
    $final_sigs = array();

    foreach($msg as $idx => $p) {
      if($p instanceof OpenPGP_LiteralDataPacket) {
        return array(array($p, array_values(array_filter($msg->packets, function($p) {
          return $p instanceof OpenPGP_SignaturePacket;
        }))));
      } else if($p instanceof OpenPGP_PublicSubkeyPacket || $p instanceof OpenPGP_SecretSubkeyPacket) {
        if($userid) {
          array_push($final_sigs, array($key, $userid, $sigs));
          $userid = NULL;
        } else if($subkey) {
          array_push($final_sigs, array($key, $subkey, $sigs));
          $key = NULL;
        }
        $sigs = array();
        $subkey = $p;
      } else if($p instanceof OpenPGP_PublicKeyPacket) {
        if($userid) {
          array_push($final_sigs, array($key, $userid, $sigs));
          $userid = NULL;
        } else if($subkey) {
          array_push($final_sigs, array($key, $subkey, $sigs));
          $subkey = NULL;
        } else if($key) {
          array_push($final_sigs, array($key, $sigs));
          $key = NULL;
        }
        $sigs = array();
        $key = $p;
      } else if($p instanceof OpenPGP_UserIDPacket) {
        if($userid) {
          array_push($final_sigs, array($key, $userid, $sigs));
          $userid = NULL;
        } else if($key) {
          array_push($final_sigs, array($key, $sigs));
        }
        $sigs = array();
        $userid = $p;
      } else if($p instanceof OpenPGP_SignaturePacket) {
        $sigs[] = $p;
      }
    }

    if($userid) {
      array_push($final_sigs, array($key, $userid, $sigs));
    } else if($subkey) {
      array_push($final_sigs, array($key, $subkey, $sigs));
    } else if($key) {
      array_push($final_sigs, array($key, $sigs));
    }

    return $final_sigs;
  }

  /**
   * Function to extract verified signatures
   * $verifiers is an array of callbacks formatted like array('RSA' => array('SHA256' => CALLBACK)) that take two parameters: raw message and signature packet
   */
  function verified_signatures($verifiers) {
    $signed = $this->signatures();
    $vsigned = array();

    foreach($signed as $sign) {
      $signatures = array_pop($sign);
      $vsigs = array();

      foreach($signatures as $sig) {
        $verifier = $verifiers[$sig->key_algorithm_name()][$sig->hash_algorithm_name()];
        if($verifier && $this->verify_one($verifier, $sign, $sig)) {
          $vsigs[] = $sig;
        }
      }
      array_push($sign, $vsigs);
      $vsigned[] = $sign;
    }

    return $vsigned;
  }

  function verify_one($verifier, $sign, $sig) {
    if($sign[0] instanceof OpenPGP_LiteralDataPacket) {
      $sign[0]->normalize();
      $raw = $sign[0]->data;
    } else if(isset($sign[1]) && $sign[1] instanceof OpenPGP_UserIDPacket) {
      $raw = implode('', array_merge($sign[0]->fingerprint_material(), array(chr(0xB4),
        pack('N', strlen($sign[1]->body())), $sign[1]->body())));
    } else if(isset($sign[1]) && ($sign[1] instanceof OpenPGP_PublicSubkeyPacket || $sign[1] instanceof OpenPGP_SecretSubkeyPacket)) {
      $raw = implode('', array_merge($sign[0]->fingerprint_material(), $sign[1]->fingerprint_material()));
    } else if($sign[0] instanceof OpenPGP_PublicKeyPacket) {
      $raw = implode('', $sign[0]->fingerprint_material());
    } else {
      return NULL;
    }
    return call_user_func($verifier, $raw.$sig->trailer, $sig);
  }

  // IteratorAggregate interface

  function getIterator() {
    return new ArrayIterator($this->packets);
  }

  // ArrayAccess interface

  function offsetExists($offset) {
    return isset($this->packets[$offset]);
  }

  function offsetGet($offset) {
    return $this->packets[$offset];
  }

  function offsetSet($offset, $value) {
    return is_null($offset) ? $this->packets[] = $value : $this->packets[$offset] = $value;
  }

  function offsetUnset($offset) {
    unset($this->packets[$offset]);
  }
}

//////////////////////////////////////////////////////////////////////////////
// OpenPGP packets

/**
 * OpenPGP packet.
 *
 * @see http://tools.ietf.org/html/rfc4880#section-4.1
 * @see http://tools.ietf.org/html/rfc4880#section-4.3
 */
class OpenPGP_Packet {
  public $tag, $size, $data;

  static function class_for($tag) {
    return isset(self::$tags[$tag]) && class_exists(
      $class = 'OpenPGP_' . self::$tags[$tag] . 'Packet') ? $class : __CLASS__;
  }

  /**
   * Parses an OpenPGP packet.
   * 
   * Partial body lengths based on https://github.com/toofishes/python-pgpdump/blob/master/pgpdump/packet.py
   *
   * @see http://tools.ietf.org/html/rfc4880#section-4.2
   */
  static function parse(&$input) {
    $packet = NULL;
    if (strlen($input) > 0) {
      $parser = ord($input[0]) & 64 ? 'parse_new_format' : 'parse_old_format';

      $header_start0 = 0;
      $consumed = 0;
      $packet_data = "";
      do {
          list($tag, $data_offset, $data_length, $partial) = self::$parser($input, $header_start0);

          $data_start0 = $header_start0 + $data_offset;
          $header_start0 = $data_start0 + $data_length - 1;
          $packet_data .= substr($input, $data_start0, $data_length);

          $consumed += $data_offset + $data_length;
          if ($partial) {
              $consumed -= 1;
          }
      } while ($partial === true && $parser === 'parse_new_format');

      if ($tag && ($class = self::class_for($tag))) {
        $packet = new $class();
        $packet->tag    = $tag;
        $packet->input  = $packet_data;
        $packet->length = strlen($packet_data);
        $packet->read();
        unset($packet->input);
        unset($packet->length);
      }
      $input = substr($input, $consumed);
    }
    return $packet;
  }

  /**
   * Parses a new-format (RFC 4880) OpenPGP packet.
   *
   * @see http://tools.ietf.org/html/rfc4880#section-4.2.2
   */
  static function parse_new_format($input, $header_start = 0) {
    $tag = ord($input[0]) & 63;
    $len = ord($input[$header_start + 1]);
    if($len < 192) { // One octet length
      return array($tag, 2, $len, false);
    }
    if($len > 191 && $len < 224) { // Two octet length
      return array($tag, 3, (($len - 192) << 8) + ord($input[$header_start + 2]) + 192, false);
    }
    if($len == 255) { // Five octet length
      $unpacked = unpack('N', substr($input, $header_start + 2, 4));
      return array($tag, 6, reset($unpacked), false);
    }
    // Partial body lengths
    return array($tag, 2, 1 << ($len & 0x1f), true);
  }

  /**
   * Parses an old-format (PGP 2.6.x) OpenPGP packet.
   *
   * @see http://tools.ietf.org/html/rfc4880#section-4.2.1
   */
  static function parse_old_format($input) {
    $len = ($tag = ord($input[0])) & 3;
    $tag = ($tag >> 2) & 15;
    switch ($len) {
      case 0: // The packet has a one-octet length. The header is 2 octets long.
        $head_length = 2;
        $data_length = ord($input[1]);
        break;
      case 1: // The packet has a two-octet length. The header is 3 octets long.
        $head_length = 3;
        $data_length = unpack('n', substr($input, 1, 2));
        $data_length = $data_length[1];
        break;
      case 2: // The packet has a four-octet length. The header is 5 octets long.
        $head_length = 5;
        $data_length = unpack('N', substr($input, 1, 4));
        $data_length = $data_length[1];
        break;
      case 3: // The packet is of indeterminate length. The header is 1 octet long.
        $head_length = 1;
        $data_length = strlen($input) - $head_length;
        break;
    }
    return array($tag, $head_length, $data_length, false);
  }

  function __construct($data=NULL) {
    $this->tag = array_search(substr(substr(get_class($this), 8), 0, -6), self::$tags);
    $this->data = $data;
  }

  function read() {
  }

  function body() {
    return $this->data; // Will normally be overridden by subclasses
  }

  function header_and_body() {
    $body = $this->body(); // Get body first, we will need it's length
    $tag = chr($this->tag | 0xC0); // First two bits are 1 for new packet format
    $size = chr(255).pack('N', strlen($body)); // Use 5-octet lengths
    return array('header' => $tag.$size, 'body' => $body);
  }

  function to_bytes() {
    $data = $this->header_and_body();
    return $data['header'].$data['body'];
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-3.5
   */
  function read_timestamp() {
    return $this->read_unpacked(4, 'N');
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-3.2
   */
  function read_mpi() {
    $length = $this->read_unpacked(2, 'n');  // length in bits
    $length = (int)floor(($length + 7) / 8); // length in bytes
    return $this->read_bytes($length);
  }

  /**
   * @see http://php.net/manual/en/function.unpack.php
   */
  function read_unpacked($count, $format) {
    $unpacked = unpack($format, $this->read_bytes($count));
    return reset($unpacked);
  }

  function read_byte() {
    return ($bytes = $this->read_bytes()) ? $bytes[0] : NULL;
  }

  function read_bytes($count = 1) {
    $bytes = substr($this->input, 0, $count);
    $this->input = substr($this->input, $count);
    return $bytes;
  }

  static $tags = array(
     1 => 'AsymmetricSessionKey',      // Public-Key Encrypted Session Key
     2 => 'Signature',                 // Signature Packet
     3 => 'SymmetricSessionKey',       // Symmetric-Key Encrypted Session Key Packet
     4 => 'OnePassSignature',          // One-Pass Signature Packet
     5 => 'SecretKey',                 // Secret-Key Packet
     6 => 'PublicKey',                 // Public-Key Packet
     7 => 'SecretSubkey',              // Secret-Subkey Packet
     8 => 'CompressedData',            // Compressed Data Packet
     9 => 'EncryptedData',             // Symmetrically Encrypted Data Packet
    10 => 'Marker',                    // Marker Packet
    11 => 'LiteralData',               // Literal Data Packet
    12 => 'Trust',                     // Trust Packet
    13 => 'UserID',                    // User ID Packet
    14 => 'PublicSubkey',              // Public-Subkey Packet
    17 => 'UserAttribute',             // User Attribute Packet
    18 => 'IntegrityProtectedData',    // Sym. Encrypted and Integrity Protected Data Packet
    19 => 'ModificationDetectionCode', // Modification Detection Code Packet
    60 => 'Experimental',              // Private or Experimental Values
    61 => 'Experimental',              // Private or Experimental Values
    62 => 'Experimental',              // Private or Experimental Values
    63 => 'Experimental',              // Private or Experimental Values
  );
}

/**
 * OpenPGP Public-Key Encrypted Session Key packet (tag 1).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.1
 */
class OpenPGP_AsymmetricSessionKeyPacket extends OpenPGP_Packet {
  public $version, $keyid, $key_algorithm, $encrypted_data;

  function __construct($key_algorithm='', $keyid='', $encrypted_data='', $version=3) {
    parent::__construct();
    $this->version = $version;
    $this->keyid = substr($keyid, -16);
    $this->key_algorithm = $key_algorithm;
    $this->encrypted_data = $encrypted_data;
  }

  function read() {
    switch($this->version = ord($this->read_byte())) {
      case 3:
        $rawkeyid = $this->read_bytes(8);
        $this->keyid = '';
        for($i = 0; $i < strlen($rawkeyid); $i++) { // Store KeyID in Hex
          $this->keyid .= sprintf('%02X',ord($rawkeyid{$i}));
        }

        $this->key_algorithm = ord($this->read_byte());

        $this->encrypted_data = $this->input;
        break;
      default:
        throw new Exception("Unsupported AsymmetricSessionKeyPacket version: " . $this->version);
    }
  }

  function body() {
    $bytes = chr($this->version);

    for($i = 0; $i < strlen($this->keyid); $i += 2) {
      $bytes .= chr(hexdec($this->keyid{$i}.$this->keyid{$i+1}));
    }

    $bytes .= chr($this->key_algorithm);
    $bytes .= $this->encrypted_data;
    return $bytes;
  }
}

/**
 * OpenPGP Signature packet (tag 2).
 * Be sure to NULL the trailer if you update a signature packet!
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.2
 */
class OpenPGP_SignaturePacket extends OpenPGP_Packet {
  public $version, $signature_type, $hash_algorithm, $key_algorithm, $hashed_subpackets, $unhashed_subpackets, $hash_head;
  public $trailer; // This is the literal bytes that get tacked on the end of the message when verifying the signature

  function __construct($data=NULL, $key_algorithm=NULL, $hash_algorithm=NULL) {
    parent::__construct();
    $this->version = 4; // Default to version 4 sigs
    if(is_string($this->hash_algorithm = $hash_algorithm)) {
      $this->hash_algorithm = array_search($this->hash_algorithm, self::$hash_algorithms);
    }
    if(is_string($this->key_algorithm = $key_algorithm)) {
      $this->key_algorithm = array_search($this->key_algorithm, OpenPGP_PublicKeyPacket::$algorithms);
    }
    if($data) { // If we have any data, set up the creation time
      $this->hashed_subpackets = array(new OpenPGP_SignaturePacket_SignatureCreationTimePacket(time()));
    }
    if($data instanceof OpenPGP_LiteralDataPacket) {
      $this->signature_type = ($data->format == 'b') ? 0x00 : 0x01;
      $data->normalize();
      $data = $data->data;
    } else if($data instanceof OpenPGP_Message && $data[0] instanceof OpenPGP_PublicKeyPacket) {
      // $data is a message with PublicKey first, UserID second
      $key = implode('', $data[0]->fingerprint_material());
      $user_id = $data[1]->body();
      $data = $key . chr(0xB4) . pack('N', strlen($user_id)) . $user_id;
    }
    $this->data = $data; // Store to-be-signed data in here until the signing happens
  }

  /**
   * $this->data must be set to the data to sign (done by constructor)
   * $signers in the same format as $verifiers for OpenPGP_Message.
   */
  function sign_data($signers) {
    $this->trailer = $this->calculate_trailer();
    $signer = $signers[$this->key_algorithm_name()][$this->hash_algorithm_name()];
    $this->data = call_user_func($signer, $this->data.$this->trailer);
    $unpacked = unpack('n', substr(implode('',$this->data), 0, 2));
    $this->hash_head = reset($unpacked);
  }

  function read() {
    switch($this->version = ord($this->read_byte())) {
      case 2:
      case 3:
        assert(ord($this->read_byte()) == 5);
        $this->signature_type = ord($this->read_byte());
        $creation_time = $this->read_timestamp();
        $keyid = $this->read_bytes(8);
        $keyidHex = '';
        for($i = 0; $i < strlen($keyid); $i++) { // Store KeyID in Hex
          $keyidHex .= sprintf('%02X',ord($keyid{$i}));
        }

        $this->hashed_subpackets = array();
        $this->unhashed_subpackets = array(
          new OpenPGP_SignaturePacket_SignatureCreationTimePacket($creation_time),
          new OpenPGP_SignaturePacket_IssuerPacket($keyidHex)
        );

        $this->key_algorithm = ord($this->read_byte());
        $this->hash_algorithm = ord($this->read_byte());
        $this->hash_head = $this->read_unpacked(2, 'n');
        $this->data = array();
        while(strlen($this->input) > 0) {
          $this->data[] = $this->read_mpi();
        }
        break;
      case 4:
        $this->signature_type = ord($this->read_byte());
        $this->key_algorithm = ord($this->read_byte());
        $this->hash_algorithm = ord($this->read_byte());
        $this->trailer = chr(4).chr($this->signature_type).chr($this->key_algorithm).chr($this->hash_algorithm);

        $hashed_size = $this->read_unpacked(2, 'n');
        $hashed_subpackets = $this->read_bytes($hashed_size);
        $this->trailer .= pack('n', $hashed_size).$hashed_subpackets;
        $this->hashed_subpackets = self::get_subpackets($hashed_subpackets);

        $this->trailer .= chr(4).chr(0xff).pack('N', 6 + $hashed_size);

        $unhashed_size = $this->read_unpacked(2, 'n');
        $this->unhashed_subpackets = self::get_subpackets($this->read_bytes($unhashed_size));

        $this->hash_head = $this->read_unpacked(2, 'n');

        $this->data = array();
        while(strlen($this->input) > 0) {
          $this->data[] = $this->read_mpi();
        }
        break;
    }
  }

  function calculate_trailer() {
    // The trailer is just the top of the body plus some crap
    $body = $this->body_start();
    return $body.chr(4).chr(0xff).pack('N', strlen($body));
  }

  function body_start() {
    $body = chr(4).chr($this->signature_type).chr($this->key_algorithm).chr($this->hash_algorithm);

    $hashed_subpackets = '';
    foreach((array)$this->hashed_subpackets as $p) {
      $hashed_subpackets .= $p->to_bytes();
    }
    $body .= pack('n', strlen($hashed_subpackets)).$hashed_subpackets;

    return $body;
  }

  function body() {
    switch($this->version) {
      case 2:
      case 3:
        $body = chr($this->version) . chr(5) . chr($this->signature_type);

        foreach((array)$this->unhashed_subpackets as $p) {
          if($p instanceof OpenPGP_SignaturePacket_SignatureCreationTimePacket) {
            $body .= pack('N', $p->data);
            break;
          }
        }

        foreach((array)$this->unhashed_subpackets as $p) {
          if($p instanceof OpenPGP_SignaturePacket_IssuerPacket) {
            for($i = 0; $i < strlen($p->data); $i += 2) {
              $body .= chr(hexdec($p->data{$i}.$p->data{$i+1}));
            }
            break;
          }
        }

        $body .= chr($this->key_algorithm);
        $body .= chr($this->hash_algorithm);
        $body .= pack('n', $this->hash_head);

        foreach($this->data as $mpi) {
          $body .= pack('n', OpenPGP::bitlength($mpi)).$mpi;
        }

        return $body;
      case 4:
        if(!$this->trailer) $this->trailer = $this->calculate_trailer();
        $body = substr($this->trailer, 0, -6);

        $unhashed_subpackets = '';
        foreach((array)$this->unhashed_subpackets as $p) {
          $unhashed_subpackets .= $p->to_bytes();
        }
        $body .= pack('n', strlen($unhashed_subpackets)).$unhashed_subpackets;

        $body .= pack('n', $this->hash_head);

        foreach((array)$this->data as $mpi) {
          $body .= pack('n', OpenPGP::bitlength($mpi)).$mpi;
        }

        return $body;
    }
  }

  function key_algorithm_name() {
    return OpenPGP_PublicKeyPacket::$algorithms[$this->key_algorithm];
  }

  function hash_algorithm_name() {
    return self::$hash_algorithms[$this->hash_algorithm];
  }

  function issuer() {
    foreach($this->hashed_subpackets as $p) {
      if($p instanceof OpenPGP_SignaturePacket_IssuerPacket) return $p->data;
    }
    foreach($this->unhashed_subpackets as $p) {
      if($p instanceof OpenPGP_SignaturePacket_IssuerPacket) return $p->data;
    }
    return NULL;
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-5.2.3.1
   */
  static function get_subpackets($input) {
    $subpackets = array();
    while(($length = strlen($input)) > 0) {
      $subpackets[] = self::get_subpacket($input);
      if($length == strlen($input)) { // Parsing stuck?
        break;
      }
    }
    return $subpackets;
  }

  static function get_subpacket(&$input) {
    $len = ord($input[0]);
    $length_of_length = 1;
    // if($len < 192) One octet length, no furthur processing
    if($len > 190 && $len < 255) { // Two octet length
      $length_of_length = 2;
      $len = (($len - 192) << 8) + ord($input[1]) + 192;
    }
    if($len == 255) { // Five octet length
      $length_of_length = 5;
      $unpacked = unpack('N', substr($input, 1, 4));
      $len = reset($unpacked);
    }
    $input = substr($input, $length_of_length); // Chop off length header
    $tag = ord($input[0]);
    $class = self::class_for($tag);
    if($class) {
      $packet = new $class();
      $packet->tag = $tag;
      $packet->input = substr($input, 1, $len-1);
      $packet->length = $len-1;
      $packet->read();
      unset($packet->input);
      unset($packet->length);
    }
    $input = substr($input, $len); // Chop off the data from this packet
    return $packet;
  }

  static $hash_algorithms = array(
       1 => 'MD5',
       2 => 'SHA1',
       3 => 'RIPEMD160',
       8 => 'SHA256',
       9 => 'SHA384',
      10 => 'SHA512',
      11 => 'SHA224'
    );

  static $subpacket_types = array(
      //0 => 'Reserved',
      //1 => 'Reserved',
      2 => 'SignatureCreationTime',
      3 => 'SignatureExpirationTime',
      4 => 'ExportableCertification',
      5 => 'TrustSignature',
      6 => 'RegularExpression',
      7 => 'Revocable',
      //8 => 'Reserved',
      9 => 'KeyExpirationTime',
      //10 => 'Placeholder for backward compatibility',
      11 => 'PreferredSymmetricAlgorithms',
      12 => 'RevocationKey',
      //13 => 'Reserved',
      //14 => 'Reserved',
      //15 => 'Reserved',
      16 => 'Issuer',
      //17 => 'Reserved',
      //18 => 'Reserved',
      //19 => 'Reserved',
      20 => 'NotationData',
      21 => 'PreferredHashAlgorithms',
      22 => 'PreferredCompressionAlgorithms',
      23 => 'KeyServerPreferences',
      24 => 'PreferredKeyServer',
      25 => 'PrimaryUserID',
      26 => 'PolicyURI',
      27 => 'KeyFlags',
      28 => 'SignersUserID',
      29 => 'ReasonforRevocation',
      30 => 'Features',
      31 => 'SignatureTarget',
      32 => 'EmbeddedSignature',
    );

  static function class_for($tag) {
    if(!isset(self::$subpacket_types[$tag])) return 'OpenPGP_SignaturePacket_Subpacket';
    return 'OpenPGP_SignaturePacket_'.self::$subpacket_types[$tag].'Packet';
  }

}

class OpenPGP_SignaturePacket_Subpacket extends OpenPGP_Packet {
  function __construct($data=NULL) {
    parent::__construct($data);
    $this->tag = array_search(substr(substr(get_class($this), 8+16), 0, -6), OpenPGP_SignaturePacket::$subpacket_types);
  }

  function header_and_body() {
    $body = $this->body(); // Get body first, we will need it's length
    $size = chr(255).pack('N', strlen($body)+1); // Use 5-octet lengths + 1 for tag as first packet body octet
    $tag = chr($this->tag);
    return array('header' => $size.$tag, 'body' => $body);
  }

  /* Defaults for unsupported packets */
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

/**
 * @see http://tools.ietf.org/html/rfc4880#section-5.2.3.4
 */
class OpenPGP_SignaturePacket_SignatureCreationTimePacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->read_timestamp();
  }

  function body() {
    return pack('N', $this->data);
  }
}

class OpenPGP_SignaturePacket_SignatureExpirationTimePacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->read_timestamp();
  }

  function body() {
    return pack('N', $this->data);
  }
}

class OpenPGP_SignaturePacket_ExportableCertificationPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = (ord($this->input) != 0);
  }

  function body() {
    return chr($this->data ? 1 : 0);
  }
}

class OpenPGP_SignaturePacket_TrustSignaturePacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->depth = ord($this->input{0});
    $this->trust = ord($this->input{1});
  }

  function body() {
    return chr($this->depth) . chr($this->trust);
  }
}

class OpenPGP_SignaturePacket_RegularExpressionPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = substr($this->input, 0, -1);
  }

  function body() {
    return $this->data . chr(0);
  }
}

class OpenPGP_SignaturePacket_RevocablePacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = (ord($this->input) != 0);
  }

  function body() {
    return chr($this->data ? 1 : 0);
  }
}

class OpenPGP_SignaturePacket_KeyExpirationTimePacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->read_timestamp();
  }

  function body() {
    return pack('N', $this->data);
  }
}

class OpenPGP_SignaturePacket_PreferredSymmetricAlgorithmsPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = array();
    while(strlen($this->input) > 0) {
      $this->data[] = ord($this->read_byte());
    }
  }

  function body() {
    $bytes = '';
    foreach($this->data as $algo) {
      $bytes .= chr($algo);
    }
    return $bytes;
  }
}

class OpenPGP_SignaturePacket_RevocationKeyPacket extends OpenPGP_SignaturePacket_Subpacket {
   public $key_algorithm, $fingerprint, $sensitive;

  function read() {
    // bitfield must have bit 0x80 set, says the spec
    $bitfield = ord($this->read_byte());
    $this->sensitive = $bitfield & 0x40 == 0x40;
    $this->key_algorithm = ord($this->read_byte());

    $this->fingerprint = '';
    while(strlen($this->input) > 0) {
      $this->fingerprint .= sprintf('%02X',ord($this->read_byte()));
    }
  }

  function body() {
    $bytes = '';
    $bytes .= chr(0x80 | ($this->sensitive ? 0x40 : 0x00));
    $bytes .= chr($this->key_algorithm);

    for($i = 0; $i < strlen($this->fingerprint); $i += 2) {
      $bytes .= chr(hexdec($this->fingerprint{$i}.$this->fingerprint{$i+1}));
    }

    return $bytes;
  }
}

/**
 * @see http://tools.ietf.org/html/rfc4880#section-5.2.3.5
 */
class OpenPGP_SignaturePacket_IssuerPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    for($i = 0; $i < 8; $i++) { // Store KeyID in Hex
      $this->data .= sprintf('%02X',ord($this->read_byte()));
    }
  }

  function body() {
    $bytes = '';
    for($i = 0; $i < strlen($this->data); $i += 2) {
      $bytes .= chr(hexdec($this->data{$i}.$this->data{$i+1}));
    }
    return $bytes;
  }
}

class OpenPGP_SignaturePacket_NotationDataPacket extends OpenPGP_SignaturePacket_Subpacket {
  public $human_readable, $name;

  function read() {
    $flags = $this->read_bytes(4);
    $namelen = $this->read_unpacked(2, 'n');
    $datalen = $this->read_unpacked(2, 'n');
    $this->human_readable = ord($flags[0]) & 0x80 == 0x80;
    $this->name = $this->read_bytes($namelen);
    $this->data = $this->read_bytes($datalen);
  }

  function body () {
    return chr($this->human_readable ? 0x80 : 0x00) . "\0\0\0" .
      pack('n', strlen($this->name)) . pack('n', strlen($this->data)) .
      $this->name . $this->data;
  }
}

class OpenPGP_SignaturePacket_PreferredHashAlgorithmsPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = array();
    while(strlen($this->input) > 0) {
      $this->data[] = ord($this->read_byte());
    }
  }

  function body() {
    $bytes = '';
    foreach($this->data as $algo) {
      $bytes .= chr($algo);
    }
    return $bytes;
  }
}

class OpenPGP_SignaturePacket_PreferredCompressionAlgorithmsPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = array();
    while(strlen($this->input) > 0) {
      $this->data[] = ord($this->read_byte());
    }
  }

  function body() {
    $bytes = '';
    foreach($this->data as $algo) {
      $bytes .= chr($algo);
    }
    return $bytes;
  }
}

class OpenPGP_SignaturePacket_KeyServerPreferencesPacket extends OpenPGP_SignaturePacket_Subpacket {
  public $no_modify;

  function read() {
    $flags = ord($this->input);
    $this->no_modify = $flags & 0x80 == 0x80;
  }

  function body() {
    return chr($this->no_modify ? 0x80 : 0x00);
  }
}

class OpenPGP_SignaturePacket_PreferredKeyServerPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

class OpenPGP_SignaturePacket_PrimaryUserIDPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = (ord($this->input) != 0);
  }

  function body() {
    return chr($this->data ? 1 : 0);
  }

}

class OpenPGP_SignaturePacket_PolicyURIPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

class OpenPGP_SignaturePacket_KeyFlagsPacket extends OpenPGP_SignaturePacket_Subpacket {
  function __construct($flags=array()) {
    parent::__construct();
    $this->flags = $flags;
  }

  function read() {
    $this->flags = array();
    while($this->input) {
      $this->flags[] = ord($this->read_byte());
    }
  }

  function body() {
    $bytes = '';
    foreach($this->flags as $f) {
      $bytes .= chr($f);
    }
    return $bytes;
  }
}

class OpenPGP_SignaturePacket_SignersUserIDPacket extends OpenPGP_SignaturePacket_Subpacket {
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

class OpenPGP_SignaturePacket_ReasonforRevocationPacket extends OpenPGP_SignaturePacket_Subpacket {
  public $code;

  function read() {
    $this->code = ord($this->read_byte());
    $this->data = $this->input;
  }

  function body() {
    return chr($this->code) . $this->data;
  }
}


class OpenPGP_SignaturePacket_FeaturesPacket extends OpenPGP_SignaturePacket_KeyFlagsPacket {
  // Identical functionality to parent
}

class OpenPGP_SignaturePacket_SignatureTargetPacket extends OpenPGP_SignaturePacket_Subpacket {
  public $key_algorithm, $hash_algorithm;

  function read() {
    $this->key_algorithm = ord($this->read_byte());
    $this->hash_algorithm = ord($this->read_byte());
    $this->data = $this->input;
  }

  function body() {
    return chr($this->key_algorithm) . chr($this->hash_algorithm) . $this->data;
  }

}

class OpenPGP_SignaturePacket_EmbeddedSignaturePacket extends OpenPGP_SignaturePacket {
  // TODO: This is duplicated from subpacket... improve?
  function __construct($data=NULL) {
    parent::__construct($data);
    $this->tag = array_search(substr(substr(get_class($this), 8+16), 0, -6), OpenPGP_SignaturePacket::$subpacket_types);
  }

  function header_and_body() {
    $body = $this->body(); // Get body first, we will need it's length
    $size = chr(255).pack('N', strlen($body)+1); // Use 5-octet lengths + 1 for tag as first packet body octet
    $tag = chr($this->tag);
    return array('header' => $size.$tag, 'body' => $body);
  }
}

/**
 * OpenPGP Symmetric-Key Encrypted Session Key packet (tag 3).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.3
 */
class OpenPGP_SymmetricSessionKeyPacket extends OpenPGP_Packet {
  public $version, $symmetric_algorithm, $s2k, $encrypted_data;

  function __construct($s2k=NULL, $encrypted_data='', $symmetric_algorithm=9, $version=3) {
    parent::__construct();
    $this->version = $version;
    $this->symmetric_algorithm = $symmetric_algorithm;
    $this->s2k = $s2k;
    $this->encrypted_data = $encrypted_data;
  }

  function read() {
    $this->version = ord($this->read_byte());
    $this->symmetric_algorithm = ord($this->read_byte());
    $this->s2k = OpenPGP_S2k::parse($this->input);
    $this->encrypted_data = $this->input;
  }

  function body() {
    return chr($this->version) . chr($this->symmetric_algorithm) .
      $this->s2k->to_bytes() . $this->encrypted_data;
  }
}

/**
 * OpenPGP One-Pass Signature packet (tag 4).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.4
 */
class OpenPGP_OnePassSignaturePacket extends OpenPGP_Packet {
  public $version, $signature_type, $hash_algorithm, $key_algorithm, $key_id, $nested;
  function read() {
    $this->version = ord($this->read_byte());
    $this->signature_type = ord($this->read_byte());
    $this->hash_algorithm = ord($this->read_byte());
    $this->key_algorithm = ord($this->read_byte());
    for($i = 0; $i < 8; $i++) { // Store KeyID in Hex
      $this->key_id .= sprintf('%02X',ord($this->read_byte()));
    }
    $this->nested = ord($this->read_byte());
  }

  function body() {
    $body = chr($this->version).chr($this->signature_type).chr($this->hash_algorithm).chr($this->key_algorithm);
    for($i = 0; $i < strlen($this->key_id); $i += 2) {
      $body .= chr(hexdec($this->key_id{$i}.$this->key_id{$i+1}));
    }
    $body .= chr((int)$this->nested);
    return $body;
  }
}

/**
 * OpenPGP Public-Key packet (tag 6).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.1.1
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.2
 * @see http://tools.ietf.org/html/rfc4880#section-11.1
 * @see http://tools.ietf.org/html/rfc4880#section-12
 */
class OpenPGP_PublicKeyPacket extends OpenPGP_Packet {
  public $version, $timestamp, $algorithm;
  public $key, $key_id, $fingerprint;
  public $v3_days_of_validity;

  function __construct($key=array(), $algorithm='RSA', $timestamp=NULL, $version=4) {
    parent::__construct();
    $this->key = $key;
    if(is_string($this->algorithm = $algorithm)) {
      $this->algorithm = array_search($this->algorithm, self::$algorithms);
    }
    $this->timestamp = $timestamp ? $timestamp : time();
    $this->version = $version;

    if(count($this->key) > 0) {
      $this->key_id = substr($this->fingerprint(), -8);
    }
  }

  // Find self signatures in a message, these often contain metadata about the key
  function self_signatures($message) {
    $sigs = array();
    $keyid16 = strtoupper(substr($this->fingerprint, -16));
    foreach($message as $p) {
      if($p instanceof OpenPGP_SignaturePacket) {
        if(strtoupper($p->issuer()) == $keyid16) {
          $sigs[] = $p;
        } else {
          foreach(array_merge($p->hashed_subpackets, $p->unhashed_subpackets) as $s) {
            if($s instanceof OpenPGP_SignaturePacket_EmbeddedSignaturePacket && strtoupper($s->issuer()) == $keyid16) {
              $sigs[] = $p;
              break;
            }
          }
        }
      } else if(count($sigs)) break; // After we've seen a self sig, the next non-sig stop all self-sigs
    }
    return $sigs;
  }

  // Find expiry time of this key based on the self signatures in a message
  function expires($message) {
    foreach($this->self_signatures($message) as $p) {
      foreach(array_merge($p->hashed_subpackets, $p->unhashed_subpackets) as $s) {
        if($s instanceof OpenPGP_SignaturePacket_KeyExpirationTimePacket) {
          return $this->timestamp + $s->data;
        }
      }
    }
    return NULL; // Never expires
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-5.5.2
   */
  function read() {
    switch ($this->version = ord($this->read_byte())) {
      case 3:
        $this->timestamp = $this->read_timestamp();
        $this->v3_days_of_validity = $this->read_unpacked(2, 'n');
        $this->algorithm = ord($this->read_byte());
        $this->read_key_material();
        break;
      case 4:
        $this->timestamp = $this->read_timestamp();
        $this->algorithm = ord($this->read_byte());
        $this->read_key_material();
    }
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-5.5.2
   */
  function read_key_material() {
    foreach (self::$key_fields[$this->algorithm] as $field) {
      $this->key[$field] = $this->read_mpi();
    }
    $this->key_id = substr($this->fingerprint(), -8);
  }

  function fingerprint_material() {
    switch ($this->version) {
      case 3:
        $material = array();
        foreach (self::$key_fields[$this->algorithm] as $i) {
          $material[] = pack('n', OpenPGP::bitlength($this->key[$i]));
          $material[] = $this->key[$i];
        }
        return $material;
      case 4:
        $head = array(
          chr(0x99), NULL,
          chr($this->version), pack('N', $this->timestamp),
          chr($this->algorithm),
        );
        $material = array();
        foreach (self::$key_fields[$this->algorithm] as $i) {
          $material[] = pack('n', OpenPGP::bitlength($this->key[$i]));
          $material[] = $this->key[$i];
        }
        $material = implode('', $material);
        $head[1] = pack('n', 6 + strlen($material));
        $head[] = $material;
        return $head;
    }
  }

  /**
   * @see http://tools.ietf.org/html/rfc4880#section-12.2
   * @see http://tools.ietf.org/html/rfc4880#section-3.3
   */
  function fingerprint() {
    switch ($this->version) {
      case 2:
      case 3:
        return $this->fingerprint = strtoupper(md5(implode('', $this->fingerprint_material())));
      case 4:
        return $this->fingerprint = strtoupper(sha1(implode('', $this->fingerprint_material())));
    }
  }

  function body() {
     switch ($this->version) {
      case 2:
      case 3:
        return implode('', array_merge(array(
            chr($this->version) . pack('N', $this->timestamp) .
              pack('n', $this->v3_days_of_validity) . chr($this->algorithm)
          ), $this->fingerprint_material())
        );
      case 4:
        return implode('', array_slice($this->fingerprint_material(), 2));
    }
  }

  static $key_fields = array(
     1 => array('n', 'e'),           // RSA
    16 => array('p', 'g', 'y'),      // ELG-E
    17 => array('p', 'q', 'g', 'y'), // DSA
  );

  static $algorithms = array(
       1 => 'RSA',
       2 => 'RSA',
       3 => 'RSA',
      16 => 'ELGAMAL',
      17 => 'DSA',
      18 => 'ECC',
      19 => 'ECDSA',
      21 => 'DH'
    );

}

/**
 * OpenPGP Public-Subkey packet (tag 14).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.1.2
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.2
 * @see http://tools.ietf.org/html/rfc4880#section-11.1
 * @see http://tools.ietf.org/html/rfc4880#section-12
 */
class OpenPGP_PublicSubkeyPacket extends OpenPGP_PublicKeyPacket {
  // TODO
}

/**
 * OpenPGP Secret-Key packet (tag 5).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.1.3
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.3
 * @see http://tools.ietf.org/html/rfc4880#section-11.2
 * @see http://tools.ietf.org/html/rfc4880#section-12
 */
class OpenPGP_SecretKeyPacket extends OpenPGP_PublicKeyPacket {
  public $s2k_useage, $s2k, $symmetric_algorithm, $private_hash, $encrypted_data;
  function read() {
    parent::read(); // All the fields from PublicKey
    $this->s2k_useage = ord($this->read_byte());
    if($this->s2k_useage == 255 || $this->s2k_useage == 254) {
      $this->symmetric_algorithm = ord($this->read_byte());
      $this->s2k = OpenPGP_S2k::parse($this->input);
    } else if($this->s2k_useage > 0) {
      $this->symmetric_algorithm = $this->s2k_useage;
    }
    if($this->s2k_useage > 0) {
      $this->encrypted_data = $this->input; // Rest of input is MPIs and checksum (encrypted)
    } else {
      $this->key_from_input();
      $this->private_hash = $this->read_bytes(2); // TODO: Validate checksum?
    }
  }

  static $secret_key_fields = array(
     1 => array('d', 'p', 'q', 'u'), // RSA
     2 => array('d', 'p', 'q', 'u'), // RSA-E
     3 => array('d', 'p', 'q', 'u'), // RSA-S
    16 => array('x'),                // ELG-E
    17 => array('x'),                // DSA
  );

  function key_from_input() {
    foreach(self::$secret_key_fields[$this->algorithm] as $field) {
      $this->key[$field] = $this->read_mpi();
    }
  }

  function body() {
    $bytes = parent::body() . chr($this->s2k_useage);
    $secret_material = NULL;
    if($this->s2k_useage == 255 || $this->s2k_useage == 254) {
      $bytes .= chr($this->symmetric_algorithm);
      $bytes .= $this->s2k->to_bytes();
    }
    if($this->s2k_useage > 0) {
      $bytes .= $this->encrypted_data;
    } else {
      $secret_material = '';
      foreach(self::$secret_key_fields[$this->algorithm] as $f) {
        $f = $this->key[$f];
        $secret_material .= pack('n', OpenPGP::bitlength($f));
        $secret_material .= $f;
      }
      $bytes .= $secret_material;

      // 2-octet checksum
      $chk = 0;
      for($i = 0; $i < strlen($secret_material); $i++) {
        $chk = ($chk + ord($secret_material[$i])) % 65536;
      }
      $bytes .= pack('n', $chk);
    }
    return $bytes;
  }
}

/**
 * OpenPGP Secret-Subkey packet (tag 7).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.1.4
 * @see http://tools.ietf.org/html/rfc4880#section-5.5.3
 * @see http://tools.ietf.org/html/rfc4880#section-11.2
 * @see http://tools.ietf.org/html/rfc4880#section-12
 */
class OpenPGP_SecretSubkeyPacket extends OpenPGP_SecretKeyPacket {
  // TODO
}

/**
 * OpenPGP Compressed Data packet (tag 8).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.6
 */
class OpenPGP_CompressedDataPacket extends OpenPGP_Packet implements IteratorAggregate, ArrayAccess {
  public $algorithm;
  /* see http://tools.ietf.org/html/rfc4880#section-9.3 */
  static $algorithms = array(0 => 'Uncompressed', 1 => 'ZIP', 2 => 'ZLIB', 3 => 'BZip2');
  function read() {
    $this->algorithm = ord($this->read_byte());
    $this->data = $this->read_bytes($this->length);
    switch($this->algorithm) {
      case 0:
        $this->data = OpenPGP_Message::parse($this->data);
        break;
      case 1:
        $this->data = OpenPGP_Message::parse(gzinflate($this->data));
        break;
      case 2:
        $this->data = OpenPGP_Message::parse(gzuncompress($this->data));
        break;
      case 3:
        $this->data = OpenPGP_Message::parse(bzdecompress($this->data));
        break;
      default:
        /* TODO error? */
    }
  }

  function body() {
    $body = chr($this->algorithm);
    switch($this->algorithm) {
      case 0:
        $body .= $this->data->to_bytes();
        break;
      case 1:
        $body .= gzdeflate($this->data->to_bytes());
        break;
      case 2:
        $body .= gzcompress($this->data->to_bytes());
        break;
      case 3:
        $body .= bzcompress($this->data->to_bytes());
        break;
      default:
        /* TODO error? */
    }
    return $body;
  }

  // IteratorAggregate interface

  function getIterator() {
    return new ArrayIterator($this->data->packets);
  }

  // ArrayAccess interface

  function offsetExists($offset) {
    return isset($this->data[$offset]);
  }

  function offsetGet($offset) {
    return $this->data[$offset];
  }

  function offsetSet($offset, $value) {
    return is_null($offset) ? $this->data[] = $value : $this->data[$offset] = $value;
  }

  function offsetUnset($offset) {
    unset($this->data[$offset]);
  }

}

/**
 * OpenPGP Symmetrically Encrypted Data packet (tag 9).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.7
 */
class OpenPGP_EncryptedDataPacket extends OpenPGP_Packet {
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

/**
 * OpenPGP Marker packet (tag 10).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.8
 */
class OpenPGP_MarkerPacket extends OpenPGP_Packet {
  // TODO
}

/**
 * OpenPGP Literal Data packet (tag 11).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.9
 */
class OpenPGP_LiteralDataPacket extends OpenPGP_Packet {
  public $format, $filename, $timestamp;

  function __construct($data=NULL, $opt=array()) {
    parent::__construct();
    $this->data = $data;
    $this->format = isset($opt['format']) ? $opt['format'] : 'b';
    $this->filename = isset($opt['filename']) ? $opt['filename'] : 'data';
    $this->timestamp = isset($opt['timestamp']) ? $opt['timestamp'] : time();
  }

  function normalize() {
    if($this->format == 'u' || $this->format == 't') { // Normalize line endings
      $this->data = str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", $this->data)));
    }
  }

  function read() {
    $this->size = $this->length - 1 - 4;
    $this->format = $this->read_byte();
    $filename_length = ord($this->read_byte());
    $this->size -= $filename_length;
    $this->filename = $this->read_bytes($filename_length);
    $this->timestamp = $this->read_timestamp();
    $this->data = $this->read_bytes($this->size);
  }

  function body() {
    return $this->format.chr(strlen($this->filename)).$this->filename.pack('N', $this->timestamp).$this->data;
  }
}

/**
 * OpenPGP Trust packet (tag 12).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.10
 */
class OpenPGP_TrustPacket extends OpenPGP_Packet {
  function read() {
    $this->data = $this->input;
  }

  function body() {
    return $this->data;
  }
}

/**
 * OpenPGP User ID packet (tag 13).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.11
 * @see http://tools.ietf.org/html/rfc2822
 */
class OpenPGP_UserIDPacket extends OpenPGP_Packet {
  public $name, $comment, $email;

  function __construct($name='', $comment='', $email='') {
    parent::__construct();
    if(!$comment && !$email) {
      $this->input = $name;
      $this->read();
    } else {
      $this->name = $name;
      $this->comment = $comment;
      $this->email = $email;
    }
  }

  function read() {
    $this->data = $this->input;
    // User IDs of the form: "name (comment) <email>"
    if (preg_match('/^([^\(]+)\(([^\)]+)\)\s+<([^>]+)>$/', $this->data, $matches)) {
      $this->name    = trim($matches[1]);
      $this->comment = trim($matches[2]);
      $this->email   = trim($matches[3]);
    }
    // User IDs of the form: "name <email>"
    else if (preg_match('/^([^<]+)\s+<([^>]+)>$/', $this->data, $matches)) {
      $this->name    = trim($matches[1]);
      $this->comment = NULL;
      $this->email   = trim($matches[2]);
    }
    // User IDs of the form: "name"
    else if (preg_match('/^([^<]+)$/', $this->data, $matches)) {
      $this->name    = trim($matches[1]);
      $this->comment = NULL;
      $this->email   = NULL;
    }
    // User IDs of the form: "<email>"
    else if (preg_match('/^<([^>]+)>$/', $this->data, $matches)) {
      $this->name    = NULL;
      $this->comment = NULL;
      $this->email   = trim($matches[2]);
    }
  }

  function __toString() {
    $text = array();
    if ($this->name)    { $text[] = $this->name; }
    if ($this->comment) { $text[] = "({$this->comment})"; }
    if ($this->email)   { $text[] = "<{$this->email}>"; }
    return implode(' ', $text);
  }

  function body() {
    return ''.$this; // Convert to string is the body
  }
}

/**
 * OpenPGP User Attribute packet (tag 17).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.12
 * @see http://tools.ietf.org/html/rfc4880#section-11.1
 */
class OpenPGP_UserAttributePacket extends OpenPGP_Packet {
  public $packets;

  // TODO
}

/**
 * OpenPGP Sym. Encrypted Integrity Protected Data packet (tag 18).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.13
 */
class OpenPGP_IntegrityProtectedDataPacket extends OpenPGP_EncryptedDataPacket {
  public $version;

  function __construct($data='', $version=1) {
    parent::__construct();
    $this->version = $version;
    $this->data = $data;
  }

  function read() {
    $this->version = ord($this->read_byte());
    $this->data = $this->input;
  }

  function body() {
    return chr($this->version) . $this->data;
  }
}

/**
 * OpenPGP Modification Detection Code packet (tag 19).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-5.14
 */
class OpenPGP_ModificationDetectionCodePacket extends OpenPGP_Packet {
  function __construct($sha1='') {
    parent::__construct();
    $this->data = $sha1;
  }

  function read() {
    $this->data = $this->input;
    if(strlen($this->input) != 20) throw new Exception("Bad ModificationDetectionCodePacket");
  }

  function header_and_body() {
    $body = $this->body(); // Get body first, we will need it's length
    if(strlen($body) != 20) throw new Exception("Bad ModificationDetectionCodePacket");
    return array('header' => "\xD3\x14", 'body' => $body);
  }

  function body() {
    return $this->data;
  }
}

/**
 * OpenPGP Private or Experimental packet (tags 60..63).
 *
 * @see http://tools.ietf.org/html/rfc4880#section-4.3
 */
class OpenPGP_ExperimentalPacket extends OpenPGP_Packet {}

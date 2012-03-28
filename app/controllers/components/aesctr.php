<?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */
/*  AES counter (CTR) mode implementation in PHP (c) Chris Veness 2005-2011. Right of free use is */
/*    granted for all commercial or non-commercial use under CC-BY licence. No warranty of any    */
/*    form is offered.                                                                            */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */
  
class AesctrComponent extends AesComponent {
  
  /** 
   * Encrypt a text using AES encryption in Counter mode of operation
   *  - see http://csrc.nist.gov/publications/nistpubs/800-38a/sp800-38a.pdf
   *
   * Unicode multi-byte character safe
   *
   * @param plaintext source text to be encrypted
   * @param password  the password to use to generate a key
   * @param nBits     number of bits to be used in the key (128, 192, or 256)
   * @return          encrypted text
   */
  public function encrypt($plaintext, $password, $nBits) {
    $blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
    if (!($nBits==128 || $nBits==192 || $nBits==256)) return '';  // standard allows 128/192/256 bit keys
    // note PHP (5) gives us plaintext and password in UTF8 encoding!
    
    // use AES itself to encrypt password to get cipher key (using plain password as source for  
    // key expansion) - gives us well encrypted key
    $nBytes = $nBits/8;  // no bytes in key
    $pwBytes = array();
    for ($i=0; $i<$nBytes; $i++) $pwBytes[$i] = ord(substr($password,$i,1)) & 0xff;
    $key = parent::cipher($pwBytes, parent::keyExpansion($pwBytes));
    $key = array_merge($key, array_slice($key, 0, $nBytes-16));  // expand key to 16/24/32 bytes long 
  
    // initialise 1st 8 bytes of counter block with nonce (NIST SP800-38A §B.2): [0-1] = millisec, 
    // [2-3] = random, [4-7] = seconds, giving guaranteed sub-ms uniqueness up to Feb 2106
    $counterBlock = array();
    $nonce = floor(microtime(true)*1000);   // timestamp: milliseconds since 1-Jan-1970
    $nonceMs = $nonce%1000;
    $nonceSec = floor($nonce/1000);
    $nonceRnd = floor(rand(0, 0xffff));
    
    for ($i=0; $i<2; $i++) $counterBlock[$i]   = $this->urs($nonceMs,  $i*8) & 0xff;
    for ($i=0; $i<2; $i++) $counterBlock[$i+2] = $this->urs($nonceRnd, $i*8) & 0xff;
    for ($i=0; $i<4; $i++) $counterBlock[$i+4] = $this->urs($nonceSec, $i*8) & 0xff;
    
    // and convert it to a string to go on the front of the ciphertext
    $ctrTxt = '';
    for ($i=0; $i<8; $i++) $ctrTxt .= chr($counterBlock[$i]);
  
    // generate key schedule - an expansion of the key into distinct Key Rounds for each round
    $keySchedule = parent::keyExpansion($key);
    //print_r($keySchedule);
    
    $blockCount = ceil(strlen($plaintext)/$blockSize);
    $ciphertxt = array();  // ciphertext as array of strings
    
    for ($b=0; $b<$blockCount; $b++) {
      // set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
      // done in two stages for 32-bit ops: using two words allows us to go past 2^32 blocks (68GB)
      for ($c=0; $c<4; $c++) $counterBlock[15-$c] = $this->urs($b, $c*8) & 0xff;
      for ($c=0; $c<4; $c++) $counterBlock[15-$c-4] = $this->urs($b/0x100000000, $c*8);
  
      $cipherCntr = parent::cipher($counterBlock, $keySchedule);  // -- encrypt counter block --
  
      // block size is reduced on final block
      $blockLength = $b<$blockCount-1 ? $blockSize : (strlen($plaintext)-1)%$blockSize+1;
      $cipherByte = array();
      
      for ($i=0; $i<$blockLength; $i++) {  // -- xor plaintext with ciphered counter byte-by-byte --
        $cipherByte[$i] = $cipherCntr[$i] ^ ord(substr($plaintext, $b*$blockSize+$i, 1));
        $cipherByte[$i] = chr($cipherByte[$i]);
      }
      $ciphertxt[$b] = implode('', $cipherByte);  // escape troublesome characters in ciphertext
    }
  
    // implode is more efficient than repeated string concatenation
    $ciphertext = $ctrTxt . implode('', $ciphertxt);
    $ciphertext = base64_encode($ciphertext);
    return $ciphertext;
  }
  
  
  /** 
   * Decrypt a text encrypted by AES in counter mode of operation
   *
   * @param ciphertext source text to be decrypted
   * @param password   the password to use to generate a key
   * @param nBits      number of bits to be used in the key (128, 192, or 256)
   * @return           decrypted text
   */
  public function decrypt($ciphertext, $password, $nBits) {
    $blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
    if (!($nBits==128 || $nBits==192 || $nBits==256)) return '';  // standard allows 128/192/256 bit keys
    $ciphertext = base64_decode($ciphertext);
  
    // use AES to encrypt password (mirroring encrypt routine)
    $nBytes = $nBits/8;  // no bytes in key
    $pwBytes = array();
    for ($i=0; $i<$nBytes; $i++) $pwBytes[$i] = ord(substr($password,$i,1)) & 0xff;
    $key = parent::cipher($pwBytes, parent::keyExpansion($pwBytes));
    $key = array_merge($key, array_slice($key, 0, $nBytes-16));  // expand key to 16/24/32 bytes long
    
    // recover nonce from 1st element of ciphertext
    $counterBlock = array();
    $ctrTxt = substr($ciphertext, 0, 8);
    for ($i=0; $i<8; $i++) $counterBlock[$i] = ord(substr($ctrTxt,$i,1));
    
    // generate key schedule
    $keySchedule = parent::keyExpansion($key);
  
    // separate ciphertext into blocks (skipping past initial 8 bytes)
    $nBlocks = ceil((strlen($ciphertext)-8) / $blockSize);
    $ct = array();
    for ($b=0; $b<$nBlocks; $b++) $ct[$b] = substr($ciphertext, 8+$b*$blockSize, 16);
    $ciphertext = $ct;  // ciphertext is now array of block-length strings
  
    // plaintext will get generated block-by-block into array of block-length strings
    $plaintxt = array();
    
    for ($b=0; $b<$nBlocks; $b++) {
      // set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
      for ($c=0; $c<4; $c++) $counterBlock[15-$c] = $this->urs($b, $c*8) & 0xff;
      for ($c=0; $c<4; $c++) $counterBlock[15-$c-4] = $this->urs(($b+1)/0x100000000-1, $c*8) & 0xff;
  
      $cipherCntr = parent::cipher($counterBlock, $keySchedule);  // encrypt counter block
  
      $plaintxtByte = array();
      for ($i=0; $i<strlen($ciphertext[$b]); $i++) {
        // -- xor plaintext with ciphered counter byte-by-byte --
        $plaintxtByte[$i] = $cipherCntr[$i] ^ ord(substr($ciphertext[$b],$i,1));
        $plaintxtByte[$i] = chr($plaintxtByte[$i]);
      
      }
      $plaintxt[$b] = implode('', $plaintxtByte); 
    }
  
    // join array of blocks into single plaintext string
    $plaintext = implode('',$plaintxt);
    
    return $plaintext;
  }
  
  
  /*
   * Unsigned right shift function, since PHP has neither >>> operator nor unsigned ints
   *
   * @param a  number to be shifted (32-bit integer)
   * @param b  number of bits to shift a to the right (0..31)
   * @return   a right-shifted and zero-filled by b bits
   */
  private function urs($a, $b) {
    $a &= 0xffffffff; $b &= 0x1f;  // (bounds check)
    if ($a&0x80000000 && $b>0) {   // if left-most bit set
      $a = ($a>>1) & 0x7fffffff;   //   right-shift one bit & clear left-most bit
      $a = $a >> ($b-1);           //   remaining right-shifts
    } else {                       // otherwise
      $a = ($a>>$b);               //   use normal right-shift
    } 
    return $a; 
  }

}  
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */
?>
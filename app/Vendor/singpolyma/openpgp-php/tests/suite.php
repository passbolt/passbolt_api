<?php

require_once dirname(__FILE__).'/../lib/openpgp.php';

class Serialization extends PHPUnit_Framework_TestCase {
  public function oneSerialization($path) {
    $in = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/data/' . $path));
    $mid = $in->to_bytes();
    $out = OpenPGP_Message::parse($mid);
    $this->assertEquals($in, $out);
  }

  public function test000001006public_key() {
    $this->oneSerialization("000001-006.public_key");
  }

  
  public function test000002013user_id() {
    $this->oneSerialization("000002-013.user_id");
  }
  
  public function test000003002sig() {
    $this->oneSerialization("000003-002.sig");
  }
  
  public function test000004012ring_trust() {
    $this->oneSerialization("000004-012.ring_trust");
  }
  
  public function test000005002sig() {
    $this->oneSerialization("000005-002.sig");
  }
  
  public function test000006012ring_trust() {
    $this->oneSerialization("000006-012.ring_trust");
  }
  
  public function test000007002sig() {
    $this->oneSerialization("000007-002.sig");
  }
  
  public function test000008012ring_trust() {
    $this->oneSerialization("000008-012.ring_trust");
  }
  
  public function test000009002sig() {
    $this->oneSerialization("000009-002.sig");
  }
  
  public function test000010012ring_trust() {
    $this->oneSerialization("000010-012.ring_trust");
  }
  
  public function test000011002sig() {
    $this->oneSerialization("000011-002.sig");
  }
  
  public function test000012012ring_trust() {
    $this->oneSerialization("000012-012.ring_trust");
  }
  
  public function test000013014public_subkey() {
    $this->oneSerialization("000013-014.public_subkey");
  }
  
  public function test000014002sig() {
    $this->oneSerialization("000014-002.sig");
  }
  
  public function test000015012ring_trust() {
    $this->oneSerialization("000015-012.ring_trust");
  }
  
  public function test000016006public_key() {
    $this->oneSerialization("000016-006.public_key");
  }
  
  public function test000017002sig() {
    $this->oneSerialization("000017-002.sig");
  }
  
  public function test000018012ring_trust() {
    $this->oneSerialization("000018-012.ring_trust");
  }
  
  public function test000019013user_id() {
    $this->oneSerialization("000019-013.user_id");
  }
  
  public function test000020002sig() {
    $this->oneSerialization("000020-002.sig");
  }
  
  public function test000021012ring_trust() {
    $this->oneSerialization("000021-012.ring_trust");
  }
  
  public function test000022002sig() {
    $this->oneSerialization("000022-002.sig");
  }
  
  public function test000023012ring_trust() {
    $this->oneSerialization("000023-012.ring_trust");
  }
  
  public function test000024014public_subkey() {
    $this->oneSerialization("000024-014.public_subkey");
  }
  
  public function test000025002sig() {
    $this->oneSerialization("000025-002.sig");
  }
  
  public function test000026012ring_trust() {
    $this->oneSerialization("000026-012.ring_trust");
  }
  
  public function test000027006public_key() {
    $this->oneSerialization("000027-006.public_key");
  }
  
  public function test000028002sig() {
    $this->oneSerialization("000028-002.sig");
  }
  
  public function test000029012ring_trust() {
    $this->oneSerialization("000029-012.ring_trust");
  }
  
  public function test000030013user_id() {
    $this->oneSerialization("000030-013.user_id");
  }
  
  public function test000031002sig() {
    $this->oneSerialization("000031-002.sig");
  }
  
  public function test000032012ring_trust() {
    $this->oneSerialization("000032-012.ring_trust");
  }
  
  public function test000033002sig() {
    $this->oneSerialization("000033-002.sig");
  }
  
  public function test000034012ring_trust() {
    $this->oneSerialization("000034-012.ring_trust");
  }
  
  public function test000035006public_key() {
    $this->oneSerialization("000035-006.public_key");
  }
  
  public function test000036013user_id() {
    $this->oneSerialization("000036-013.user_id");
  }
  
  public function test000037002sig() {
    $this->oneSerialization("000037-002.sig");
  }
  
  public function test000038012ring_trust() {
    $this->oneSerialization("000038-012.ring_trust");
  }
  
  public function test000039002sig() {
    $this->oneSerialization("000039-002.sig");
  }
  
  public function test000040012ring_trust() {
    $this->oneSerialization("000040-012.ring_trust");
  }
  
  public function test000041017attribute() {
    $this->oneSerialization("000041-017.attribute");
  }
  
  public function test000042002sig() {
    $this->oneSerialization("000042-002.sig");
  }
  
  public function test000043012ring_trust() {
    $this->oneSerialization("000043-012.ring_trust");
  }
  
  public function test000044014public_subkey() {
    $this->oneSerialization("000044-014.public_subkey");
  }
  
  public function test000045002sig() {
    $this->oneSerialization("000045-002.sig");
  }
  
  public function test000046012ring_trust() {
    $this->oneSerialization("000046-012.ring_trust");
  }
  
  public function test000047005secret_key() {
    $this->oneSerialization("000047-005.secret_key");
  }
  
  public function test000048013user_id() {
    $this->oneSerialization("000048-013.user_id");
  }
  
  public function test000049002sig() {
    $this->oneSerialization("000049-002.sig");
  }
  
  public function test000050012ring_trust() {
    $this->oneSerialization("000050-012.ring_trust");
  }
  
  public function test000051007secret_subkey() {
    $this->oneSerialization("000051-007.secret_subkey");
  }
  
  public function test000052002sig() {
    $this->oneSerialization("000052-002.sig");
  }
  
  public function test000053012ring_trust() {
    $this->oneSerialization("000053-012.ring_trust");
  }
  
  public function test000054005secret_key() {
    $this->oneSerialization("000054-005.secret_key");
  }
  
  public function test000055002sig() {
    $this->oneSerialization("000055-002.sig");
  }
  
  public function test000056012ring_trust() {
    $this->oneSerialization("000056-012.ring_trust");
  }
  
  public function test000057013user_id() {
    $this->oneSerialization("000057-013.user_id");
  }
  
  public function test000058002sig() {
    $this->oneSerialization("000058-002.sig");
  }
  
  public function test000059012ring_trust() {
    $this->oneSerialization("000059-012.ring_trust");
  }
  
  public function test000060007secret_subkey() {
    $this->oneSerialization("000060-007.secret_subkey");
  }
  
  public function test000061002sig() {
    $this->oneSerialization("000061-002.sig");
  }
  
  public function test000062012ring_trust() {
    $this->oneSerialization("000062-012.ring_trust");
  }
  
  public function test000063005secret_key() {
    $this->oneSerialization("000063-005.secret_key");
  }
  
  public function test000064002sig() {
    $this->oneSerialization("000064-002.sig");
  }
  
  public function test000065012ring_trust() {
    $this->oneSerialization("000065-012.ring_trust");
  }
  
  public function test000066013user_id() {
    $this->oneSerialization("000066-013.user_id");
  }
  
  public function test000067002sig() {
    $this->oneSerialization("000067-002.sig");
  }
  
  public function test000068012ring_trust() {
    $this->oneSerialization("000068-012.ring_trust");
  }
  
  public function test000069005secret_key() {
    $this->oneSerialization("000069-005.secret_key");
  }
  
  public function test000070013user_id() {
    $this->oneSerialization("000070-013.user_id");
  }
  
  public function test000071002sig() {
    $this->oneSerialization("000071-002.sig");
  }
  
  public function test000072012ring_trust() {
    $this->oneSerialization("000072-012.ring_trust");
  }
  
  public function test000073017attribute() {
    $this->oneSerialization("000073-017.attribute");
  }
  
  public function test000074002sig() {
    $this->oneSerialization("000074-002.sig");
  }
  
  public function test000075012ring_trust() {
    $this->oneSerialization("000075-012.ring_trust");
  }
  
  public function test000076007secret_subkey() {
    $this->oneSerialization("000076-007.secret_subkey");
  }
  
  public function test000077002sig() {
    $this->oneSerialization("000077-002.sig");
  }
  
  public function test000078012ring_trust() {
    $this->oneSerialization("000078-012.ring_trust");
  }
  
  public function test002182002sig() {
    $this->oneSerialization("002182-002.sig");
  }
  
  public function testpubringgpg() {
    $this->oneSerialization("pubring.gpg");
  }
  
  public function testsecringgpg() {
    $this->oneSerialization("secring.gpg");
  }
  
  public function testcompressedsiggpg() {
    $this->oneSerialization("compressedsig.gpg");
  }
  
  public function testcompressedsigzlibgpg() {
    $this->oneSerialization("compressedsig-zlib.gpg");
  }
  
  public function testcompressedsigbzip2gpg() {
    $this->oneSerialization("compressedsig-bzip2.gpg");
  }
  
  public function testonepass_sig() {
    $this->oneSerialization("onepass_sig");
  }
  
  public function testsymmetrically_encrypted() {
    $this->oneSerialization("symmetrically_encrypted");
  }
  
  public function testuncompressedopsdsagpg() {
    $this->oneSerialization("uncompressed-ops-dsa.gpg");
  }
  
  public function testuncompressedopsdsasha384txtgpg() {
    $this->oneSerialization("uncompressed-ops-dsa-sha384.txt.gpg");
  }
  
  public function testuncompressedopsrsagpg() {
    $this->oneSerialization("uncompressed-ops-rsa.gpg");
  }

  public function testSymmetricAES() {
    $this->oneSerialization("symmetric-aes.gpg");
  }

  public function testSymmetricNoMDC() {
    $this->oneSerialization("symmetric-no-mdc.gpg");
  }
}

class Fingerprint extends PHPUnit_Framework_TestCase {
  public function oneFingerprint($path, $kf) {
    $m = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/data/' . $path));
    $this->assertEquals($m[0]->fingerprint(), $kf);
  }

  public function test000001006public_key() {
    $this->oneFingerprint("000001-006.public_key", "421F28FEAAD222F856C8FFD5D4D54EA16F87040E");
  }

  public function test000016006public_key() {
    $this->oneFingerprint("000016-006.public_key", "AF95E4D7BAC521EE9740BED75E9F1523413262DC");
  }

  public function test000027006public_key() {
    $this->oneFingerprint("000027-006.public_key", "1EB20B2F5A5CC3BEAFD6E5CB7732CF988A63EA86");
  }

  public function test000035006public_key() {
    $this->oneFingerprint("000035-006.public_key", "CB7933459F59C70DF1C3FBEEDEDC3ECF689AF56D");
  }
}

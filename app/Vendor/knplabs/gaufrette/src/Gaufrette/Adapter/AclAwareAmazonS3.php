<?php

namespace Gaufrette\Adapter;

use \AmazonS3 as AmazonClient;
use Gaufrette\Adapter;

/**
 * Makes the AmazonS3 adapter ACL aware. Uses the AWS SDK for PHP v1.x
 *
 * See the AwsS3 adapter for using the AWS SDK for PHP v2.x. There is
 * no distinction in the AwsS3 adapter between an ACL aware adapter
 * and regular adapter.
 *
 * @package Gaufrette
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AclAwareAmazonS3 implements Adapter,
                                  MetadataSupporter
{
    protected $delegate;
    protected $s3;
    protected $bucketName;
    protected $aclConstant = AmazonClient::ACL_PRIVATE;
    protected $users = array();

    public function __construct(Adapter $delegate, AmazonClient $s3, $bucketName)
    {
        $this->delegate = $delegate;
        $this->s3 = $s3;
        $this->bucketName = $bucketName;
    }

    public function setAclConstant($constant)
    {
        if (!defined($constant = 'AmazonS3::ACL_'.strtoupper($constant))) {
            throw new \InvalidArgumentException(sprintf('The ACL constant "%s" does not exist on AmazonS3.', $constant));
        }

        $this->aclConstant = constant($constant);
    }

    public function setUsers(array $users)
    {
        $this->users = array();

        foreach ($users as $user) {
            if (!isset($user['permission'])) {
                throw new \InvalidArgumentException(sprintf('setUsers() expects an array where each item contains a "permission" key, but got %s.', json_encode($user)));
            }

            if (!defined($constant = 'AmazonS3::GRANT_'.strtoupper($user['permission']))) {
                throw new \InvalidArgumentException('The permission must be the suffix for one of the AmazonS3::GRANT_ constants.');
            }
            $user['permission'] = constant($constant);

            if (isset($user['group'])) {
                if (!defined($constant = 'AmazonS3::USERS_'.strtoupper($user['group']))) {
                    throw new \InvalidArgumentException('The group must be the suffix for one of the AmazonS3::USERS_ constants.');
                }
                $user['id'] = constant($constant);
                unset($user['group']);
            } elseif (!isset($user['id'])) {
                throw new \InvalidArgumentException(sprintf('Either "group", or "id" must be set for each user, but got %s.', json_encode($user)));
            }

            $this->users[] = $user;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        return $this->delegate->read($key);
    }

    /**
     * {@inheritDoc}
     */
    public function rename($key, $new)
    {
        $rs = $this->delegate->rename($key, $new);

        try {
            $this->updateAcl($new);

            return $rs;
        } catch (\Exception $ex) {
            $this->delete($new);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content)
    {
        $rs = $this->delegate->write($key, $content);

        try {
            $this->updateAcl($key);

            return $rs;
        } catch (\Exception $ex) {
            $this->delete($key);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return $this->delegate->exists($key);
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        return $this->delegate->mtime($key);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return $this->delegate->keys();
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->delegate->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function setMetadata($key, $metadata)
    {
        if ($this->delegate instanceof MetadataSupporter) {
            $this->delegate->setMetadata($key, $metadata);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata($key)
    {
        if ($this->delegate instanceof MetadataSupporter) {
            return $this->delegate->getMetadata($key);
        }

        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        return $this->delegate->isDirectory($key);
    }

    protected function getAcl()
    {
        if (empty($this->users)) {
            return $this->aclConstant;
        }

        return $this->users;
    }

    private function updateAcl($key)
    {
        $response = $this->s3->set_object_acl($this->bucketName, $key, $this->getAcl());
        if (200 != $response->status) {
            throw new \RuntimeException('S3-ACL change failed: '.print_r($response, true));
        }
    }
}

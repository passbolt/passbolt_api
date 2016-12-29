Amazon S3 & AclAwareAmazonS3
============================

When using the legacy Amazon S3 adapters (aws-sdk-php < 2), you will need to specify a CA
certificate to be able to talk to Amazon servers in https. You can use
the one which is shipped with the SDK by defining before creating the
``\AmazonS3`` object:

```php
define("AWS_CERTIFICATE_AUTHORITY", true);
```

Specifying a custom CA certificate is not required when using the
`Gaufrette\Adapter\AmazonS3` adapter because it uses the newest version of the
AWS SDK for PHP.

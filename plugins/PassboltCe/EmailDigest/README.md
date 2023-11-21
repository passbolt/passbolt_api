# Introduction
After importing a large amount of resources, or any action generating a large amount of emails,
we would like the emails to be grouped by operator and template into one single email resuming
the action performed.

When the number of emails ina digest is below or equal 10, all the emails are rendered
within the email digest.

When the number of emails under a digest is above 10 emails, only a summary email is sent.

This document describes the approach taken.

# How to add a digest template

Typically, the digest templates are added in the plugins, e.g. in EmailDigestPlugin for the default email digests,
using the `DigestTemplateRegistry`:

```php
DigestTemplateRegistry::getInstance()
    ->addTemplate(new ResourceChangesDigestTemplate(5))
    ->addTemplate(new ResourceShareDigestTemplate(1))
    ...
```
The priority of the digest template may be passed in the constructor. Default is 10, the highest priority is 1.



# Technical specifications

This section describes the various components involved in the sending of email digest.

## `DigestTemplateRegistry`

This class gathers all the digest templates. Typically, it is used in the `EmailDigestPlugin::bootstrap();`
to register which digest templates are available. Note that templates are added in when performing an action
at the command line only.

## `AbstractDigestTemplate`

A digest template defines the email templates that will be covered by a digest, the priority
of a digest, the template used by this digest when the number of emails is above 10.

Default priority is 10, and a number below enhances the priority. So priority 1 is higher than priority 2.

You may follow the abstract methods of this abstract template to build a new digest template.

## `DigestsCollection`

When sending or previewing a batch of emails (per default 100, see `PASSBOLT_PLUGINS_EMAIL_DIGEST_BATCH_SIZE_LIMIT`),
the emails are attributed to a digest, if they are candidates to a digest, or will be
rendered as single emails. The `DigestsCollection` iterates through all the emails
to be sent and creates digests resp. assigns to these digests the emails candidate.

## `Digest`
A digest contains the emails for:
- a single operator
- a single recipient
- a single digest template

The `marshalEmails` method renders the emails within the digest, returning an `EmailDigest`.

## `EmailDigest`
This class is the conversion of a `Digest` into an object that can be sent as an email. It is the email realization
of a digest, with all the information needed to create an email to be sent.

# Summary
The developer registers `AbstractDigestTemplate`s. When emails are sent, these are grouped in `Digest`s of the same
recipient and operator by the `DigestsCollection`, or left as single emails if they do not find a matching digest template.

When sending the emails, each `Digest` is previewed resp. sent thanks to the `EmailDigest` class.


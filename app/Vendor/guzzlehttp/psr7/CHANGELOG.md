# CHANGELOG

## 1.2.3 - 2016-02-18

* Fixed support in `GuzzleHttp\Psr7\CachingStream` for seeking forward on remote
  streams, which can sometimes return fewer bytes than requested with `fread`.
* Fixed handling of gzipped responses with FNAME headers.

## 1.2.2 - 2016-01-22

* Added support for URIs without any authority.
* Added support for HTTP 451 'Unavailable For Legal Reasons.'
* Added support for using '0' as a filename.
* Added support for including non-standard ports in Host headers.

## 1.2.1 - 2015-11-02

* Now supporting negative offsets when seeking to SEEK_END.

## 1.2.0 - 2015-08-15

* Body as `"0"` is now properly added to a response.
* Now allowing forward seeking in CachingStream.
* Now properly parsing HTTP requests that contain proxy targets in
  `parse_request`.
* functions.php is now conditionally required.
* user-info is no longer dropped when resolving URIs.

## 1.1.0 - 2015-06-24

* URIs can now be relative.
* `multipart/form-data` headers are now overridden case-insensitively.
* URI paths no longer encode the following characters because they are allowed
  in URIs: "(", ")", "*", "!", "'"
* A port is no longer added to a URI when the scheme is missing and no port is
  present.

## 1.0.0 - 2015-05-19

Initial release.

Currently unsupported:

- `Psr\Http\Message\ServerRequestInterface`
- `Psr\Http\Message\UploadedFileInterface`

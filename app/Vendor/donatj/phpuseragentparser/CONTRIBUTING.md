# How to Contribute

## Reporting Issues

Issues can be reported via the [Github Issues](https://github.com/donatj/PhpUserAgent/issues) page.

- **Detail is key**: If a browser is being misidentified, one or more sample user agent strings are key to getting it resolved.
- **Missing Browser**: Is it modern? What is it being misidentified as? There are a lot of dead browsers out there that there is no reason to support.

Please do not file any requests for OS version identification. It is not a desired feature.

## Pull Requests

Pull requests are truly appreciated. While I try my best to stay on top of browsers hitting the market it is still a difficult task.

- **Formatting**: Indentation **must** use tabs. Please try to match internal formatting and spacing to existing code.
- **Tests**: If you're adding support for a new browser be sure to add test user agents for if at all possible ***every platform*** the browser is available on. Untested code will take much longer to be merged.
- **Terseness**: Try to be terse. Be clever. Take up as little space as possible. The point of this project initially was to be smaller than the other guys.

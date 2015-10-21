@property can.Map.validations.static.validationMessages validationMessages
@parent can.Map.validations 8

`validationMessages` has the default validation error messages that will be returned by the builtin
validation methods. These can be overwritten by assigning new messages
to `can.Map.validationMessages` in your application setup.

The following messages (with defaults) are available:

- format - "is invalid"
- inclusion - "is not a valid option (perhaps out of range)"
- lengthShort - "is too short"
- lengthLong - "is too long"
- presence - "can't be empty"
- range - "is out of range"

It is important to steal can/map/validations before
overwriting the messages, otherwise the changes will
be lost once steal loads it later.

## Example

	can.Map.validationMessages.format = "is invalid dummy!"
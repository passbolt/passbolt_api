Release song: https://www.youtube.com/watch?v=h7V36waLN0M

This hot-fix resolves a regression introduced in v5.3.0 that blocked the creation of standalone Custom Fields content types. A validation error in the API prevented these resources from being saved. With v5.3.1, the validation logic has been corrected, so users can now create and test Custom Fields content types as intended.

Thank you to everyone in the community who spotted the issue so quickly and helped us verify the fix!

## [5.3.1] - 2025-07-09
### Fixed
- PB-43748 Users are unable to save a new standalone custom field resource

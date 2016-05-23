# Migrating to Swagger-PHP v2.x

Swagger-PHP 2.x generates a swagger.json file that follows the swagger v2 specification.
If you need to output the older 1.2 specification use swagger-php 1.x

## Removed annotations

### @SWG\Resource
The concept "Resource" is no longer part of swagger spec.
All apis are now listed on swagger.json/paths

### @SWG\Api
The closest thing to @SWG\Api is @SWG\Path, but swagger-php 2.x will no longer require the @SWG\Path to group operations.
@SWG\Get(path="/api/items") will automaticly gerenerate and merge the operations based on the path.

### @SWG\Operation
Although a Operation annotation exists, swagger-php2 requires the use of the shorthand notation based on the http method.
So instead of 
```
@SWG\Operation(method="get", summary="...")`
```
write 
```
@SWG\Get(summary="...")
@SWG\Post(summary="...")
@SWG\Put(summary="...")
```
etc

### @SWG\Model
The closest thing to @SWG\Model is @SWG\Definition, which defines a Schema object that can be referenced from other annotations `@SWG\Schema($ref="#/definitions/$definition")`

### @SWG\Partial
Because swagger v2 allowes references, the @SWG\Partial has been removed.

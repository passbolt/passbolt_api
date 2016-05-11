<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="api_key"
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="petstore_auth",
 *   type="oauth2",
 *   authorizationUrl="http://petstore.swagger.io/api/oauth/dialog",
 *   flow="implicit",
 *   scopes={
 *     "read:pets": "read your pets",
 *     "write:pets": "modify pets in your account"
 *   }
 * )
 */

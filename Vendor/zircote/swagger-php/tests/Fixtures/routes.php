<?php
//
// Allow indentation with tab(s).
//
// http://www.doctrine-project.org/jira/browse/DCOM-255
// https://github.com/zircote/swagger-php/issues/168
// https://github.com/zircote/swagger-php/issues/203
//
// @codingStandardsIgnoreStart
//

   /**
    *
    *	@SWG\Put(
    * 		path="/users/{id}",
    * 		tags={"users"},
    * 		operationId="updateUser",
    * 		summary="Update user entry",
    * 		@SWG\Parameter(
    * 			name="id",
    * 			in="path",
    * 			required=true,
    * 			type="string",
    * 			description="UUID",
    * 		),
    * 		@SWG\Parameter(
    * 			name="user",
    * 			in="body",
    * 			required=true,
    * 			@SWG\Schema(ref="#/definitions/User"),
    *		),
    * 		@SWG\Response(
    * 			response=200,
    * 			description="success",
    * 		),
    * 		@SWG\Response(
    * 			response="default",
    * 			description="error",
    * 			@SWG\Schema(ref="#/definitions/Error"),
    * 		),
    * 	)
    * @SWG\Options(
    * path="/users/{id}",
    * @SWG\Response(response=200,description="Some CORS stuff")
    * )
    */
   Route::put('/users/{user_id}', 'UserController@update');

	/**
	 *
	 * 	@SWG\Delete(
	 * 		path="/users/{id}",
	 * 		tags={"users"},
	 * 		operationId="deleteUser",
	 * 		summary="Remove user entry",
	 * 		@SWG\Parameter(
	 * 			name="id",
	 * 			in="path",
	 * 			required=true,
	 * 			type="string",
	 * 			description="UUID",
	 * 		),
	 * 		@SWG\Response(
	 * 			response=200,
	 * 			description="success",
	 * 		),
	 * 		@SWG\Response(
	 * 			response="default",
	 * 			description="error",
	 * 			@SWG\Schema(ref="#/definitions/Error"),
	 * 		),
	 * 	)
	 *
	 */
	Route::delete('/users/{user_id}', 'UserController@destroy');

      /**
      *@SWG\Head(path="/users/{id}",@SWG\Response(response=200,description="Only checking if it exists"))
      */
     Route::get('/users/{user_id}', 'UserController@show');

//
// @codingStandardsIgnoreEnd
//

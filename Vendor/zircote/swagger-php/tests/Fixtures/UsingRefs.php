<?php
namespace SwaggerFixtures;

/**
 * @SWG\Info(title="Using a parameter definition", version="unittest")
 */
class UsingRefs
{

    /**
     * @SWG\Get(
     *   path="/pi/{item_name}",
     *   summary="Get protected item",
     *   @SWG\Parameter(ref="#/parameters/ItemName"),
     *   @SWG\Response(response="default", ref="#/responses/Item")
     * )
     */
    public function getProtectedItem()
    {
    }
}

/**
 * @SWG\Parameter(
 *   name="ItemName",
 *   in="path",
 *   type="string",
 *   required=true,
 *   description="protected item name",
 *   maxLength=256
 * )
 */

/**
 * @SWG\Response (
 *   response="Item",
 *   description="A protected item"
 * )
 */

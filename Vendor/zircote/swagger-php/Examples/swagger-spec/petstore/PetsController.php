<?php

namespace petstore;

class PetsController
{

    /**
     * @SWG\Get(
     *     path="/pets",
     *     summary="finds pets in the system",
     *     tags={"Pet Operations"},
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Pet")
     *         ),
     *         @SWG\Header(header="x-expires", type="string")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(
     *             ref="#/definitions/Error"
     *         )
     *     )
     * )
     */
    public function findPets()
    {
    }
}

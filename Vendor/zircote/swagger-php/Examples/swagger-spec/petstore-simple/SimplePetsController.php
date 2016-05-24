<?php

namespace Petstore;

class SimplePetsController
{

    /**
     * @SWG\Get(
     *     path="/pets",
     *     description="Returns all pets from the system that the user has access to",
     *     operationId="findPets",
     *     produces={"application/json", "application/xml", "text/xml", "text/html"},
     *     @SWG\Parameter(
     *         name="tags",
     *         in="query",
     *         description="tags to filter by",
     *         required=false,
     *         type="array",
     *         @SWG\Items(type="string"),
     *         collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="maximum number of results to return",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/pet")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(
     *             ref="#/definitions/errorModel"
     *         )
     *     )
     * )
     */
    public function findPets()
    {
    }

    /**
     * @SWG\Get(
     *     path="/pets/{id}",
     *     description="Returns a user based on a single ID, if the user does not have access to the pet",
     *     operationId="findPetById",
     *     @SWG\Parameter(
     *         description="ID of pet to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     produces={
     *         "application/json",
     *         "application/xml",
     *         "text/html",
     *         "text/xml"
     *     },
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(ref="#/definitions/pet")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     */
    public function findPetById()
    {
    }

    /**
     * @SWG\Post(
     *     path="/pets",
     *     operationId="addPet",
     *     description="Creates a new pet in the store.  Duplicates are allowed",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="pet",
     *         in="body",
     *         description="Pet to add to the store",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/petInput"),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(ref="#/definitions/pet")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     * @SWG\Definition(
     *     definition="petInput",
     *     allOf={
     *         @SWG\Schema(ref="pet"),
     *         @SWG\Schema(
     *             required={"name"},
     *             @SWG\Property(
     *                 property="id",
     *                 type="integer",
     *                 format="int64"
     *             )
     *         )
     *     }
     * )
     */
    public function addPet()
    {
    }

    /**
     * @SWG\Delete(
     *     path="/pets/{id}",
     *     description="deletes a single pet based on the ID supplied",
     *     operationId="deletePet",
     *     @SWG\Parameter(
     *         description="ID of pet to delete",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="pet deleted"
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     */
    public function deletePet()
    {
    }
}

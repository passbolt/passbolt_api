<?php

namespace PetstoreIO;

/**
 * @SWG\Definition(@SWG\Xml(name="Order"))
 */
class Order
{

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $petId;

    /**
     * @SWG\Property
     * @var bool
     */
    public $complete;

    /**
     * @SWG\Property(format="int32")
     * @var int
     */
    public $quantity;

    /**
     * @var \DateTime
     * @SWG\Property()
     */
    public $shipDate;

    /**
     * Order Status
     * @var string
     * @SWG\Property(enum={"placed","approved","delivered"})
     */
    public $status;
}

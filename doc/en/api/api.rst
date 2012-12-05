API
#########

Model behaviors are a way to organize some of the functionality
defined in CakePHP models. They allow us to separate and reuse logic that
creates a type of behavior, and they do this without requiring inheritance.  For
example creating tree structures. By providing a simple yet powerful way to
enhance models, behaviors allow us to attach functionality to models by defining
a simple class variable. That's how behaviors allow models to get rid of all the
extra weight that might not be part of the business contract they are modeling,
or that is also needed in different models and can then be extrapolated.

As an example, consider a model that gives us access to a database table which
stores structural information about a tree. Removing, adding, and migrating
nodes in the tree is not as simple as deleting, inserting, and editing rows in
the table. Many records may need to be updated as things move around. Rather
than creating those tree-manipulation methods on a per model basis (for every
model that needs that functionality), we could simply tell our model to use the
:php:class:`TreeBehavior`, or in more formal terms, we tell our model to behave
as a Tree.  This is known as attaching a behavior to a model. With just one line
of code, our CakePHP model takes on a whole new set of methods that allow it to
interact with the underlying structure.

CakePHP already includes behaviors for tree structures, translated content,
access control list interaction, not to mention the community-contributed
behaviors already available in the CakePHP Bakery (`http://bakery.cakephp.org
<http://bakery.cakephp.org>`_).  In this section, we'll cover the basic usage
pattern for adding behaviors to models, how to use CakePHP's built-in behaviors,
and how to create our own.

In essence, Behaviors are
`Mixins <http://en.wikipedia.org/wiki/Mixin>`_ with callbacks.

Using Behaviors
===============

Behaviors are attached to models through the ``$actsAs`` model class
variable::

    class Category extends AppModel {
        public $actsAs = array('Tree');
    }

This example shows how a Category model could be managed in a tree
structure using the TreeBehavior. Once a behavior has been
specified, use the methods added by the behavior as if they always
existed as part of the original model::

    // Set ID
    $this->Category->id = 42;

    // Use behavior method, children():
    $kids = $this->Category->children();

Some behaviors may require or allow settings to be defined when the
behavior is attached to the model. Here, we tell our TreeBehavior
the names of the "left" and "right" fields in the underlying
database table::

    class Category extends AppModel {
        public $actsAs = array('Tree' => array(
            'left'  => 'left_node',
            'right' => 'right_node'
        ));
    }

We can also attach several behaviors to a model. There's no reason
why, for example, our Category model should only behave as a tree,
it may also need internationalization support::

    class Category extends AppModel {
        public $actsAs = array(
            'Tree' => array(
              'left'  => 'left_node',
              'right' => 'right_node'
            ),
            'Translate'
        );
    }

So far we have been adding behaviors to models using a model class
variable. That means that our behaviors will be attached to our
models throughout the model's lifetime. However, we may need to
"detach" behaviors from our models at runtime. Let's say that on
our previous Category model, which is acting as a Tree and a
Translate model, we need for some reason to force it to stop acting
as a Translate model::

    // Detach a behavior from our model:
    $this->Category->Behaviors->unload('Translate');

That will make our Category model stop behaving as a Translate
model from thereon. We may need, instead, to just disable the
Translate behavior from acting upon our normal model operations:
our finds, our saves, etc. In fact, we are looking to disable the
behavior from acting upon our CakePHP model callbacks. Instead of
detaching the behavior, we then tell our model to stop informing of
these callbacks to the Translate behavior::

    // Stop letting the behavior handle our model callbacks
    $this->Category->Behaviors->disable('Translate');

We may also need to find out if our behavior is handling those
model callbacks, and if not we then restore its ability to react to
them::

    // If our behavior is not handling model callbacks
    if (!$this->Category->Behaviors->enabled('Translate')) {
        // Tell it to start doing so
        $this->Category->Behaviors->enable('Translate');
    }

Just as we could completely detach a behavior from a model at
runtime, we can also attach new behaviors. Say that our familiar
Category model needs to start behaving as a Christmas model, but
only on Christmas day::

    // If today is Dec 25
    if (date('m/d') == '12/25') {
        // Our model needs to behave as a Christmas model
        $this->Category->Behaviors->load('Christmas');
    }

We can also use the load method to override behavior settings::

    // We will change one setting from our already attached behavior
    $this->Category->Behaviors->load('Tree', array('left' => 'new_left_node'));

There's also a method to obtain the list of behaviors a model has
attached. If we pass the name of a behavior to the method, it will
tell us if that behavior is attached to the model, otherwise it
will give us the list of attached behaviors::

    // If the Translate behavior is not attached
    if (!$this->Category->Behaviors->attached('Translate')) {
        // Get the list of all behaviors the model has attached
        $behaviors = $this->Category->Behaviors->attached();
    }

Creating Behaviors
==================

Behaviors that are attached to Models get their callbacks called
automatically. The callbacks are similar to those found in Models:
``beforeFind``, ``afterFind``, ``beforeSave``, ``afterSave``, ``beforeDelete``,
``afterDelete`` and ``onError`` - see
:doc:`/models/callback-methods`.

Your behaviors should be placed in ``app/Model/Behavior``.  They are named in CamelCase and
postfixed by ``Behavior``, ex. NameBehavior.php.
It's often helpful to use a core behavior as a template when creating
your own. Find them in ``lib/Cake/Model/Behavior/``.

Every callback and behavior method takes a reference to the model it is being called
from as the first parameter.

Besides implementing the callbacks, you can add settings per behavior and/or
model behavior attachment. Information about specifying settings can be found in
the chapters about core behaviors and their configuration.

A quick example that illustrates how behavior settings can be
passed from the model to the behavior::

    class Post extends AppModel {
        public $actsAs = array(
            'YourBehavior' => array(
                'option1_key' => 'option1_value'
            )
        );
    }

Since behaviors are shared across all the model instances that use them, it's a
good practice to store the settings per alias/model name that is using the
behavior.  When created behaviors will have their ``setup()`` method called::

    public function setup(Model $Model, $settings = array()) {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = array(
                'option1_key' => 'option1_default_value',
                'option2_key' => 'option2_default_value',
                'option3_key' => 'option3_default_value',
            );
        }
        $this->settings[$Model->alias] = array_merge(
            $this->settings[$Model->alias], (array)$settings);
    }

Creating behavior methods
=========================

Behavior methods are automatically available on any model acting as
the behavior. For example if you had::

    class Duck extends AppModel {
        public $actsAs = array('Flying');
    }

You would be able to call ``FlyingBehavior`` methods as if they were
methods on your Duck model. When creating behavior methods you
automatically get passed a reference of the calling model as the
first parameter. All other supplied parameters are shifted one
place to the right. For example::

    $this->Duck->fly('toronto', 'montreal');

Although this method takes two parameters, the method signature
should look like::

    public function fly(Model $Model, $from, $to) {
        // Do some flying.
    }

Keep in mind that methods called in a ``$this->doIt()`` fashion
from inside a behavior method will not get the $model parameter
automatically appended.

Mapped methods
--------------

In addition to providing 'mixin' methods, behaviors can also provide pattern
matching methods. Behaviors can also define mapped methods.  Mapped methods use
pattern matching for method invocation. This allows you to create methods
similar to ``Model::findAllByXXX`` methods on your behaviors.  Mapped methods need
to be declared in your behaviors ``$mapMethods`` array.  The method signature for
a mapped method is slightly different than a normal behavior mixin method::

    class MyBehavior extends ModelBehavior {
        public $mapMethods = array('/do(\w+)/' => 'doSomething');

        public function doSomething(Model $model, $method, $arg1, $arg2) {
            debug(func_get_args());
            //do something
        }
    }

The above will map every ``doXXX()`` method call to the behavior.  As you can see, the model is
still the first parameter, but the called method name will be the 2nd parameter.  This allows
you to munge the method name for additional information, much like ``Model::findAllByXX``.  If the above
behavior was attached to a model the following would happen::

    $model->doReleaseTheHounds('homer', 'lenny');

    // would output
    'ReleaseTheHounds', 'homer', 'lenny'

Behavior callbacks
==================

Model Behaviors can define a number of callbacks that are triggered
before/after the model callbacks of the same name. Behavior
callbacks allow your behaviors to capture events in attached models
and augment the parameters or splice in additional behavior.

The available callbacks are:

-  ``beforeValidate`` is fired before a model's beforeValidate
-  ``beforeFind`` is fired before a model's beforeFind
-  ``afterFind`` is fired before a model's afterFind
-  ``beforeSave`` is fired before a model's beforeSave
-  ``afterSave`` is fired before a model's afterSave
-  ``beforeDelete`` is fired after a model's beforeDelete
-  ``afterDelete`` is fired before a model's afterDelete

Creating a behavior callback
----------------------------

.. php:class:: ModelBehavior

Model behavior callbacks are defined as simple methods in your
behavior class. Much like regular behavior methods, they receive a
``$Model`` parameter as the first argument. This parameter is the
model that the behavior method was invoked on.

.. php:method:: setup(Model $Model, array $settings = array())

    Called when a behavior is attached to a model.  The settings come from the
    attached model's ``$actsAs`` property.

.. php:method:: cleanup(Model $Model)

    Called when a behavior is detached from a model.  The base method removes
    model settings based on ``$model->alias``. You can override this method and
    provide custom cleanup functionality.

.. php:method:: beforeFind(Model $Model, array $query)

    If a behavior's beforeFind return's false it will abort the find().
    Returning an array will augment the query parameters used for the
    find operation.

.. php:method:: afterFind(Model $Model, mixed $results, boolean $primary)

    You can use the afterFind to augment the results of a find. The
    return value will be passed on as the results to either the next
    behavior in the chain or the model's afterFind.

.. php:method:: beforeDelete(Model $Model, boolean $cascade = true)

    You can return false from a behavior's beforeDelete to abort the
    delete. Return true to allow it continue.

.. php:method:: afterDelete(Model $Model)

    You can use afterDelete to perform clean up operations related to
    your behavior.

.. php:method:: beforeSave(Model $Model)

    You can return false from a behavior's beforeSave to abort the
    save. Return true to allow it continue.

.. php:method:: afterSave(Model $Model, boolean $created)

    You can use afterSave to perform clean up operations related to
    your behavior. $created will be true when a record is created, and
    false when a record is updated.

.. php:method:: beforeValidate(Model $Model)

    You can use beforeValidate to modify a model's validate array or
    handle any other pre-validation logic. Returning false from a
    beforeValidate callback will abort the validation and cause it to
    fail.



.. meta::
    :title lang=en: Behaviors
    :keywords lang=en: api

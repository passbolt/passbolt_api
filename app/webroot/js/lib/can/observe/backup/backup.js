//allows you to backup and restore a observe instance
steal('can/util', 'can/observe', 'can/util/object', function (can) {
	var flatProps = function (a) {
		var obj = {};
		for (var prop in a) {
			if (typeof a[prop] !== 'object' || a[prop] === null || a[prop] instanceof Date) {
				obj[prop] = a[prop]
			}
		}
		return obj;
	};

	can.extend(can.Observe.prototype, {

		/**
		 * @function can.Observe.prototype.backup
		 * @plugin can/observe/backup
		 * @parent can.Observe.backup
		 *
		 * `observe.backup()` backs up a [can.Observe] instance, so it can be restored later
		 * by calling [can.Observe.prototype.restore] or checked if it
		 * has changed with [can.Observe.prototype.isDirty]:
		 *
		 *      var recipe = new can.Observe({
		 *           name : 'Pancakes',
		 *           ingredients : [{
		 *               name : "eggs",
		 *               amount : '1'
		 *           }, {
		 *               name : "flour",
		 *               amount : '1 cup'
		 *           }, {
		 *               name : "milk",
		 *               amount : '1 1/4 cup'
		 *           }]
		 *       });
		 *
		 *       recipe.backup();
		 *
		 * @return {can.Observe} The observe instance
		 */
		backup : function () {
			this._backupStore = this._attrs();
			return this;
		},

		/**
		 * @function can.Observe.prototype.isDirty
		 * @plugin can/observe/backup
		 * @parent can.Observe.backup
		 *
		 * `observe.isDirty([checkAssociations])` returns if the observe has changed since the last
		 * [can.Observe.prototype.backup] call. If there is no backup it will return false. If you pass
		 * true, _isDirty_ also checks if any child properties or [can.Model] associations have changed.
		 *
		 *       var recipe = new can.Observe({
		 *           name : 'Pancakes',
		 *           ingredients : [{
		 *               name : "eggs",
		 *               amount : '1'
		 *           }, {
		 *               name : "flour",
		 *               amount : '1 cup'
		 *           }, {
		 *               name : "milk",
		 *               amount : '1 1/4 cup'
		 *           }]
		 *       });
		 *
		 *       recipe.backup();
		 *       // Change the attribute of a nested property
		 *       recipe.attr('ingredients.0.amount', '2');
		 *       recipe.isDirty() // -> false
		 *       recipe.isDirty(true) // -> true
		 *       recipe.attr('name', 'Eggcakes');
		 *       recipe.isDirty() // -> true
		 *
		 * @param {Boolean} [checkAssociations] Whether nested objects should be checked or
		 * not. Defaults to false.
		 * @return {Boolean} true if there are changes,
		 *   false if not or there is no backup
		 */
		isDirty : function (checkAssociations) {
			return this._backupStore &&
				!can.Object.same(this._attrs(),
					this._backupStore,
					undefined,
					undefined,
					undefined,
					!!checkAssociations);
		},

		/**
		 * @function can.Observe.prototype.restore
		 * @parent can.Observe.backup
		 *
		 * `observe.restore([restoreAssociations])` restores the observe to the state of the last time
		 * [can.Observe.prototype.backup] was called if [can.Observe.prototype.isDirty]
		 * returns true. If you pass true, _restore_ will also check and restore all nested properties
		 * and [can.Model] associations.
		 *
		 *      var recipe = new can.Observe({
		 *          name : 'Pancakes',
		 *          ingredients : [{
		 *              name : "eggs",
		 *              amount : '1'
		 *          }, {
		 *              name : "flour",
		 *              amount : '1 cup'
		 *          }, {
		 *              name : "milk",
		 *              amount : '1 1/4 cup'
		 *       }]});
		 *
		 *       recipe.backup();
		 *
		 *       // Change the attribute of a nested observe
		 *       recipe.attr('ingredients.0.amount', '2');
		 *       recipe.attr('name', 'Eggcakes');
		 *       recipe.attr('name') // -> Eggcakes
		 *       recipe.attr('ingredients.0.amount') // -> 2
		 *       recipe.restore(true);
		 *       recipe.attr('name') // -> Pancakes
		 *       recipe.attr('ingredients.0.amount') // -> 1
		 *
		 * @param {Boolean} [restoreAssociations] Whether nested objects should also
		 * be restored or not. Defaults to false.
		 * @return {can.Observe} The observe instance
		 */
		restore : function (restoreAssociations) {
			var props = restoreAssociations ? this._backupStore : flatProps(this._backupStore)

			if (this.isDirty(restoreAssociations)) {
				this._attrs(props);
			}

			return this;
		}

	})

	return can.Observe;
})



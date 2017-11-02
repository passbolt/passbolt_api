<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\Lib\Model;

Trait CommentsModelTrait {
	/**
	 * Asserts that an object has all the attributes a comment should have.
	 *
	 * @param object $comment
	 */
	protected function assertCommentAttributes($comment)
	{
		$attributes = ['id', 'parent_id', 'foreign_id', 'foreign_model', 'content', 'created', 'modified', 'created_by', 'modified_by'];
		$this->assertObjectHasAttributes($attributes, $comment);
	}
}

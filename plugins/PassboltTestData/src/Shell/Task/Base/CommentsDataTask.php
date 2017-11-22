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
namespace PassboltTestData\Shell\Task\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class CommentsDataTask extends DataTask
{
    public $entityName = 'Comments';

    /**
     * Get the comments data
     *
     * @return array
     */
    protected function _getData()
    {
        $comments[] = [
            'id' => UuidFactory::uuid('comment.id.apache-1'),
            'parent_id' => null,
            'user_id' => UuidFactory::uuid('user.id.irene'),
            'foreign_id' => UuidFactory::uuid('resource.id.apache'),
            'foreign_model' => 'Resource',
            'content' => 'this is a short comment',
            'created' => '2012-11-25 13:39:25',
            'modified' => '2012-11-25 13:39:25',
            'created_by' => UuidFactory::uuid('user.id.irene'),
            'modified_by' => UuidFactory::uuid('user.id.irene'),
        ];
        $comments[] = [
            'id' => UuidFactory::uuid('comment.id.apache-2'),
            'parent_id' => UuidFactory::uuid('comment.id.apache-1'),
            'user_id' => UuidFactory::uuid('user.id.irene'),
            'foreign_id' => UuidFactory::uuid('resource.id.apache'),
            'foreign_model' => 'Resource',
            'content' => 'this is a reply to the short comment',
            'created' => '2012-11-25 13:39:26',
            'modified' => '2012-11-25 13:39:26',
            'created_by' => UuidFactory::uuid('user.id.irene'),
            'modified_by' => UuidFactory::uuid('user.id.irene'),
        ];

        return $comments;
    }
}

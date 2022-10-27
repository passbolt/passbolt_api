<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.8.0
 */
namespace App\Error\Exception;

use Cake\Form\Form;
use Cake\Http\Exception\HttpException;

/**
 * Exception raised when a validation rule is not satisfied in a Form.
 */
class FormValidationException extends HttpException implements
    ExceptionWithErrorsDetailInterface
{
    /**
     * @inheritDoc
     */
    protected $_defaultCode = 400;

    /**
     * The form.
     *
     * @var \Cake\Form\Form
     */
    protected $form;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param \Cake\Form\Form $form The form
     * @param int|null $code The error code
     * @param \Throwable|null $previous the previous exception.
     */
    public function __construct(
        string $message,
        Form $form,
        ?int $code = null,
        ?\Throwable $previous = null
    ) {
        $this->form = $form;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->form->getErrors();
    }

    /**
     * @return \Cake\Form\Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }
}

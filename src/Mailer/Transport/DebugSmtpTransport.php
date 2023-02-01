<?php
declare(strict_types=1);

namespace App\Mailer\Transport;

/**
 * Smtp Transport class based on the CakePHP one, with tracing capabilities to help with debugging.
 */
class DebugSmtpTransport extends SmtpTransport
{
    /**
     * Client / server communication trace.
     *
     * @var array
     */
    protected $trace = [];

    /**
     * Extends _smtpSend by storing the communication trace.
     *
     * @param null|string $data data to be sent
     * @param string|false $checkCode check code
     * @return null|string
     */
    protected function _smtpSend(?string $data, $checkCode = '250'): ?string
    {
        $code = parent::_smtpSend($data, $checkCode);
        $this->_bufferTrace($data, $this->getLastResponse());

        return $code;
    }

    /**
     * Add trace in buffer.
     *
     * @param ?string $data data sent
     * @param array $response response received
     * @return array
     */
    protected function _bufferTrace(?string $data, array $response): array
    {
        $entry = [
            'cmd' => $data,
            'response' => $response,
        ];
        $this->trace[] = $entry;

        return $entry;
    }

    /**
     * Get trace.
     *
     * @return array
     */
    public function getTrace(): array
    {
        return $this->trace;
    }
}

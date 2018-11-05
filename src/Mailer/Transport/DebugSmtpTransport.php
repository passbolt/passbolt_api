<?php
namespace App\Mailer\Transport;

/**
 * Smtp Transport class based on the CakePHP one, with tracing capabilities to help with debugging.
 */
class DebugSmtpTransport extends \Cake\Mailer\Transport\SmtpTransport
{
    /**
     * Client / server communication trace.
     * @var array
     */
    protected $trace = [];

    /**
     * Extends _smtpSend by storing the communication trace.
     * @param null|string $data data to be sent
     * @param string $checkCode check code
     * @return null|string
     */
    protected function _smtpSend($data, $checkCode = '250')
    {
        $code = parent::_smtpSend($data, $checkCode);
        $this->_bufferTrace($data, $this->getLastResponse());

        return $code;
    }

    /**
     * Add trace in buffer.
     * @param string $data data sent
     * @param array $response response received
     * @return array
     */
    protected function _bufferTrace($data, $response)
    {
        $entry = [
            'cmd' => $data,
            'response' => $response
        ];
        $this->trace[] = $entry;

        return $entry;
    }

    /**
     * Get trace.
     * @return array
     */
    public function getTrace()
    {
        return $this->trace;
    }
}

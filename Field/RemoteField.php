<?php
namespace Mezon\Gui\Field;

/**
 * Class RemoteField
 *
 * @package Field
 * @subpackage RemoteField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Remote field control
 */
class RemoteField extends \Mezon\Gui\Field
{

    /**
     * Session id
     *
     * @var string
     */
    protected $sessionId = '';

    /**
     * Remote source of records
     *
     * @var string
     */
    protected $remoteSource = '';

    /**
     * Method fetches session id from the description
     *
     * @param array $fieldDescription
     *            Field description
     */
    protected function initSessionId(array $fieldDescription)
    {
        if (isset($fieldDescription['session-id'])) {
            $this->sessionId = $fieldDescription['session-id'];
        } else {
            throw (new \Exception('Session id is not defined', - 1));
        }
    }

    /**
     * Method fetches remote source from the description
     *
     * @param array $fieldDescription
     *            Field description
     */
    protected function initRemoteSource(array $fieldDescription)
    {
        if (isset($fieldDescription['remote-source'])) {
            $this->remoteSource = $fieldDescription['remote-source'];
        } else {
            throw (new \Exception('Remote source of records is not defined', - 1));
        }
    }

    /**
     * Constructor
     *
     * @param array $fieldDescription
     *            Field description
     * @param string $value
     *            Field value
     */
    public function __construct(array $fieldDescription, string $value = '')
    {
        parent::__construct($fieldDescription, $value);

        $this->initSessionId($fieldDescription);

        $this->initRemoteSource($fieldDescription);
    }

    /**
     * Getting service client
     *
     * @return \Mezon\CrudService\CrudServiceClient Service client
     */
    protected function getClient(): \Mezon\CrudService\CrudServiceClient
    {
        // TODO exclude to separate package
        // @codeCoverageIgnoreStart
        $externalRecords = new \Mezon\CrudService\CrudServiceClient($this->remoteSource);
        $externalRecords->setToken($this->sessionId);
        return $externalRecords;
        // @codeCoverageIgnoreEnd
    }
}

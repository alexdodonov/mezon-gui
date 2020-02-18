<?php
namespace Mezon\Gui\Field;

/**
 * Class TextField
 *
 * @package Field
 * @subpackage TextField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Text field control
 */
class TextField implements \Mezon\Gui\Control
{

    /**
     * Text content
     *
     * @var string
     */
    protected $text = '';

    /**
     * Constructor
     *
     * @param array $fieldDescription
     *            Field description
     */
    public function __construct(array $fieldDescription)
    {
        if (isset($fieldDescription['text'])) {
            $this->text = $fieldDescription['text'];
        }
    }

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        return $this->text;
    }

    /**
     * Does control fills all row
     */
    public function fillAllRow(): bool
    {
        return true;
    }
}

<?php
namespace Mezon\Gui\ListBuilder;

/**
 * Interface ListBuilderAdapter
 *
 * @package ListBuilder
 * @subpackage ListBuilderAdapter
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/30)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Interace for all adapters
 */
interface ListBuilderAdapter
{

    /**
     * Method returns all vailable records
     *
     * @return array all vailable records
     */
    public function all(): array;

    /**
     * Method returns a subset from vailable records
     *
     * @param array $order
     *            order settings
     * @param int $from
     *            the beginning of the bunch
     * @param int $limit
     *            the size of the batch
     * @return array subset from vailable records
     */
    public function getRecords(array $order, int $from, int $limit): array;

    /**
     * Record preprocessor
     *
     * @param array $record
     *            record to be preprocessed
     * @return array preprocessed record
     */
    public function preprocessListItem(array $record): array;
}

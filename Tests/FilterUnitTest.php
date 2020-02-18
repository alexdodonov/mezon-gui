<?php

class FilterUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing addFilterConditionFromArr method
     */
    function testAddFilterConditionFromArr()
    {
        // setup and test body
        $result = \Mezon\Gui\FieldsAlgorithms\Filter::addFilterConditionFromArr(
            [
                [
                    'arg1' => '$id',
                    'op' => '>',
                    'arg2' => '1',
                ],
                [
                    'arg1' => '$id',
                    'op' => 'in',
                    'arg2' => [
                        1,
                        2
                    ]
                ],
                [
                    'arg1' => [
                        'arg1' => '$id',
                        'op' => 'in',
                        'arg2' => [
                            3,
                            4
                        ]
                    ],
                    'op' => '=',
                    'arg2' => 'true'
                ]
            ],
            []);

        // asssertions
        $this->assertContains('id > 1', $result);
        $this->assertContains('id in ( 1 , 2 )', $result);
        $this->assertContains('( id in ( 3 , 4 ) ) = \'true\'', $result);
    }

    /**
     * Testing addFilterConditionFromArr method
     */
    function testAddFilterConditionFromArrException()
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup and test body
        \Mezon\Gui\FieldsAlgorithms\Filter::addFilterConditionFromArr(
            [
                [
                    'arg1' => '$id',
                    'op' => '<>',
                    'arg2' => '1',
                ]
            ],
            []);
    }

    /**
     * Testing addFilterConditionFromArr method
     */
    function testAddFilterConditionFromArrSimple()
    {
        // setup and test body
        $result = \Mezon\Gui\FieldsAlgorithms\Filter::addFilterConditionFromArr(
            [
                'field1' => 1,
                'field2' => 'null',
                'field3' => 'not null',
                'field4' => 'some string',
            ],
            []);

        // asssertions
        $this->assertContains('field1 = 1', $result, 'Integer compilation error');
        $this->assertContains('field2 IS NULL', $result, 'Null compilation error');
        $this->assertContains('field3 IS NOT NULL', $result, 'Not null compilation error');
        $this->assertContains('field4 LIKE "some string"', $result, 'String compilation error');
    }
}

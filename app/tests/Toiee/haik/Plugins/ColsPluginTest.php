<?php
use Toiee\haik\Plugins\Cols\ColsPlugin;


class ColsPluginTest extends TestCase {

    public function testConvertMethodExists()
    {
        $this->assertInternalType('string', with(new ColsPlugin)->convert());
    }

    public function testParameter()
    {
        $tests = array(
            'no_params' => array(
                'cols'   => array(),
                'assert' => array(
                    array (
                        'cols'   => 12,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
            'only1num' => array(
                'cols'   => array(3),
                'assert' => array(
                    array (
                        'cols'   => 3,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
            'only1nums' => array(
                'cols'   => array(3,4),
                'assert' => array(
                    array (
                        'cols'   => 3,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                    array (
                        'cols'   => 4,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
            'offset' => array(
                'cols'   => array('3+3'),
                'assert' => array(
                    array (
                        'cols'   => 3,
                        'offset' => 3,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
            'offsets' => array(
                'cols'   => array('3+3','2+1','1+2'),
                'assert' => array(
                    array (
                        'cols'   => 3,
                        'offset' => 3,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                    array (
                        'cols'   => 2,
                        'offset' => 1,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                    array (
                        'cols'   => 1,
                        'offset' => 2,
                        'class'  => '',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
            'colClass' = array(
                'cols'   => array('1.colOneClass, 2.colTwoClass'),
                'assert' => array(
                    array (
                        'cols'   => 1,
                        'offset' => 0,
                        'class'  => 'colOneClass',
                        'style'  => '',
                        'body'   => '',
                    ),
                    array (
                        'cols'   => 2,
                        'offset' => 0,
                        'class'  => 'colTwoClass',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),
        );

        foreach ($tests as $key => $data)
        {
            $cols = new ColsPlugin;
            $cols->convert($data['cols'], '');
            $this->assertAttributeSame($data['assert'], 'cols', $cols);
        }
    }

    public function testOverMaxCols()
    {
        $this->markTestIncomplete('This implements not yet');
    }

}
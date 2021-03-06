<?php
use Toiee\haik\Plugins\Cols\ColsPlugin;


class ColsPluginTest extends TestCase {

    public function testConvertMethodExists()
    {
        $this->assertInternalType('string', with(new ColsPlugin)->convert());
    }

    /**
     * @dataProvider paramProvider
     */
    public function testParameter($cols, $assert)
    {
        $plugin = new ColsPlugin;
        $plugin->convert($cols, '');
        $this->assertAttributeSame($assert, 'cols', $plugin);
    }
    
    public function paramProvider()
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
            'colClass' => array(
                'cols'   => array('1.colOneClass', '2.colTwoClass'),
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
            'manyclass' => array(
                'cols'   => array('3+2.starbuks', '4.soy.late'),
                'assert' => array(
                    array (
                        'cols'   => 3,
                        'offset' => 2,
                        'class'  => 'starbuks',
                        'style'  => '',
                        'body'   => '',
                    ),
                    array (
                        'cols'   => 4,
                        'offset' => 0,
                        'class'  => 'soy late',
                        'style'  => '',
                        'body'   => '',
                    ),
                )
            ),

        );
        
        return $tests;
    }

    /**
     * @dataProvider pluginClassProvider
     */
    public function testPluginClass($cols, $assert)
    {
        $plugin = new ColsPlugin;
        $plugin->convert($cols, '');
        $this->assertAttributeSame($assert, 'className', $plugin);
    }
    
    public function pluginClassProvider()
    {
        $tests = array(
            'classname' => array(
                'cols'   => array('class=test-class'),
                'assert' => 'test-class',
            ),
            'no-classname' => array(
                'cols'   => array('class='),
                'assert' => '',
            ),
        );
        
        return $tests;
    }

    /**
     * @dataProvider delimiterProvider
     */
    public function testDeleimiter($cols, $assert)
    {
        $plugin = new ColsPlugin;
        $plugin->convert($cols, '');
        $this->assertAttributeSame($assert, 'delimiter', $plugin);
    }
    
    public function delimiterProvider()
    {
        $tests = array(
            'delimiter' => array(
                'cols'   => array('++++'),
                'assert' => "\n++++\n",
            ),
            'no-delimiter' => array(
                'cols'   => array(),
                'assert' => "\n====\n",
            ),
        );
        
        return $tests;
    }
    
    /**
     * @dataProvider bodyProvider
     */
    public function testParseBody($body, $assert)
    {
        $plugin = new ColsPlugin;
        $plugin->convert(array(), $body);
        $this->assertAttributeSame($assert, 'cols', $plugin);
    }
    
    public function bodyProvider()
    {
        $tests = array(
            'none' => array(
                'body'   => "str1\nstr2",
                'assert' => array(
                    array (
                        'cols'   => 12,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => "str1\nstr2",
                    ),
                ),
            ),
            '2cols' => array(
                'body'   => "str1\n====\nstr2",
                'assert' => array(
                    array (
                        'cols'   => 6,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => "str1",
                    ),
                    array (
                        'cols'   => 6,
                        'offset' => 0,
                        'class'  => '',
                        'style'  => '',
                        'body'   => "str2",
                    ),
                ),
            ),
        );
        
        return $tests;
    }
    
    public function testHtml()
    {
        $tests = array(
            'no_params' => array(
                'cols' => array(),
                'body' => 'test',
                'assert' => '<div class="haik-plugin-cols row">'.
                            '<div class="col-sm-12">'.\Parser::parse('test').'</div>'.
                            '</div>',
            ),
            'class' => array(
                'cols' => array(),
                'body' => "CLASS:hogeclass\ntest",
                'assert' => '<div class="haik-plugin-cols row">'.
                            '<div class="col-sm-12 hogeclass">'.\Parser::parse('test').'</div>'.
                            '</div>',
            ),
            'style' => array(
                'cols' => array(),
                'body' => "STYLE:background-color:#330000;color:#fff;\ntest",
                'assert' => '<div class="haik-plugin-cols row">'.
                            '<div class="col-sm-12" style="background-color:#330000;color:#fff;">'.\Parser::parse('test').'</div>'.
                            '</div>',
            ),
            'all' => array(
                'cols' => array('3+2','4.starbucks','++++', 'class=late'),
                'body' => "STYLE:background-color:#000;color:#ccc;\n".
                          "CLASS:burbon\n".
                          "col1\n".
                          "\n++++\n".
                          "col2\n".
                          "col3",
                'assert' => '<div class="haik-plugin-cols row late">'.
                            '<div class="col-sm-3 col-sm-offset-2 burbon" style="background-color:#000;color:#ccc;">'.\Parser::parse("col1").'</div>'.
                            '<div class="col-sm-4 starbucks">'.\Parser::parse("col2\ncol3").'</div>'.
                            '</div>',
            ),
            'nodelimiter' => array(
                'cols' => array('3+2','4+1.starbucks','class=tea'),
                'body' => "STYLE:background-color:#000;color:#ccc;\n".
                          "CLASS:burbon\n".
                          "col1\n".
                          "\n====\n".
                          "STYLE:background-color:#f33;color:#222;\n".
                          "CLASS:cafe\n".
                          "col2\n".
                          "col3",
                'assert' => '<div class="haik-plugin-cols row tea">'.
                            '<div class="col-sm-3 col-sm-offset-2 burbon" style="background-color:#000;color:#ccc;">'.\Parser::parse("col1").'</div>'.
                            '<div class="col-sm-4 col-sm-offset-1 starbucks cafe" style="background-color:#f33;color:#222;">'.\Parser::parse("col2\ncol3").'</div>'.
                            '</div>',
            ),
            'diffColsOverBody' => array(
                'cols' => array(6,6),
                'body' => "col1\n".
                          "\n====\n".
                          "col2\n".
                          "\n====\n".
                          "col3",
                'assert' => '<div class="haik-plugin-cols row">'.
                            '<div class="col-sm-6">'.\Parser::parse("col1").'</div>'.
                            '<div class="col-sm-6">'.\Parser::parse("col2\n\n====\ncol3").'</div>'.
                            '</div>',
            ),
            'diffColsLessBody' => array(
                'cols' => array(4,4,4),
                'body' => "col1\n".
                          "\n====\n".
                          "col2",
                'assert' => '<div class="haik-plugin-cols row">'."\n".
                            '<div class="col-sm-4">'.\Parser::parse("col1").'</div>'.
                            '<div class="col-sm-4">'.\Parser::parse("col2").'</div>'.
                            '<div class="col-sm-4">'.'</div>'.
                            '</div>',
            ),
        );

        foreach ($tests as $key => $data)
        {
            $data['assert'] = preg_replace('/\n| {2,}/', '', trim($data['assert']));
            $cmpdata = with(new ColsPlugin)->convert($data['cols'], $data['body']);
            $cmpdata = preg_replace('/\n| {2,}/', '', trim($cmpdata));
            $this->assertEquals($data['assert'], $cmpdata);
        }
    }
    

    public function testOverMaxCols()
    {
        $tests = array(
            'notAuth' => array(
                'cols' => array(7,7),
                'body' => "test1\n====\ntest2",
                'assert' => '<div class="haik-plugin-cols row">'."\n".
                            '  <div class="col-sm-7">'."\n".'      '.\Parser::parse('test1').'    </div>'."\n".
                            '  <div class="col-sm-7">'."\n".'      '.\Parser::parse('test2').'    </div>'."\n".
                            '</div>',
            ),
            'Auth' => array(
                'cols' => array(7,7),
                'body' => "test1\n====\ntest2",
                'assert' => '<div class="haik-plugin-alert alert alert-danger alert-dismissable">'.
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.
                            '<p>There are over 12 columns.</p>'."\n".
                            '</div>'."\n".
                            '<div class="haik-plugin-cols row">'."\n".
                            '  <div class="col-sm-7">'."\n".'      '.\Parser::parse('test1').'    </div>'."\n".
                            '  <div class="col-sm-7">'."\n".'      '.\Parser::parse('test2').'    </div>'."\n".
                            '</div>',
            ),
        );


        foreach ($tests as $key => $data)
        {
            if ($key === 'Auth')
            {
                $user = User::where('email', 'touch@toiee.jp')->first();
                $this->be($user);
            }
            $data['assert'] = preg_replace('/\n| {2,}/', '', trim($data['assert']));
            $cmpdata = with(new ColsPlugin)->convert($data['cols'], $data['body']);
            $cmpdata = preg_replace('/\n| {2,}/', '', trim($cmpdata));

            $this->assertEquals($data['assert'], $cmpdata);
        }
    
    
        $this->markTestIncomplete('This implements not yet');
    }

}
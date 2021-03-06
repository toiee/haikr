<?php
use Toiee\haik\Plugins\Slide\SlidePlugin;

class SlidePluginTest extends TestCase {

    public function testConvertMethodExists()
    {
        $this->assertInternalType('string', with(new SlidePlugin)->convert());
    }

    public function testDefaultImage()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/900x500.png" alt="">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>test title</h3>'."\n"
                      . '        <p>test</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "#### test title\n"
              . "test\n";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testSetImage()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>test title</h3>'."\n"
                      . '        <p>test</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "![alt](http://placehold.jp/1000x400.png)\n"
              . "#### test title\n"
              . "test\n";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testNoHeadingAndBody()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "![alt](http://placehold.jp/1000x400.png)";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testSetOnlyHeading()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/900x500.png" alt="">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>test title</h3>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "#### test title";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testSetOnlyBody()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/900x500.png" alt="">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <p>test</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "test";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testSetHeadingAndBody()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/900x500.png" alt="">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>test title</h3>'."\n"
                      . '        <p>test</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = "#### test title\n"
              . "test\n";

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testEmptyItem()
    {
        $body = '';
        $expected = array();

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);
        
        $this->assertAttributeEquals($expected, 'items', $slide_obj);


        $body = "====\n====\n";
        $expected = array();

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }

    public function testFullItem()
    {
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Third Slide' . "\n"
              . 'This is first slide.';
        $expected = array(array(
            'image' => '<img src="http://placehold.jp/1000x400.png" alt="alt">',
            'heading' => '<h3>Third Slide</h3>',
            'body' => '<p>This is first slide.</p>',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }

    public function testOnlyImage()
    {
        $body = '![alt](http://placehold.jp/1000x400.png)';
        $expected = array(array(
            'image' => '<img src="http://placehold.jp/1000x400.png" alt="alt">',
            'body'  => '',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }

    public function testOnlyBody()
    {
        $body = 'This is first slide.';
        $expected = array(array(
            'body' => '<p>This is first slide.</p>',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }

    public function testOnlyHeading()
    {
        $body = '### Third Slide';
        $expected = array(array(
            'heading' => '<h3>Third Slide</h3>',
            'body'  => '',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }

    public function testHeadingAndBody()
    {
        $body = '### Third Slide' . "\n"
              . 'This is first slide.';
        $expected = array(array(
            'heading' => '<h3>Third Slide</h3>',
            'body' => '<p>This is first slide.</p>',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);        
    }
    
    public function testImageAndBody()
    {
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . 'This is first slide.';
        $expected = array(array(
            'image' => '<img src="http://placehold.jp/1000x400.png" alt="alt">',
            'body' => '<p>This is first slide.</p>',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);
    }
    
    public function testImageAndHeading()
    {
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Third Slide';
        $expected = array(array(
            'image' => '<img src="http://placehold.jp/1000x400.png" alt="alt">',
            'heading' => '<h3>Third Slide</h3>',
            'body'  => '',
        ));

        $slide_obj = new SlidePlugin();
        $result = $slide_obj->convert(array(), $body);

        $this->assertAttributeEquals($expected, 'items', $slide_obj);        
    }

    public function testMultiSlides()
    {
        # This is the test of array of items are 2.
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array();
        $expected = 2;
        $slide_obj->convert($params, $body);

        $this->assertAttributeCount($expected, 'items', $slide_obj);


        # This is the test of array of items are 3.
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array();
        $expected = 3;
        $slide_obj->convert($params, $body);

        $this->assertAttributeCount($expected, 'items', $slide_obj);
    }

    public function testOneSlideWithNoButton()
    {
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array();
        $expected = array(
            'indicatorsSet' => false,
            'controlsSet'   => false,
        );
        $slide_obj->convert($params, $body);

        $this->assertAttributeEquals($expected, 'options', $slide_obj);
    }

    public function testWithNoButton()
    {
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array('nobutton');
        $expected = array(
            'indicatorsSet' => false,
            'controlsSet'   => false,
        );
        $slide_obj->convert($params, $body);

        $this->assertAttributeEquals($expected, 'options', $slide_obj);
    }

    public function testWithNoIndicator()
    {
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array('noindicator');
        $expected = array(
            'indicatorsSet' => false,
            'controlsSet'   => true,
        );
        $slide_obj->convert($params, $body);

        $this->assertAttributeEquals($expected, 'options', $slide_obj);
    }

    public function testWithNoControls()
    {
        $slide_obj = new SlidePlugin();
        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $params = array('noslidebutton');
        $expected = array(
            'indicatorsSet' => true,
            'controlsSet'   => false,
        );
        $slide_obj->convert($params, $body);

        $this->assertAttributeEquals($expected, 'options', $slide_obj);
    }

    public function testNoIndicatorAndControl()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array('nobutton'),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '    <div class="item">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testNoIndicator()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array('noindicator'),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '    <div class="item">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '  <!-- Controls -->'."\n"
                      . '  <a class="left carousel-control" href="#haik_plugin_slide_'.$id.'" data-slide="prev">'."\n"
                      . '    <span class="glyphicon glyphicon-chevron-left"></span>'."\n"
                      . '  </a>'."\n"
                      . '  <a class="right carousel-control" href="#haik_plugin_slide_'.$id.'" data-slide="next">'."\n"
                      . '    <span class="glyphicon glyphicon-chevron-right"></span>'."\n"
                      . '  </a>'."\n"
                      . '</div>',
        );

        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testNoControl()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array('noslidebutton'),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Indicators -->'."\n"
                      . '  <ol class="carousel-indicators">'."\n"
                      . '    <li data-target="#haik_plugin_slide_'.$id.'" data-slide-to="0"></li>'."\n"
                      . '    <li data-target="#haik_plugin_slide_'.$id.'" data-slide-to="1"></li>'."\n"
                      . '  </ol>'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '    <div class="item">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '</div>',
        );

        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }

    public function testSetIndicatorAndControl()
    {
        $slide_obj = new SlidePlugin();
        $id = $slide_obj->getId();

        $test = array(
            'slide'  => array(),
            'assert' => '<div id="haik_plugin_slide_'.$id.'" class="haik-plugin-slide carousel slide" data-ride="carousel">'."\n"
                      . '  <!-- Indicators -->'."\n"
                      . '  <ol class="carousel-indicators">'."\n"
                      . '    <li data-target="#haik_plugin_slide_'.$id.'" data-slide-to="0"></li>'."\n"
                      . '    <li data-target="#haik_plugin_slide_'.$id.'" data-slide-to="1"></li>'."\n"
                      . '  </ol>'."\n"
                      . '  <!-- Wrapper for slides -->'."\n"
                      . '  <div class="carousel-inner">'."\n"
                      . '    <div class="item active">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '    <div class="item">'."\n"
                      . '      <img src="http://placehold.jp/1000x400.png" alt="alt">'."\n"
                      . '      <div class="carousel-caption">'."\n"
                      . '        <h3>Heading</h3>'."\n"
                      . '        <p>Body</p>'."\n"
                      . '      </div>'."\n"
                      . '    </div>'."\n"
                      . '  </div>'."\n"
                      . '  <!-- Controls -->'."\n"
                      . '  <a class="left carousel-control" href="#haik_plugin_slide_'.$id.'" data-slide="prev">'."\n"
                      . '    <span class="glyphicon glyphicon-chevron-left"></span>'."\n"
                      . '  </a>'."\n"
                      . '  <a class="right carousel-control" href="#haik_plugin_slide_'.$id.'" data-slide="next">'."\n"
                      . '    <span class="glyphicon glyphicon-chevron-right"></span>'."\n"
                      . '  </a>'."\n"
                      . '</div>',
        );

        $body = '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body' . "\n"
              . '====' . "\n"
              . '![alt](http://placehold.jp/1000x400.png)' . "\n"
              . '### Heading' . "\n"
              . 'Body';

        $expect_return = preg_replace('/\n| {2,}/', '', trim($test['assert']));
        $actual_return = $slide_obj->convert($test['slide'], $body);
        $actual_return = preg_replace('/\n| {2,}/', '', trim($actual_return));

        $this->assertEquals($expect_return, $actual_return);
    }
}

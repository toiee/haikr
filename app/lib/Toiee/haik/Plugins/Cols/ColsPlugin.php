<?php
namespace Toiee\haik\Plugins\Cols;

use Toiee\haik\Plugins\Plugin;
use Toiee\haik\Plugins\Utility;

class ColsPlugin extends Plugin {

    const COL_MAX_NUM     = 12;
    const COL_DELIMITER   = "\n====\n";

    protected $className;
    protected $delimiter;

    protected $cols;
    protected $colBase;
    
    protected $params;
    protected $body;
    
    protected $totalColNum;

    
    public function __construct()
    {
        parent::__construct();

        $this->colBase = array (
            'cols'   => self::COL_MAX_NUM,
            'offset' => 0,
            'class'  => '',
            'style'  => '',
            'body'   => '',
        );

        $this->cols = array();
        $this->totalColNum = 0;
        $this->className = '';
        $this->delimiter = self::COL_DELIMITER;
    }

    /**
     * convert call via HaikMarkdown :::{plugin-name(...):::
     * @params array $params
     * @params string $body when {...} was set
     * @return string converted HTML string
     * @throws RuntimeException when unimplement
     */
    public function convert($params = array(), $body = '')
    {

        // set params
        $this->params = $params;
        $this->body = $body;

        
        $this->parseParams();
        $this->parseBody();
        
        $html = $this->getHtml();
        
        // When over max col, show alert message if auth.
        $alert = '';
        if ($this->totalColNum > self::COL_MAX_NUM)
        {
            if (\Auth::check())
            {
                $message = 'There are over '. self::COL_MAX_NUM . ' columns.';
                $alert = with(\Plugin::get('alert'))->convert(array('danger', 'close'), $message);
                $html = $alert . "\n" . $html;
            }
        }

        return $html;
    }

    /**
     * parse params
     */
    protected function parseParams()
    {

        foreach ($this->params as $param)
        {
            $cols = Utility::parseColumnData($param);
            if ( ! $cols)
            {
                if (preg_match('/^class=(.+)$/', $param, $mts))
                {
                    // if you want add class to top div
                    $this->className = trim($mts[1]);
                }
                else
                {
                    // if you want change delimiter
                    $this->delimiter = "\n" . trim($param) . "\n";
                }
                continue;
            }

            $cols = array_merge($this->colBase, $cols);
            $this->cols[] = $cols;
            $this->totalColNum += $cols['cols'];
        }
    }

    /**
     * parse body
     */
    protected function parseBody()
    {
        if (count($this->cols) === 0)
        {
            // if parameter is not set then make cols with body
        	$data = explode($this->delimiter, $this->body);
    		$col_num = (int)(self::COL_MAX_NUM / count($data));
    		for ($i = 0; $i < count($data); $i++)
    		{
    		    $col['cols'] = $col_num;
                $this->cols[$i] = array_merge($this->colBase, $col);
    		}
        }
        
        // if parameter and body delimiter is not match then bind body over cols 
        $data = array_pad(explode($this->delimiter, $this->body, count($this->cols)), count($this->cols), '');
    	for($i = 0; $i < count($this->cols); $i++)
    	{
    		if (isset($data[$i]))
    		{
    		    if (preg_match('/(?:^|\n)STYLE:(.+?)\n/', $data[$i], $mts))
    		    {
    		        $this->cols[$i]['style'] = trim($mts[1]);
        		    $data[$i] = preg_replace('/'.preg_quote($mts[0], '/'). '/', '', $data[$i], 1);
    		    }

    		    if (preg_match('/(?:^|\n)CLASS:(.+?)\n/', $data[$i], $mts))
    		    {
    		        $this->cols[$i]['class'] = trim($this->cols[$i]['class'] . ' ' . trim($mts[1]));
        		    $data[$i] = preg_replace('/'.preg_quote($mts[0], '/'). '/', '', $data[$i], 1);
    		    }

    		    $this->cols[$i]['body'] = $data[$i];
    		}
    	}
    }
    
    /**
     * get html
     * @return string $html cols html
     */
    protected function getHtml()
    {
        $cols = array();
        $cols['row_class'] = $this->className ? $this->className : '';

        foreach ($this->cols as $col)
        {
            $coldata = array();
            $coldata['class'] = Utility::createColumnClass($col);
            $coldata['style'] = $col['style']  ? $col['style'] : '';
            $coldata['body'] = $col['body'];

            $cols['data'][] = $coldata;
        }

        return $this->format($cols);
    }

    /**
     * get formated html
     * @params array $data col options data
     * @return string $html formated col html
     */
    protected function format($data)
    {
        foreach ($data['data'] as $key => $col)
        {
            $data['data'][$key]['body'] = \Parser::parse($col['body']);
        }

        return self::renderView('template', $data);
    }
}
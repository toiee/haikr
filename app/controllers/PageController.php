<?php

use Michelf\MarkdownExtra;

class PageController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $pagename = '';
        if (Request::isMethod('post'))
        {
            $pagename = Input::get('name');

            // editに飛ばす
            return Redirect::to('haik--admin/edit/'.$pagename);
        }

        $this->layout = View::make('settings.layouts.editor')->with(array(
           'name' => $pagename,
           'view' => 'settings.create',
           'nav' => 'settings.includes.nav',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
        if (Request::isMethod('post'))
        {
            $pagename = Input::get('name');
            $page = Page::where('name', $pagename)->first();
            
            if ( ! $page)
            {
                $page = new Page;
                $page->name = $pagename;
                $page->haik_site_id = 1;
            }
            $page->body = Input::get('body');
            $page->title = Input::get('title');
            
            
            if ($page->save())
            {
                
            }
            else
            {
                
            }
            
            return Redirect::to(Haik::pageUrl($pagename));
        }
        return Redirect::to(Haik::pageUrl($pagename));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($pagename = '')
    {
        if ($pagename === '')
        {
            $pagename = Config::get('app.haik.defaultPage');
        }

        $page = Page::where('name', $pagename)->first();
        
        if ($page)
        {
            $title = $page->title;
            $text = $page->body;
        }
        else if (Auth::check())
        {
            return $this->edit($pagename);
        }
        else
        {
            //TODO: 404 error
            App::abort(404);
        }
        $this->page = $page;
        
        $html = '<h1>' . e($title) . '</h1>';
        $html .= Parser::parse($text);

        $this->setView($html);
    }

    public function pluginAct($pluginId = '')
    {
        try {
            $html = Plugin::get($pluginId)->action();
        }
        catch (Exception $e) {
            $html = $pluginId .' plugin not implemented';
        }
        $this->setView($html);
    }

    protected function setView($html = '')
    {
        $this->setupTheme();

        $pagename = isset($this->page->name) ? $this->page->name : Config::get('app.haik.defaultPage');

        $html .= '<hr>';
        $html .= '<a href="/haik--admin/edit/'.$pagename.'">編集</a>';
        $html .= " ";
        $html .= '<a href="/haik--admin/destroy/' .$pagename.'">削除</a>';
        $html .= " ";
        $html .= '<a href="/haik--admin/create/">追加</a>';

        Theme::set('page_title', 'タイトル');
        Theme::set('content', $html);

        $this->layout = $this->renderTheme();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($pagename)
    {
        // $id からページのソースをひっぱってきて
        // textarea に入れる
        
        $page = Page::where('name', $pagename)->first();
        
        $md = '';
        $title = $pagename;
        
        if ($page)
        {
            $title = $page->title;
            $md = $page->body;
        }
        
        $this->layout = View::make('settings.layouts.editor')->with(array(
           'title' => $title,
           'md'    => $md,
           'name'  => $pagename,
           'view'  => 'settings.edit',
           'nav'   => 'settings.includes.nav_edit',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($pagename)
    {
        $page = Page::where('name', $pagename)->first();
        $page->delete();
        
        return Redirect::to('FrontPage');
        
    }

}

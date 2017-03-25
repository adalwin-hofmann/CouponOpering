<?php

class BaseController extends Controller {

    public function __construct()
    {
        $route = Route::current();
        $seoContents = App::make('SeoContentRepositoryInterface');
        $genericContents = $seoContents->findByUrlAndType($route->getPath());
        $wildContents = $seoContents->getByWildUrl(Request::path(), $route->getPath());
        $seoContent = $genericContents;
        
        foreach($wildContents as $wild)
        {
            $seoContent = array_merge($seoContent, $wild);
        }
        
        $specificContents = $seoContents->findByUrlAndType(Request::path());
        $seoContent = array_merge($seoContent, $specificContents);
        View::share('seoContent', $seoContent);
        $this->seoContent = $seoContent;
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{ 
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
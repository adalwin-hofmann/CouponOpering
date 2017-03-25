<?php

class SeoController extends BaseController {

    /**
    *
    * Create a new controller instance.
    *
    * @param UserRepository $users
    *
    * @return void
    */
    public function __construct(
        CategoryRepositoryInterface $categories,
        CityImageRepositoryInterface $cityImages,
        SeoContentRepositoryInterface $seoContents,
        UserRepositoryInterface $users
    )
    {
        $this->categories = $categories;
        $this->cityImages = $cityImages;
        $this->seoContents = $seoContents;
        $this->users = $users;

        $this->beforeFilter(function()
        {
            if(!Auth::check())
            {
                return Redirect::to('/login');
            }
            else
            {
                $user = Auth::User();
                $found = $this->users->checkType($user, 'content');
                $found = $found && $this->users->checkType($user, 'seo');
                if(!$found)
                {
                    return Redirect::to('/login');
                }
            }
        });
    }

    public function getIndex()
    {
        $code = array();
        //$code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-contents')->with('code', implode(' ', $code));

        $vw->urls = $this->seoContents->getPageUrls();
        $vw->searchUrl = '';
        $vw->searchType = '';
        $vw->content = null;
        $vw->primary_nav = "seo";
        $vw->secondary_nav = "seo";
        return $vw;

        /*$code = array();
        $code[] = View::make('admin.content.jscode.seo');
        $vw = View::make('admin.content.seo')->with('code', implode(' ', $code));

        $cat = Input::get('cat', null);
        $category = $this->categories->find($cat);
        $vw->category = null;
        $vw->subcategory = null;
        $vw->selected = null;

        $parents = $this->categories->getByParentId(0);
        $vw->parents = $parents;

        $subs = $this->categories->getByParentId($cat);
        $vw->subs = $subs;

        $vw->primary_nav = "seo";
        $vw->secondary_nav = "seo";
        return $vw;*/
    }

    public function postIndex()
    {
        $code = array();
        //$code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-contents')->with('code', implode(' ', $code));

        $vw->urls = $this->seoContents->getPageUrls();

        if(Input::has('id'))
        {
            $seo = $this->seoContents->modifySeo(Input::all());
            if(!$seo)
            {
                $errors = $this->seoContents->errors();
                return Redirect::to('seo/contents')->with(array('errors' => $errors))->withInput();
            }
            Event::fire('backoffice.updated', array('seo_content', $seo->id, Auth::user()->id, $seo, 'seo content updated'));
        }

        if(Input::has('create_new'))
        {
            $vw->searchUrl = '';
            $vw->searchType = '';
            $vw->content = null;
        }
        else
        {
            $vw->searchUrl = Input::get('page_url');
            $vw->searchType = Input::get('content_type');
            $vw->content = $this->seoContents->findByUrlAndType(Input::get('page_url'), Input::get('content_type'));
        }
        $vw->primary_nav = "seo";
        $vw->secondary_nav = "seo";
        return $vw;

        /*$code = array();
        $code[] = View::make('admin.content.jscode.seo');
        $vw = View::make('admin.content.seo')->with('code', implode(' ', $code));

        if(Input::has('above_heading') || Input::get('above_heading') === '')
        {
            $this->categories->update(Input::get('category_id'), array(
                'above_heading' => Input::get('above_heading'),
                'footer_heading' => Input::get('footer_heading'),
                'sub_heading' => Input::get('sub_heading'),
                'title' => Input::get('title'),
                'description' => Input::get('description')
            ));
        }

        $cat = Input::get('cat', null);
        $category = $this->categories->find($cat);
        $vw->category = $category;

        $parents = $this->categories->getByParentId(0);
        $vw->parents = $parents;

        $subcat = Input::get('subcat', null);
        $subcategory = $this->categories->find($subcat);
        if($category && $subcategory && $category->id != $subcategory->parent_id)
            $subcategory = null;
        $vw->subcategory = $subcategory;
        $parent = $category ? $category->id : null;
        $subs = $this->categories->getByParentId($parent);
        $vw->subs = $subs;
        $vw->selected = $subcategory ? $subcategory : $category;

        $vw->primary_nav = "seo";
        $vw->secondary_nav = "seo";
        return $vw;*/
    }

    public function getStates()
    {
        $code = array();
        //$code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-states')->with('code', implode(' ', $code));

        $state = Input::get('state', null);
        $city_image = $this->cityImages->findByState($state);
        $vw->city_image = $city_image;

        $states = $this->cityImages->getStates();
        $vw->states = $states;

        $vw->primary_nav = "seo";
        $vw->secondary_nav = "states";
        return $vw;
    }

    public function postStates()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-states')->with('code', implode(' ', $code));

        $state = Input::get('state', null);
        $city_image = $this->cityImages->findByState($state);
        if(Input::has('about'))
        {
            $city_image->about = Input::get('about');
            $city_image->save();
        }
        $vw->city_image = $city_image;

        $states = $this->cityImages->getStates();
        $vw->states = $states;

        $vw->primary_nav = "seo";
        $vw->secondary_nav = "states";
        return $vw;
    }

    public function getContents()
    {
        $code = array();
        //$code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-contents')->with('code', implode(' ', $code));

        $vw->urls = $this->seoContents->getPageUrls();
        $vw->searchUrl = '';
        $vw->searchType = '';
        $vw->content = null;
        $vw->primary_nav = "seo";
        $vw->secondary_nav = "contents";
        return $vw;
    }

    public function postContents()
    {
        $code = array();
        //$code[] = View::make('admin.content.jscode.seo-states');
        $vw = View::make('admin.content.seo-contents')->with('code', implode(' ', $code));

        $vw->urls = $this->seoContents->getPageUrls();

        if(Input::has('id'))
        {
            $seo = $this->seoContents->modifySeo(Input::all());
            if(!$seo)
            {
                $errors = $this->seoContents->errors();
                return Redirect::to('seo/contents')->with(array('errors' => $errors))->withInput();
            }
            Event::fire('backoffice.updated', array('seo_content', $seo->id, Auth::user()->id, $seo, 'seo content updated'));
        }

        if(Input::has('create_new'))
        {
            $vw->searchUrl = '';
            $vw->searchType = '';
            $vw->content = null;
        }
        else
        {
            $vw->searchUrl = Input::get('page_url');
            $vw->searchType = Input::get('content_type');
            $vw->content = $this->seoContents->findByUrlAndType(Input::get('page_url'), Input::get('content_type'));
        }
        $vw->primary_nav = "seo";
        $vw->secondary_nav = "contents";
        return $vw;
    }
}
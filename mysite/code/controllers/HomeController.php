<?php

class HomeController extends Page_Controller
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array(
        'index'
    );

    public function init(){
        parent::init();
    }

    public function index(SS_HTTPRequest $request) {
        $params = $request->allParams();
        $id = $params['ID'];

        if($id == ''){
            $home = SiteTree::get()->filter(array('URLSegment' => 'home'))->First();
            if($home) return $home->renderWith('Page');
        }

        $launcher = Launcher::get()->filter(array(
            'URLSegment' => $id,
            'Approved' => 1
        ))->First();

        if($launcher){
            //analytics here??
            return 'launcher found.';
            //return $this->redirect($launcher->Destination);
            return $this;
        }
        $pageTest = Page::get()->filter(array('URLSegment' => $id))->First();
        if($pageTest){
            return ContentController::create($pageTest)->renderWith('Page');              
        }

        return $this->httpError(404);

    }
}

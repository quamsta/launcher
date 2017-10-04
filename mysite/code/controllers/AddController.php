<?php

class AddController extends Page_Controller
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
        'index',
        'Form',
    );

    public function init(){
        parent::init();
        if(!Member::currentUserID()) Security::permissionFailure();
    }

    public function index(SS_HTTPRequest $request) {
        $params = $request->allParams();
        $id = $params['ID'];

        $data = array(
            'Title' => 'Add a launcher'
        );

        return $this->customise($data)->renderWith('Page');
    }

    public function Form(){
        $fields = new FieldList(
            FieldGroup::create(
                new LiteralField('SegmentPrefix','Prefix:'),
                new TextField('URLSegment', 'Requested URL')
            ),
            TextField::create('Destination', 'Destination', 'http://')
        );

        $actions = new FieldList(
            FormAction::create("doAdd")->setTitle("Request Launcher")->setUseButtonTag(true)
        );

        $required = new RequiredFields('URLSegment', 'Destination');

        $form = new Form($this, 'Form', $fields, $actions, $required);

        return $form;

    }

    public function doAdd($data, Form $form) {
        if(!Member::currentUserID()) Security::permissionFailure();

        $newLauncher = Launcher::create();
        $newLauncher->URLSegment = $data['URLSegment'];
        $newLauncher->Destination = $data['Destination'];
        $newLauncher->write();
        $form->sessionMessage('added launcher:  '. $data['URLSegment'], 'success');
        return $this->redirectBack();
    }
}

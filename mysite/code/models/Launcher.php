<?php

class Launcher extends DataObject {

	private static $db = array(
		'URLSegment' => 'Varchar(2083)', // 2083 is the maximum length of a URL in Internet Explorer.
		'Destination' => 'Varchar(2083)',
		'Approved' => 'Boolean'
	); 

	private static $has_one = array(
		'Submitter' => 'Member'
	);

	private static $summary_fields = array(
		'URLSegment',
		'Destination',
		'Approved',
		'Submitter.Name',
		'Created'
	);

	public function getCMSFields(){

		$f = parent::getCMSFields();

		//$f->removeByName('SubmitterID');
		// $field = $f->dataFieldByName('SubmitterID');
		$f->removeByName('SubmitterID');
		// $field->performReadonlyTransformation();
		// $f->addFieldToTab('Root.Main', $field);
		// Debug::show($field);
		

		if(($this->isInDb()) && ($this->SubmitterID != 0)){
			$submitter = Member::get()->filter(array('ID' => $this->SubmitterID))->First();
			if($submitter){
				$f->addFieldToTab('Root.Main', ReadonlyField::create('SubmitterName','Submitter', $submitter->getName()));
			}
			
		}
		

		return $f;
	}
	public function getTitle(){
		return $this->URLSegment;
	}
    public function getBetterButtonsActions() {
        $fields = parent::getBetterButtonsActions();
        if($this->IsApproved) {
            $fields->push(BetterButtonCustomAction::create('deny', 'Deny'));
        }
        else {
            $fields->push(BetterButtonCustomAction::create('approve', 'Approve'));
        }
        return $fields;
    }
	public function onBeforeWrite() {
	    // check on first write action, aka "database row creation" (ID-property is not set)
	    if(!$this->isInDb()) {
	      $currentMember = Member::currentUser();

	      if(!$currentMember) {
	        user_error('Launch creation failed, no permissions.', E_USER_ERROR);
	        exit();
	      }

	      $this->SubmitterID = $currentMember->ID;
	    }

	    parent::onBeforeWrite();
	  }	
}
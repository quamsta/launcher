<?php

class LauncherAdmin extends ModelAdmin {

    private static $managed_models = array(
    	'Launcher'
    );

    private static $field_labels = array(
      'URLSegment' => 'URL' // renames the column to "Cost"
    );

    private static $url_segment = 'launchers';

    private static $menu_title = 'Launchers';

    private static $menu_icon = 'mysite/images/launcher-icon.png';

	public function getSearchContext() {
		$context = parent::getSearchContext();

		if($this->modelClass == 'Launcher') {
		    $context->getFields()->push(new CheckboxField('q[ExpensiveOnly]', 'Only expensive stuff'));
		}

		return $context;
	}
}
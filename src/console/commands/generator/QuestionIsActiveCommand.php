<?php

namespace yii2module\vendor\console\commands\generator;

use yii2lab\extension\console\helpers\input\Question;

class QuestionIsActiveCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if($event->isActive === null && (in_array('service', $event->types) || in_array('repository', $event->types))) {
			$event->isActive = Question::confirm2('Is active?', false);
		}
	}
	
}

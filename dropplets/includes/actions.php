<?php

class action {
	
	protected static $_actions = array();
	
	protected static $_timesRun = array();
	
	public static function add($hook, $function, $priority = 100) {
		
		if (!is_callable($function)) {
			return FALSE;
		}
		
		if (!array_key_exists($hook, self::$_actions)) {
			self::$_actions[$hook] = array();
		}
		
		self::$_actions[$hook][self::_uniqueIdentifier($hook, $function, $priority)] = array(
			'function'   => $function,
			'priority'   => $priority
		);
		
		return TRUE;
	}
	
	public static function run($hook) {
		
		if (!array_key_exists($hook, self::$_actions)) {
			return FALSE;
		}
		
		uasort(self::$_actions[$hook], array('Action', '_prioritySort'));
		
		foreach (self::$_actions[$hook] as $action) {
			call_user_func($action['function']);
		}
		if (!isset(self::$_timesRun[$hook])) {
			self::$_timesRun[$hook] = 1;
		} else {
			self::$_timesRun[$hook]++;
		}
	}
	
	public static function remove($hook, $function, $priority = 100) {
		
		$identifier = self::_uniqueIdentifier($hook, $function, $priority);
		
		if (array_key_exists($identifier, self::$_actions[$hook])) {
			unset(self::$_actions[$hook][$identifier]);
			return true;
		}else {
			return false;
		}
		
	}
	
	public static function removeAll($hook) {
		unset(self::$_actions[$hook]);
	}
	
	public static function timesRun($hook) {
		if (!isset(self::$_timesRun[$hook])) {
			return 0;
		}
		return self::$_timesRun[$hook];
	}
	
	public static function replace($hook, $existing, $oldPriority = 100, $new, $priority = 100) {
		
		$id = self::_uniqueIdentifier($hook, $existing, $oldPriority);
		
		if (isset(self::$_actions[$hook][$id])) {
			unset(self::$_actions[$hook][$id]);
			self::add($hook, $new, $priority);
		}
		
	}
	
	protected static function _prioritySort($a, $b) {
		if ($a['priority'] == $b['priority']) {
			return 0;
		}
		return ($a['priority'] < $b['priority']) ? -1 : 1;
	}
	
	protected static function _uniqueIdentifier($hook, $function, $priority) {
		
		if ( is_object($function) ) {
			$function = array( $function, '' );
		} elseif (!is_string($function)) {
			$function = (array) $function;
		}
		
		if (is_string($function)) {
			$identifier = $hook . $function . (string) $priority;
		} elseif (is_object($function[0])) {
			if ( function_exists('spl_object_hash') ) {
				$identifier = $hook . spl_object_hash($function[0]) . $function[1] . (string) $priority;
			} else {
				$identifier = $hook . $function[1] . (string) $priority;
			}
		} else {
			$identifier = $hook . $function[0] . $function[1] . (string) $priority;
		}
		
		return hash('sha256', $identifier);
		
	}
}
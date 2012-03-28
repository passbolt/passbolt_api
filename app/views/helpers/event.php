<?php 

class EventHelper extends AppHelper {
	function toText($event){
		$res = "";
		switch($event["Event"]["action_id"]){
			case WATCHER_CAT_READ_PASSWORDS:
				if($event["Category"]["name"] == '::'.$event["User"]["id"].'::'){
					$event["Category"]["name"] = "My Passwords";
				}
				$res = sprintf('<span class="user">%s</span> accessed category <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_GET_PASSWORD:
				$res = sprintf('<span class="user">%s</span> accessed password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_CAT_RENAME:
				$res = sprintf('<span class="user">%s</span> renamed category <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_CAT_DELETE:
				$res = sprintf('<span class="user">%s</span> deleted category <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_CAT_DELETE_FOREVER:
				$res = sprintf('<span class="user">%s</span> deleted forever category <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_CAT_MOVE:
				$res = sprintf('<span class="user">%s</span> moved category <span class="category">%s</span> from <span class="category">%s</span> to <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"], $event["Event"]["category_from"], $event["Event"]["category_to"]);
				break;
			case WATCHER_CAT_COPY:
				$res = sprintf('<span class="user">%s</span> copied category <span class="category">%s</span> in to <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"], $event["Event"]["category_new_parent"]);
				break;
			case WATCHER_CAT_CREATE:
				$res = sprintf('<span class="user">%s</span> created category <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_CAT_CREATE_DATABASE:
				$res = sprintf('<span class="user">%s</span> created database <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_CAT_OPEN_DATABASE:
				if($event["Category"]["name"] == '::'.$event["User"]["id"].'::'){
					$event["Category"]["name"] = "My Passwords";
				}
				$res = sprintf('<span class="user">%s</span> opened database <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_PASSWORD_CREATE:
				$res = sprintf('<span class="user">%s</span> created password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_PASSWORD_DELETE:
				$res = sprintf('<span class="user">%s</span> deleted password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_PASSWORD_DELETE_FOREVER:
				$res = sprintf('<span class="user">%s</span> deleted forever password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_PASSWORD_RESTORE:
				$res = sprintf('<span class="user">%s</span> restores password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_PASSWORD_MODIFY:
				$res = sprintf('<span class="user">%s</span> modified password <span class="password">%s</span>', $event["User"]["name"], $event["Password"]["title"]);
				break;
			case WATCHER_PERMISSION_ADD:
				$res = sprintf('<span class="user">%s</span> added permission on <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_PERMISSION_DELETE:
				$res = sprintf('<span class="user">%s</span> deleted permission on <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
			case WATCHER_PERMISSION_MODIFY:
				$res = sprintf('<span class="user">%s</span> modified permission on <span class="category">%s</span>', $event["User"]["name"], $event["Category"]["name"]);
				break;
		}
		return $res;
	}
}
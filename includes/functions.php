<?php

function __autoload($class_name) {
	$class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path)) {
        require_once($path);
    } else {
		die("The file {$class_name}.php could not be found.");
	}
}

function output_message($message="") {
    if (!empty($message)) {
        return "<p class=\"message\">{$message}</p>";
    } else {
        return "";
    }
}

function redirect_to( $location = NULL ) {
    if ($location != NULL) {
        header("Location: {$location}");
        exit;
    }
}

function include_layout_template($template="") {
    include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function check_protocol($url_string) {
    if (strpos($url_string, "http://") === false) {
        $url_string = "http://" . $url_string;
        return $url_string;
    } else {
        return $url_string;
    };
}

?>
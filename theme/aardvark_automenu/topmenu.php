
<?php

	$text = '<li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel" href="'.$CFG->wwwroot.'/"><img width="18" height="17" src="'.$CFG->httpswwwroot.'/theme/'.current_theme().'/images/home_icon.png" alt="Home" title="Home"/></a>';
	$text .= '</li>';
    echo $text;


	print_whole_category_list_menu();

function print_whole_category_list_menu($category=NULL, $displaylist=NULL, $parentslist=NULL, $depth=-1) {
/// Recursive function to print out all the categories in a nice format
/// with or without courses included

     global $CFG;


    if (isset($CFG->max_category_depth) && ($depth >= $CFG->max_category_depth)) {
        return;
    }

	if ($category) {
         if ($category->visible or has_capability('moodle/course:update', get_context_instance(CONTEXT_SYSTEM))) {
            print_category_info_menu($category, $depth);
            echo '<div class="yuimenu"><div class="bd"><ul>';
		 } else {
		    return;  // Don't bother printing children of invisible categories
        }

    } else {
        $category->id = "0";
    }


    if ($categories = get_child_categories($category->id)) {   // Print all the children recursively
        $countcats = count($categories);
        $count = 0;
        $first = true;
        $last = false;
        foreach ($categories as $cat) {
            $count++;
            if ($count == $countcats) {
                $last = true;
            }
            $up = $first ? false : true;
            $down = $last ? false : true;
            $first = false;

            print_whole_category_list_menu($cat, $displaylist, $parentslist, $depth + 1);
			echo '</ul></li>';
        }
    }
}

function print_category_info_menu($category, $depth) {
/// Prints the category info in indented fashion
/// This function is only used by print_whole_category_list() above

    global $CFG;
    $coursecount = count_records('course') <= FRONTPAGECOURSELIMIT;

    $courses = get_courses($category->id, 'c.sortorder ASC', 'c.id,c.sortorder,c.visible,c.fullname,c.shortname,c.password,c.summary,c.guest,c.cost,c.currency');


	if ($depth) {

		if ($category->visible) {
		   echo '<li class="yuimenuitem"><a class="yuimenuitemlabel" href="'.$CFG->wwwroot.'/course/category.php?id='.$category->id.'">'.format_string($category->name).'</a>';
		} else {
		   echo '<li class="yuimenuitem"><a class="yuimenuitemlabel invisiblecategory" href="'.$CFG->wwwroot.'/course/category.php?id='.$category->id.'">'.format_string($category->name).'</a>';
		}
	} else {
		if ($category->visible) {
            echo '<li class="yuimenubaritem"><a class="yuimenubaritemlabel" href="'.$CFG->wwwroot.'/course/category.php?id='.$category->id.'">'.format_string($category->name).'</a>';
		} else {
		    echo '<li class="yuimenubaritem"><a class="yuimenubaritemlabel invisiblecategory" href="'.$CFG->wwwroot.'/course/category.php?id='.$category->id.'">'.format_string($category->name).'</a>';
		}
	}

}
?>
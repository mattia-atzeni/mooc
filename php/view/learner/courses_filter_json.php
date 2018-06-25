<?php

$json = array();
$json['errors'] = $vd->getErrorMessages();
$json['courses'] = array();
if (isset($courses)) {
    foreach ($courses as $course) {
        $tmp = array();
        $tmp['name'] = $course->getName();
        $tmp['link'] = $course->getLink();
        $tmp['category'] = $course->getCategory()->getName();
        $tmp ['host_name'] = $course->getHost()->getName();
        $tmp['id'] = $course->getId();
        $json['courses'][] = $tmp;
    }
}
echo json_encode($json);


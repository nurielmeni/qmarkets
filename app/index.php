<?php
require_once('Model.php');
require_once('View.php');

$migrate = $_GET['migrate'] ?? false;
$search = $_POST['search'] ?? '';

$model = new Model();
$view = new View();

$messages[] = 'Connected successfully.';
$results = [];

if ($migrate === 'true' && !$model->migrate()) {
    die('Falied to migrate the database.');
    $messages[] =  'Migration completed successfully.';
}

echo $view->render('search', ['search' => $search]);


if (!$search) {
    $messages[] =  'You need to provide a search string.';
} else if (strlen($search) < 2) {
    $messages[] =  'You need to provide a search string with 2 or more letters.';
} else {
    $results = $model->search($search);
    $messages[] =  'Search complete, ' . count($results) . ' results found.';
    echo $view->render('results', ['results' => $results]);
}

echo $view->render('messages', ['messages' => $messages]);

die();

?>
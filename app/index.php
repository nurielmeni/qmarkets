<?php
require_once('Model.php');
require_once('View.php');

$migrate = $_GET['migrate'] ?? false;
$indices = $_GET['indices'] ?? false;
$search = $_POST['search'] ?? '';

$model = new Model();
$view = new View();

$messages[] = 'Connected successfully.';
$results = [];

if ($migrate) {
    if (!$model->migrate($indices ? 1 : false)) die('Falied to migrate the database.');
    $messages[] =  'Migration completed successfully' . ($indices ? '(WITH INDICES)' : '') .'.';
}

echo $view->render('search', ['search' => $search]);


if (!$search) {
    $messages[] =  'You need to provide a search string.';
} else if (strlen($search) < 2) {
    $messages[] =  'You need to provide a search string with 2 or more letters.';
} else {
    $results = $model->search($search);
    $messages[] =  'Search complete, ' . count($results['rows']) . ' results found in ' . number_format($results['elapsed'], 6) . ' seconds.';
    echo $view->render('results', [
        'results' => $results['rows']
    ]);
}

echo $view->render('messages', ['messages' => $messages]);

die();

?>
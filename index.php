<?php  
include('app/init.php');
include('app/routes/routes.php');

$uri = $_SERVER['REQUEST_URI'];  

// $Book->late();

if (isset($_GET['page'])) 
{
    $page = $_GET['page'];

    $page_title = '';
    switch($page) {
        case 'books':
            $container_data = $Book->get();
            $Template->set_data('selected_index', 0);
            $Template->set_data('container-data', $container_data);
            break; 
        case 'lentBooks':
            $container_data = $Book->lent(); 
            $Template->set_data('selected_index', 1);
            $Template->set_data('container-data', $container_data);
            break; 
        case 'lateBooks':
            $container_data = $Book->late(); 
            $Template->set_data('selected_index', 3);
            $Template->set_data('container-data', $container_data);
            break;
        case 'members': 
            if (isset($_GET['member']))
            {
                $container_data = $Member->currently_lending($_GET['member']);
                $Template->set_data('selected_index', 2);
                $Template->set_data('container-data', $container_data);
                break;
            }
            $container_data = $Member->get();
            $Template->set_data('selected_index', 2);
            $Template->set_data('container-data', $container_data); 
            break; 
    } 

    $Template->load('app/views/main.php', 'Home');
} 
 
else 
{
    echo '<h1>Home</h1>';
}

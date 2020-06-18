<?php  
include('create_table.php');

$server = 'localhost';
$user = 'root';
$password = 'somepassword';
$db = 'binary_library';
$Database = new mysqli($server, $user, $password, $db); 

if ($Database->connect_error) 
{
    die('Error: ' . $Database->connect_error);
} 

$Database->query($CREATE_MEMBERS_TABLE);
$Database->query($CREATE_BOOKS_TABLE); 
$Database->query($CREATE_LENDING_HISTORY_TABLE);

if (mysqli_num_rows($Database->query('SELECT * from books')) == 0 ) 
{
    create_fake_data($Database);
}

spl_autoload_register(function ($class_name) {
    include 'models/' . $class_name . '.php';
});

$Member = new Member();
$Book = new Book(); 
$Template = new Template();

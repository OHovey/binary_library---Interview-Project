<?php  

class Book
{
    private $Database;
    private $db_table = 'books'; 
    private $data; 
    
    function __construct() {
        global $Database;
        $this->Database = $Database; 
    }

    public function get() 
    {
        if ($results = $this->Database->query("SELECT * FROM books")) 
        {
            $book_table = '<table class = "table" style = "background-color: whitesmoke;" >';
            $book_table .= '<thead><tr><th scope = "col">#</th><th scope = "col">Title</th><th scope = "col">Location</th></tr></thead>'; 
            $book_table .= '<tbody>';
            foreach($results as $index => $book)
            {
                $book_table .= '<tr><td scope="row">' . strval(intval($index) + 1 ) . '</td><td>' . $book['title'] . '</td><td>' . $book['location'] . '</td></tr>';
            } 
            $book_table .= '</tbody>';
            $book_table .= '</table>';

            return $book_table;
        }
    } 

    public function get_one($id)
    {
        if ($stmt = $this->Database->prepare("SELECT title, location FROM $this->db_table WHERE id = ?"))
        {
            $stmt->bind_param("i", $id); 
            $stmt->execute();
            $stmt->store_result(); 
            $stmt->bind_result($title, $location); 

            $stmt->fetch(); 

            if (! $stmt->num_rows > 0) {}
            
            $books = array('title' => $title, 'location' => $location); 
            return $books;
        }
    }

    public function lent() 
    {
        if ($results = $this->Database->query("SELECT lending_history.member_id, lending_history.book_id, members.name, books.title, books.location FROM lending_history
                                               INNER JOIN members ON lending_history.member_id = members.id
                                               INNER JOIN books ON lending_history.book_id = books.id 
                                               WHERE returned IS NULL "))
        {
            $books_list = '<table class = "table">'; 
            $books_list .= '<thead><tr style = "background-color: whitesmoke;"><th scope = "col">#</th><th>Title</th><th>User</th><th>Location</th><tr></thead>'; 
            $books_list .= '<tbody>';
            foreach($results as $index => $result)
            {
                $books_list .= '<tr style = "background-color: whitesmoke;"><td>' .  strval( intval($index) + 1 )  . '</td><td>' . $result['title'] . '</td><td>' . $result['name'] . '</td><td>' . $result['location'] . '</td></tr>';
            }
            $books_list .= '</tbody>';
            $books_list .= '</table>';
            return $books_list;
        }
    } 

    public function late() 
    {
        $current_date = date('Y-m-d');  
        $current_date_datetime = new DateTime($current_date);
        $target_date = date_sub($current_date_datetime, date_interval_create_from_date_string('30 days'));
        $target_date = date_format($target_date, 'Y-m-d'); 
        // echo $target_date;
        if ($entries = $this->Database->query("SELECT lending_history.id, books.title, books.location, members.name FROM lending_history
                                               INNER JOIN books ON lending_history.book_id = books.id AND lending_history.lent >= $target_date AND lending_history.returned IS NULL
                                               INNER JOIN members ON lending_history.member_id = members.id"))
        {
            $late_books_table = '<table class="table" style = "background-color: pink;">';
            $late_books_table .= '<thead><tr><th scope = "col">#</th><th scope = "col">Book Title</th><th scope = "col">Member Name</th><th scope = "col">Book Location</th></tr></thead>'; 
            $late_books_table .= '<tbody>';
            foreach ($entries as $key => $entry) 
            {
                $late_books_table .= '<tr><td>' . strval( intval($key + 1) ) . '</td><td>' . $entry['title'] . '</td><td>' . $entry['name'] . '</td><td>' . $entry['location'] . '</td></tr>';
            } 
            $late_books_table .= '</tbody>'; 
            $late_books_table .= '</table>'; 
            return $late_books_table;
        }
    }
}
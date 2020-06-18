<?php  

class Member 
{
    private $Database;
    private $db_table = 'members'; 
    private $data; 
    private $Book;
    
    function __construct() {
        global $Database;
        $this->Database = $Database; 
        $this->Book = new Book();  
    }


    public function get() {
        if ($results = $this->Database->query("SELECT * FROM members"))
        {
            $members_table = '<table style = "background-color: whitesmoke;" class="table">'; 
            $members_table .= '<thead><tr><th scope = "col">#</th><th scope = "col">Name</th><th scope = "col">Registration Date</th></tr></thead>';
            $members_table .= '<tbody>';
            foreach ($results as $member)
            {
                $members_table .= '<tr><td>' . $member['id'] . '</td><td>' . $member['name'] . '</td><td>' . $member['created'] . '</td><td><a href=?page=members&member=' . $member['id'] . '>View Details</a></td></tr>';
            }
            $members_table .= '</tbody>'; 
            $members_table .= '</table>'; 
            
            return $members_table;
        }
    }


    public function get_one($id) { 
        $username = NULL;
        if ($stmt = $this->Database->prepare("SELECT name, created FROM members WHERE id = ?"))
        {
            $stmt->bind_param("i", $id);
            $stmt->execute(); 
            $stmt->store_result();
            $stmt->bind_result($name, $created);
            $stmt->fetch(); 

            if ($stmt->num_rows > 0)
            {
                return [
                    'name' => $name,
                    'created' => $created
                ];
            }
        } 
        return NULL;
    }

    public function currently_lending($id) 
    {
        $member = $this->get_one($id);

        if ($stmt = $this->Database->prepare("SELECT book_id, lent FROM lending_history
                                               WHERE member_id = ?"))
        {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($book_id, $lent); 
            $stmt->fetch();

            if (! $stmt->num_rows > 0) {return '<h1 style = "background-color: whitesmoke; padding: 10px; margin: 0;">' . $member['name'] . ' - Not Currently Lending</h1>';}
            
            $lending_history[] = $this->Book->get_one($book_id);

            $user_lending_list = '<h1 style = "background-color: whitesmoke; padding: 10px; margin-bottom: 0;">' . $member['name'] . ' - Currently Lending</h1>';
            $user_lending_list .= "<table class = 'table' style = 'background-color: whitesmoke; margin: 0; padding: 5px;'>"; 
            $user_lending_list .= "<thead><tr><th scope = 'col'>Book</th><th scope = 'col'>Date Lent Out</th></tr></thead>";  
            $user_lending_list .= '<tbody>';
            foreach ($lending_history as $book) 
            {
                $user_lending_list .= '<tr><td>' . $book['title'] . '</td><td>' . $lent . '</td></tr>';
            }
            $user_lending_list .= '</tbody>'; 
            return $user_lending_list;
        }
    }
}
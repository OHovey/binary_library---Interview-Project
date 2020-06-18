<?php 

// DATABASE TABLE RELATIONSHIPS: The Members and the Books table below both have a 'has many' relationship
// with the lending_history table. The lending history table would be said to have a 'has one' relationship
// with both the books and the members table. 

// A LITTLE BIT ABOUT SAFE SQL QUERIES: Below are the queries which will populate the database with some fake data;
                                    // these queries are not considered unsafe because we can assume the input variables
                                    // will always originate from the same place; in this case, from within the same file. 
                                    // Other queries may need to use prepared statements, so that queries that are trigared
                                    // by requests made from a clients' browser - or some piece of software - are only sending
                                    // variable data that the server was intended to process. For example, if a user attampts 
                                    // to insert some malicious javascript code into the server, prepared statements allow us
                                    // to detect the nature of the data and therby handle the request differently,
                                    // cancelling the original sql query.   

$CREATE_MEMBERS_TABLE = "CREATE TABLE IF NOT EXISTS members(
    id INT AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    created DATETIME NOT NULL, 
    updated DATETIME,
    primary key (id))";

$CREATE_BOOKS_TABLE = "CREATE TABLE IF NOT EXISTS books(
    id INT AUTO_INCREMENT,
    title VARCHAR(250) NOT NULL,
    location VARCHAR(10) NOT NULL,
    lent INT NOT NULL,
    primary key (id)
)";

$CREATE_LENDING_HISTORY_TABLE = "CREATE TABLE IF NOT EXISTS lending_history(
    id INT AUTO_INCREMENT,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    lent DATETIME NOT NULL,
    returned DATETIME,
    primary key(id)
)"; 


$GET_BOOKS =  "SELECT * FROM books";
$GET_BOOKS_WHERE_LENT = "SELECT * FROM books WHERE lent = TRUE /
                         JOIN members AS m ON books.member = m.id"; 


if (! function_exists('create_fake_data')) 
{
    function create_fake_data($Database, $num_books = 50, $num_members = 10, $lending_history_entries = 100) 
    {
        $fake_book_titles = [
            'Forgotten Silk',
            'Magic on the Ultimate',
            "The Keeper's Commission",
            "Fresh Masks",
            "Dance for a Lifetime",
            "Night of Rocks",
            "The Knights of Winter",
            "Birth Benefits",
            "The Race of Execution",
            "Arms of the Road",
            "The Storyteller's Dilemma",
            "Hex and the EmeraldRugged Flames",
            "The Tide of Childhood",
            "Mystery Nurse",
            "Tycoon Risk",
            "Everyday Vendetta",
            "Bridge of Conquest",
            "Revelation and Dawn",
            "The Voice in the Nile",
            "Italian Pillars",
            "Slip of the Shaman",
            "The Evil of Merlin",
            "Chalk Casanova",
            "A Sultan's Angel",
            "The Magnificent Bishop",
            "Tiger by the Lake",
            "Something's Glory",
            "The Magic of the Sheik",
            "Split Arrangements",
            "The Loner and the King",
            "The Spring of Passion",
            "Acres of Healing",
            "The Daddy Witches",
            "The Dinosaur Jewel",
            "Ruins of Truth",
            "River and Square",
            "Fairytale Berlin",
            "The Servants of Julie",
            "The Space Octopus",
            "Trader's Aide",
            "The Downtown Hole",
            "Weddings in Heartbreak",
            "The Alien Profession",
            "The Highwayman's Deception",
            "Illusion of Harm",
            "The Rapture of the Scorpion",
            "The Ends of Time",
            "The Champion of London"
        ]; 
        
        $fake_names = [
            "Aubrey Merritt",
            "Quinton Reid",
            "Barrett Cooper",
            "Felipe Richardson",
            "Jerimiah Ware",
            "Aarav Schmitt",
            "Emilio Valencia",
            "Makena Mckenzie",
            "Aliya Meadows",
            "Maurice Shaw",
            "Zariah Bartlett",
            "Jax Chaney",
            "Alexander",
            "Andrew",
            "Anthony",
            "Austin",
            "Benjamin",
            "Blake",
            "Boris",
            "Brandon",
            "Brian",
            "Cameron",
            "Carl",
            "Charles",
            "Christian",
            "Christopher",
            "Colin",
            "Connor",
            "Dan",
            "David",
            "Dominic",
            "Dylan",
            "Edward",
            "Eric",
            "Evan",
            "Frank",
            "Gavin",
            "Gordon",
            "Harry"
        ];


        function generate_location() {
            $alphas = range('A', 'Z'); 
            $nums = range(0, 9);
            $rand_alpha = $alphas[mt_rand(0, count($alphas) - 1)];
            $rand_num = $nums[mt_rand(0, count($nums) - 1)]; 

            return $rand_alpha . strval($rand_num);
        }

        $lent_books_and_indexes = [];

        for ($i = 0; $i < count($fake_book_titles) - 1; $i++)
        {
            $title = $fake_book_titles[$i];
            $location = generate_location();
            $lent = mt_rand(0, 1) > 0.75 ? 1 : 0;  
            if ($lent)
            {
                $lent_books_and_indexes[$i] = $lent;
            }

            if ($stmt = $Database->prepare('INSERT INTO books (title, location, lent) VALUES(?, ?, ?)'))
            {
                $stmt->bind_param("ssi", $title, $location, $lent);
                $stmt->execute(); 
                $stmt->close();
            } 
            
        } 
        for ($i = 0; $i < count($fake_names); $i++)
        {
            $name = $fake_names[$i];
            $created = date('Y/m/d'); 
            if ($stmt = $Database->prepare('INSERT INTO members (name, created) VALUES(?, ?)'))
            {
                $stmt->bind_param("ss", $name, $created);
                $stmt->execute();
                $stmt->close();
            }
        } 

        function generate_lent_out_and_in_dates($out = TRUE) 
        {
            $start = strtotime("10 september 2019");
            $end = strtotime("15 june 2020"); 
            $timestamp = mt_rand($start, $end);
            $date_string = explode(" ", date("Y-m-d", $timestamp))[0]; 
            if ($out) 
            {
                $return_timestamp = date('Y-m-d', strtotime('+7 days', $timestamp));
                return ['lent' => $date_string, 'return' => $return_timestamp]; 
            } 
            return $date_string;
        }
        for ($i = 0; $i < $lending_history_entries; $i++)
        {
            $member_id = mt_rand(0, count($fake_names));
            $book_id = mt_rand(0, count($fake_book_titles));
            $date_info = generate_lent_out_and_in_dates(); 
            $lent = $date_info['lent'];
            $returned = $date_info['return'];

            if ($stmt = $Database->prepare('INSERT INTO lending_history (member_id, book_id, lent, returned) VALUES(?, ?, ?, ?)')) 
            {
                $stmt->bind_param("iiss", $member_id, $book_id, $lent, $returned);
                $stmt->close();
            }
        } 
        foreach ($lent_books_and_indexes as $lent_book_key => $lent_book_value)
        {   
            $member_id = mt_rand(0, count($fake_names)); 
            $book_id = $lent_book_key;
            $lent = generate_lent_out_and_in_dates(FALSE);
            if ($stmt = $Database->prepare('INSERT INTO lending_history (member_id, book_id, lent) VALUES(?, ?, ?)')) 
            {
                $stmt->bind_param("iis", $member_id, $book_id, $lent);
                $stmt->execute(); 
                $stmt->close();
            }
        }
    }
}


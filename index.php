<?php

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection details
    $host = "localhost";
    $dbName = "pdo_posts";
    $user = "emmy";
    $password = "test1234";

    // Set Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$dbName"; // Fixed spacing issue

    try {
        // Create a PDO instance
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);   // make object the default fetch mode.

        // PDO QUERY to fetch all records from the 'posts' table
        $sql = $conn->query('SELECT * FROM posts');

        // Fetch all results as an associative array
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);    ///  will overide the object fetching above

        // Loop through results and display the 'title' and 'author' of each post
        foreach ($results as $result) {
            $publicationStatus = $result['is_published'] ? 'is published' : 'not published';
            echo "{$result['title']} by {$result['author']} - $publicationStatus <br />";
        }

        #               example of using and object to fetch

        $results = $sql->fetchAll();

        // Loop through results and display the 'title' and 'author' of each post
        foreach ($results as $result) {
            $publicationStatus = $result->is_published ? 'is published' : 'not published';
            echo "$result->title by $result->author - $publicationStatus <br />";
        }

        echo "<br />";
        echo "<br />";
        echo "<br />";



        #    PREPARED STATEMENTS (prepare & execute)

        // user input
        $author = 'Micheal';
        $is_published = true;
        $id = 4;

        //   FETCHING MULTIPLE POST

        /// UNSAFE WAY
        $sql = "SELECT * FROM posts WHERE author = '$author' ";


        // SAFE WAY: Fetch Multiple Posts Using Positional Params (SQL placeholder `?`)
        $sql = "SELECT * FROM posts WHERE author = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$author]);

        // SAFE WAY: Fetch Multiple Posts Using Named Params
        $sql = "SELECT * FROM posts WHERE author = :author && is_published = :is_published";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['author' => $author, 'is_published' => $is_published]);
        

        // Fetch all matching posts
        $posts = $stmt->fetchAll();

        print_r($posts);

        echo "<br />";
        echo "<br />";
        echo "<br />";

        // Loop through posts and display the title and author
        foreach ($posts as $post) {
            echo "$post->title by $author <br />";
        }

        echo "<br />";
        echo "<br />";
        echo "<br />";


        ////  FETCHING A SINGLE POST
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        

        // Fetch the matching posts
        $post = $stmt->fetch();

        print_r($post);

        echo "<br />";
        echo "<br />";
        echo "<br />";


        echo "$post->title by $post->author";


        /// GET ROW COUNT
        $sql = "SELECT * FROM posts WHERE author = :author";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['author' => $author]);
        $postCount = $stmt->rowCount();

        echo "<br />";
        echo "<br />";
        echo "<br />";


        echo $postCount . '<br />';


        /// INSERTING DATA
        $title = 'post seven';
        $body = 'this is post seven';
        $is_published = 0;
        $author = 'Jenny';


        $sql = "INSERT INTO posts (title, body, is_published, author) VALUES (:title, :body, :is_published, :author)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['title' => $title, 'body' => $body, 'is_published' => $is_published, 'author' => $author]);
        echo "post added";

        echo "<br />";
        echo "<br />";
        echo "<br />";


        // UPDATING DATA
        $body = 'this is post seven and has been updated';
        $id = 7;


        $sql = "UPDATE posts SET body = :body WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id, 'body' => $body]);
        echo "post updated";

        echo "<br />";
        echo "<br />";
        echo "<br />";


        DELETING DATA
        $id = 7;


        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        echo "post deleted";

        echo "<br />";
        echo "<br />";
        echo "<br />";


        ////  SEARCH DATA
        $search = "%i%";
        $search = "%four%";
        $sql = "SELECT * FROM posts WHERE title LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['search' => $search]);

        // Get searched result
        $search_results = $stmt->fetchAll();

        foreach($search_results as $search_result){
            echo "search result is $search_result->title <br />";
        }

    

    } catch (PDOException $e) {
        // Handle connection errors
        echo "Connection failed: " . $e->getMessage();
    }

?>

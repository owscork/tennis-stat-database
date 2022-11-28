<html>
<body>

<?php
$servername = "127.0.0.1"; // Do not use "localhost"

// In the Real World (TM), you should not connect using the root account.
// Create a privileged account instead.
$username = "root";

// In the Real World (TM), this password would be cracked in miliseconds.
$password = "@Kingpin12";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dbname = "tennisDB";

mysqli_select_db($conn, $dbname) or die("Could not open the '$dbname'");

echo "<!DOCTYPE html>
<html lang=\"en\">
  <head>
    <meta charset=\"UTF-8\" />
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
    <title>Homework 3</title>
    <link rel=\"stylesheet\" href=\"style.css\">
    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Days+One&family=Dongle&display=swap\" rel=\"stylesheet\">
  </head>
  <body>
    <div class=\"header\">
        <h1>Tennis Data</h1>
        <p>Enter info for your query and press submit</p>
    </div>
    <div class=\"q1\">
        <h3>Show Player Info</h3>
        <div class=\"form1\">
            <form action=\"db_handler.php\" method=\"post\">
                <label for=\"player_name\">Enter a player's name to display their info</label>
                <br>
                <input type=\"text\" id=\"player_name\" name=\"player_name\" placeholder=\"(Name)\" required>
                <br>
                <input type=\"submit\" value=\"Submit\">
            </form>
        </div>
    </div>
    <hr>
    <div class=\"q2\">
        <h3>Show Tournament Info by Year</h3>
        <form action=\"db_handler.php\" method=\"post\">
            <label for=\"year\">Select a year to display all tournament info from that year</label>
            <br>
            Select year: <t><select name=\"year\" id=\"year\" placeholder=\"Year\" required>
            ";
            $query_string = "SELECT DISTINCT tourney_year FROM Tournaments";
            
            $result = mysqli_query($conn, $query_string);
            $tuple_count = 0;
            while ($row = mysqli_fetch_array($result)) {
                $tuple_count++;
                echo "<option value=\"$row[0]\">$row[0]</option>";
            };
            
echo "
            </select>
            <br>
            <input type=\"submit\" value=\"Submit\">
        </form>
    </div>
    <hr>    
    <div class=\"q3\">
      <h3>Show Player Stats Between Two Dates</h3>
      <form action=\"db_handler.php\" method=\"post\">
        <label for=\"player\">Enter player's name and select two dates to displayer their stats between those dates</label>
        <br>
        <input
          type=\"text\"
          list=\"value\"
          id=\"player\"
          name=\"player\"
          placeholder=\"(Name)\"
          required
        />
        <br />
        <label for=\"player\">Start Date (yyyy-mm-dd):</label>
        <select name=\"start_date\" id=\"start_date\">";
            $query_string = "SELECT DISTINCT tourney_date FROM Tournaments";
                
            $result = mysqli_query($conn, $query_string);
            $tuple_count = 0;
            while ($row = mysqli_fetch_array($result)) {
                $tuple_count++;
                echo "<option value=\"$row[0]\">$row[0]</option>";
            };
        echo "
        </select>
        <br />
        <label for=\"player\">End Date (yyyy-mm-dd):</label>
        <select name=\"end_date\" id=\"end_date\" required>";
        $query_string2 = "SELECT DISTINCT tourney_date FROM Tournaments";
            
            $result = mysqli_query($conn, $query_string2);
            $tuple_count = 0;
            while ($row = mysqli_fetch_array($result)) {
                $tuple_count++;
                echo "<option value=\"$row[0]\">$row[0]</option>";
            };
        echo "
        </select>
        <br />
        <br />
        <input type=\"submit\" value=\"Submit\" />
      </form>
    </div>
    
  </body>
</html>
"

?>

</body>
</html>
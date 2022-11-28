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
};

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
    <script>
    function sortTable(table, col) {
        //Returns only the body elements of the table
        var tb = table.tBodies[0];
        //Returns an array of all the rows except the headers
        var tr = Array.prototype.slice.call(tb.rows, 1);
        var i;
        tr = tr.sort(function sortFunction (item1, item2)
        //sortFunction is our Comparable Function, telling the sort function which element to put first
            {
            //Compares the draw size integers
            if(col == 3){
                if(parseInt(item1.cells[col].innerHTML) < parseInt(item2.cells[col].innerHTML)){
                    return -1;
                }
                else if(parseInt(item1.cells[col].innerHTML) > parseInt(item2.cells[col].innerHTML)){
                    return 1;
                }
                else {
                    return 0;
                }
            }
            else{
            //turns the cell into a text with no whitespace and compares it to the next cell, returning an integer telling function who to put first
            return (item1.cells[col].textContent.trim().localeCompare(item2.cells[col].textContent.trim()));
        
        }
        });
        //goes through all the rows and puts the rows into the correct order
        for(i = 0; i < tr.length; ++i)
        {
            tb.appendChild(tr[i]);
        }
    }
    </script>
  </head>
  <body>";

if (!empty($_POST["player_name"])) {
    $player_name = $_POST["player_name"];
    $player_query = "SELECT * FROM Player WHERE winner_name = \"$player_name\"";

    echo "
    <div class=\"header\">
        <h1>Information for $player_name</h1>
    </div>
    ";

    $result = mysqli_query($conn, $player_query);

    $tuple_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        $tuple_count++;
        echo "
        <p><b>Name: </b> $row[1]</p>
        <p><b>Nationality: </b> $row[2]</p>
        <p><b>Handedness: </b> $row[3]</p>
        ";
    }
}

if (!empty($_POST["year"])) {
    $year = $_POST["year"];
    $year_query = "SELECT * FROM Tournaments WHERE tourney_year = \"$year\"";

    echo "
    <div class=\"header\">
        <h1>Information for Tournaments in $year</h1> 
    </div>
    <table id=\"T1\">
        <tr>
            <th onclick=\"sortTable(T1, 0)\">Tournament Name</th>
            <th onclick=\"sortTable(T1, 1)\">Start Date</th>
            <th onclick=\"sortTable(T1, 2)\">Surface</th>
            <th onclick=\"sortTable(T1, 3)\">Draw Size</th>
            <th onclick=\"sortTable(T1, 4)\">Tournament Level</th>
        </tr>
    ";
    $result = mysqli_query($conn, $year_query);
    $tuple_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        $tuple_count++;
        echo "
            <tr>
                <td>$row[1]</td>
                <td>$row[2]</td>
                <td>$row[5]</td>
                <td>$row[4]</td>
                <td>$row[6]</td>
            </tr>
        ";
    };
    echo "</table>";
};



if (!empty($_POST["player"])) {
    $player_name = $_POST["player"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    
    echo "
    <div class=\"header\">
        <h1 class=\"q3-header\">
            Stats for $player_name between $start_date and $end_date
        </h1>
    </div>
    ";

    $stats_query = "
        CALL showAggreggateStatistics('$player_name', '$start_date', '$end_date');
    ";
    
    $result = mysqli_query($conn, $stats_query);

    $tuple_count = 0;
    while ($row = mysqli_fetch_array($result)) {
        $tuple_count++;
        echo "
        <p><b>Total Aces: $row[0]</b></p>
        <p><b>Total Double Faults: $row[1]</b></p>
        ";
    }
}
echo "
</body>
</html>
"
?>

</body>
</html>
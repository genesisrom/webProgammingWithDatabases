<?php
/* 
    require statement makes the file "database.php" required before the page is loaded.
    This file contains the connection to the database and the creation of the tables.
*/
require "database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        html {
            background-image: url(background.webp);
            background-repeat: repeat;
            background-size: 250px;
        }

        body {
            padding-top: 50px;
            padding-bottom: 100px;
            padding-right: 50px;
            padding-left: 50px;
            text-align: center;
            background-color: white;
            margin-bottom: 25px;
            margin-top: 50px;
            margin-right: auto;
            margin-left: auto;
            width: 75%;
        }

        h1 {
            color: deeppink;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        p {
            font-size: larger;
            width: 65%;
            margin-left: auto;
            margin-right: auto;
        }

        form {
            border: 2px dotted;
            padding-top: 20px;
            padding-bottom: 335px;
            margin-left: auto;
            margin-right: auto;
            width: 95%;
        }

        form > label {
            float: left;
            clear:both;
            width: 40%;
            font-size: 20px;
            padding: 5px;
            margin-right: 20px;
        }

        input, select {
            float: left;
            clear: right;
            margin-bottom: 10px;
            font-size: 20px;
            width: 30%;
        }

        #button {
            margin-top: 15px;
            margin-left: 40%;
            padding: 5px;
            width: 25%;
         
        }

        .error {
            color: red;
            float: left;
        }

        #msg {
            padding-top: 30px;
            clear: both;
            font-size: 25px;
            font-style: italic;
            color: deepskyblue;
        }
    </style>
    <title>Volunteer Registration</title>
</head>

<body>
    <h1>Volunteer Registration</h1>

    <p>
        Thank you for expressing interest in volunteering for the Hello Kitty Parade!!
        <br>Please fill out the form to sign up for a shift. You may register for more than one time slot.
        <br>Duplicate registrations (for the same time slot) will not be accepted.
    </p>

    <?php
    // intitalizations of variables to empty Strings
    $fname = "";
    $lname = "";
    $email = "";
    $fnameErr = "";
    $lnameErr = "";
    $msg = "";

    // if form is submitted using the method post, it vaildates the data entered 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // sets name variable to POST global variable 
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $shift = $_POST['selectedShift'];

        // checking to see if first name is empty
        if (!preg_match('/^[a-zA-Z ]*$/', $fname)) {
            $fnameErr = "Please enter a valid first name.";

            // checking to see if last name is empty
        } else if (!preg_match('/^[a-zA-Z ]*$/', $lname)) {
            $lnameErr = "Please enter a valid last name.";


        } else {
            // names are valid which means $fnameErr and $lnameErr are empty. 
            if ($fnameErr == '' && $lnameErr == '') {
                // Insert into volunteers table if that volunteer information does not yet exist
                $query = "INSERT INTO volunteerEvent.volunteerInfo (firstName, lastName, email) 
                            SELECT '$fname', '$lname', '$email'
                            WHERE NOT EXISTS (
                                SELECT firstName, lastName, email 
                                FROM volunteerEvent.volunteerInfo
                                WHERE firstName = '$fname' AND lastname = '$lname' AND email='$email')";
                $result = $conn->query($query);
                if (!$result)
                    die("Fatal Error6");
            }

            // seleting the volunteerID from the volunteerInfo table where it matches the inputed firstName, lastName, and email
            $query = "SELECT volunteerID FROM volunteerInfo WHERE firstName = '$fname' AND lastName = '$lname' AND email = '$email'";
            $result = $conn->query($query);
            if (!$result) {
                die("Fatal Error7");
            } else {
                //Fetches the volunteer ID from the result 
                $row = $result->fetch_assoc();
                $volunteerID = $row['volunteerID'];
            }

            // Checking to see if the volunteerID and shiftTime combination does not exist and sets this count as 'count'
            $query = "SELECT COUNT(*) as count FROM takenShifts WHERE volunteerID = '$volunteerID' AND shiftTime = '$shift'";
            $result = $conn->query($query);
            if (!$result)
                die("Fatal Error1");

            /* 
                Fetches the association of the result into an associative array and 
                then sets the $count variable to the specific index ('count') of that associative array 
            */
            $row = $result->fetch_assoc();
            $count = $row['count'];

            /*
                if the $count variable is equal to 0, then new data has to be inserted into the takenShifts table
                (equal to 0 means that the result of the query above returned a count of 0 which means that data does not exist)
            */
            if ($count == 0) {
                // volunteerID + shiftTime combination does not exist therefore query must insert the new field into the takenShifts table
                $query = "INSERT INTO takenShifts (volunteerID, shiftTime) VALUES ('$volunteerID', '$shift')";
                $result = $conn->query($query);
                if (!$result) {
                    die("Fatal Error2");
                } else {
                    // New data is successfully added to takenShifts table, therefore $msg must be set to a success message.
                    // $fname, $lname, and $email fields set back to empty Strings
                    $msg = "Thank you for registering to volunteer, $fname!";
                    $fname = "";
                    $lname = "";
                    $email = "";
                }

                /* 
                    Query is selecting all the columns that have the same volunteerID.
                    (used to see if the same volunteer has signed up for multiple different shifts)
                    It then sets this count as 'count2'
                */
                $query = "SELECT COUNT(*) as count2 FROM takenShifts WHERE volunteerID = '$volunteerID'";
                $result = $conn->query($query);
                if (!$result)
                    die("FatalError3");

                /* 
                    Fetches the association of the result into an associative array and 
                    then sets the $count2 variable to that specific index ('count2') of that associative array
                */
                $row2 = $result->fetch_assoc();
                $count2 = $row2['count2'];

                /*
                    If $count2 is greater than 1, which means that the volunteer has signed up for multiple different volunteer shifts,
                    then the $msg must be set to different message about their generous time. 
                    - has to fetch $fname from the post method since earlier it was set back to an empty String
                    - $fname, $lname, and $email fields set back to empty Strings
                */
                if ($count2 > 1) {
                    $fname = $_POST['fname'];
                    $msg = "Thank you for being so generous with your time, $fname";
                    $fname = "";
                    $lname = "";
                    $email = "";
                }
            } else {
                // volunteerID and shiftTime combination already exists, therefore $msg must display that the registration was not processed. 
                // $fname, $lname, and $email fields set back to empty Strings
                $msg = "Duplicate registration not processed. Thanks $fname!";
                $fname = "";
                $lname = "";
                $email = "";
            }
        }
    }
    ?>

    <form action="register.php" method="post">
        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" required>
        <!-- placeholder for error message -->
        <label class="error"><?php echo $fnameErr;?></label>

        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" required>
        <!-- placeholder for error message -->
        <label class="error"><?php echo $lnameErr;?></label>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>

        <!-- Volunteer Shifts-->
        <label for="selectedShift">Volunteer Shift</label>
        <select name="selectedShift" id="selectedShift" size=4 required>
            <?php
            // Intialization of associative array that holds all free shift times and their available slots which starts at (5)
            $freeShifts = array("12pm - 1pm" => 5, "1pm - 2pm" => 5, "2pm - 3pm" => 5, "3pm - 4pm" => 5);

            /* 
                Loop used to process through $freeShifts associative array
                $shiftTime: represents the key
                $openSlots: represent the values 
            */
            foreach ($freeShifts as $shiftTime => $openSlots) {

                // query the volunteerEvent database 
                $query = "USE volunteerEvent";
                $result = $conn->query($query);
                if (!$result)
                    die("Fatal Error2");

                /*
                    query to count the number of fields there are in the takenShifts table 
                    where the shiftTime column is equal to the $shiftTime from the $freeShifts array. 
                    It then sets this count as 'takenSlots'.
                */
                $query = "SELECT COUNT(shiftTime) as takenSlots FROM takenShifts WHERE shiftTime = '$shiftTime'";
                $result = $conn->query($query);
                if (!$result)
                    die("Fatal Error6");

                /* 
                    Fetches the association of the result into an associative array and 
                    then sets the $takenSlots variable to that specific index ('takenSlots') of that associative array
                */
                $row = $result->fetch_assoc();
                $takenSlots = $row['takenSlots'];

                /*
                    if $takenSlots is 0, then that means no one has signed up for that shiftTime 
                    so the original $openSlots value from the $freeShifts associative array can be used to display the amount of slots open.
                    The result will then be echoed out to be displayed as HTML content. 
                */
                if ($takenSlots == 0) {
                    echo "<option value='$shiftTime'> " . $shiftTime . " (" . $openSlots . " of 5 slots open)</option>";

                /*
                    if $takenSlots are greater than or equal to the original $openSlots value (5) from the foreach loop,
                    then that means a new local variable must be created in order to generate the amount of free slots open. 
                    This variable is $newSlots and it will be set to 0 because anything greater than or equal to 5 means there are no slots available.
                    The result will then be echoed out to be displayed as HTML content.  
                */
                } else if ($takenSlots >= $openSlots) {
                    $newSlots = 0;
                    echo "<option value='$shiftTime' disabled> " . $shiftTime . " (" . $newSlots . " of 5 slots open)</option>";

                /*
                    Anyother value of $takenSlots can be used to determined how many free slots are open. 
                    A local variable ($newSlots) calculates this by subtracting $takenSlots from $openSlots (5). 
                    The result will then be echoed out to be displayed as HTML content. 
                */
                } else {
                    $newSlots = $openSlots - $takenSlots;
                    echo "<option value='$shiftTime' > " . $shiftTime . " (" . $newSlots . " of 5 slots open)</option>";
                }
            }
            ?>
        </select>
        <input id="button" type="submit" value="Register" />
    </form>

    <!-- used to hold the space for $msg variable that will display different messages depending on the input -->
    <h2 id="msg">
        <?php echo $msg; ?>
    </h2>
</body>
</html>
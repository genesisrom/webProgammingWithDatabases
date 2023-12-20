# webProgammingWithDatabases
This includes the final project that I coded to its entirety for CS-230. The specifications are listed below. 

Final Project – Pulling it All Together for a Worthy Cause

Recreate the website shown using the following guidelines:

• The first page should include a countdown timer for the event and a link to the volunteer registration page. Note the following:

	- You should use JavaScript/jQuery to implement the countdown timer.
 	- The countdown time should be to the specific start time of the event (not just to the day of the event). The start time may not be 12:00am.
	- When the event day/time has arrived, the countdown time should be replaced with a message and the link to the volunteer registration page should be removed.
 
• The volunteer registration page should provide a form that allows the user to register to volunteer for the event. Note the following:

	- A volunteer is identified by their first name, last name, and email address.
	- When a volunteer registers for a time slot, the registration view will display again with the fields cleared and a thank you message will be displayed.
	- A volunteer may register for more than one time slot.
	- If a volunteer registers for the same time slot multiple times, the duplicate registrations are not processed and a message is displayed.
	- Five volunteers are needed for each time slot. The selection list options should indicate how many open spots are available for each time slot. Once a time slot is full, the option should be disabled in the list.
	- All fields on the volunteer registration form are required (this can be implemented using HTML).
	- Field values should be validated as follows:
		▪ First and last name fields should only allow letters and spaces and should require at least one letter (ie – all spaces should not be accepted as a name)
		▪ Email field can be verified using HTML type=”email” w/ no additional validation necessary
		▪ If a field value is invalid, a message should be displayed beside the field and the current field values (first name, last name, and email) should remain displayed in the form.
	
• You will need to design a database to support this application. Note the following:

	- The database should be normalized to 3rd Normal Form (3NF).
	- The database and tables should be created programmatically. You should use the username “mcuser” and the password “Pa55word” when connecting to the database.
	- The database tables should be populated programmatically by the application.

 All resources used are listed below and what exactly I used them for. 

To create countdown timer: https://www.w3schools.com/howto/howto_js_countdown.asp

To make timer countdown on page load: https://stackoverflow.com/questions/58614000/load-countdown-on-page-load
- (to make sure that the timer worked not a second later, I also referenced to the example we did in class)

Used in the php code on lines where we fetched the row of the results as an associative array so that we could get the correct volunteerID to add to the takenShifts table:
https://www.w3schools.com/php/func_mysqli_fetch_assoc.asp
- Used in combination with ex) $count2 = $row2['count2'];
- This was done in order to be able to use the result row in other code


Used to figure out how to disable the time slot option once all the spots have been filled: https://www.w3schools.com/tags/att_option_disabled.asp

Used to make sure that all time slots are displayed on the web page in the select tag and not have it be a scroll down menu: https://www.sololearn.com/en/Discuss/161737/how-can-we-change-the-select-dropdown-to-display-all-options-of-the-select-tag

Where not exists function to add data that does not already exist: https://mitch.codes/sql-tip-insert-where-not-exists/
- Used when I needed to add to the volunteerInfo table only if that volunteer has not already signed up which gives the volunteer a volunteerID
- Lines 137-142

Used when querying some of the data in the SELECT statements (it labels that column for the duration of that query set only: https://www.w3schools.com/sql/sql_ref_as.asp

SELECT (columnName) or SELECT COUNT(* or columnName) statements
- I generated these statements by using the class notes. 
- I used http://localhost/phpmyadmin/ in the SQL section (where we first coded SQL statements in class) to write and execute my SQL statements to see if they fetched me the correct data that I needed. 
- Lines 149, 160, 196

To create the dynamic select shiftTime input: 
- I again used the COUNT function for this. 
- I got help from you, the instructor via our office hours to help me understand this process. 



<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "2503";
    $dbname = "devadb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Escape user inputs for security
        $name = $conn->real_escape_string($_POST['name']);
        $regNo = $conn->real_escape_string($_POST['reg-no']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone-no']);
        $course = $conn->real_escape_string($_POST['course']);

        // File upload handling
        $imagePath = "uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);

        $resumePath = "uploads/" . basename($_FILES["resume"]["name"]);
        move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath);

        // Insert data into the database
        $sql = "INSERT INTO registration_data (name, reg_no, email, phone, course, image_path, resume_path)
                VALUES ('$name', '$regNo', '$email', '$phone', '$course', '$imagePath', '$resumePath')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
    ?>

    <div class="container">
        <h2>Registration Form</h2>
        <form id="registrationForm" action="#" method="post" enctype="multipart/form-data">
            <!-- Your existing HTML form content goes here -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" required>
                <span id="nameError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="reg-no">Registration Number:</label>
                <input type="text" id="reg-no" name="reg-no" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" required placeholder="email">
                <span id="emailError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="phone-no">Phone Number:</label>
                <input type="text" id="phone-no" name="phone-no" required>
                <span id="phoneError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="">Select a course</option>
                    <option value="Course 1">Computer Science</option>
                    <option value="Course 2">Maths</option>
                    <option value="Course 3">Statistics</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="resume">Upload Resume:</label>
                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function (event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        document.getElementById("name").addEventListener("input", function () {
            converttoupper(this);
        });

        document.getElementById("email").addEventListener("blur", function () {
            validateEmail(this);
        });

        document.getElementById("phone-no").addEventListener("input", function () {
            validatePhone(this);
        });

        function converttoupper(inputField) {
            inputField.value = inputField.value.toUpperCase();
        }

        function validateEmail(emailField) {
            // Reset any previous error messages
            document.getElementById("emailError").textContent = "";

            // Get the email value
            var email = emailField.value;

            // Email validation using a regular expression
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (!email.match(emailPattern)) {
                document.getElementById("emailError").textContent = "Invalid email format";
                document.getElementById("emailError").style.color = 'red';
            }
        }

        function validatePhone(phoneField) {
            // Reset any previous error messages
            document.getElementById("phoneError").textContent = "";

            // Get the phone number value
            var phone = phoneField.value;

            // Phone number validation (only numbers and exactly 10 digits)
            var phonePattern = /^\d{10}$/;
            if (!phone.match(phonePattern)) {
                document.getElementById("phoneError").textContent = "Invalid phone number format";
                document.getElementById("phoneError").style.color = 'red';
            }
        }

        function validateForm() {
            // Reset any previous error messages
            document.getElementById("nameError").textContent = "";
            document.getElementById("emailError").textContent = "";
            document.getElementById("phoneError").textContent = "";

            // Get the values of name, email, and phone number fields
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var phone = document.getElementById("phone-no").value;

            // Email validation using a regular expression
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (!email.match(emailPattern)) {
                document.getElementById("emailError").textContent = "Invalid email format";
                return false;
            }

            // Phone number validation (only numbers and exactly 10 digits)
            var phonePattern = /^\d{10}$/;
            if (!phone.match(phonePattern)) {
                document.getElementById("phoneError").textContent = "Phone number should be exactly 10 digits";
                return false;
            }

            return true; // Form submission will occur if all validations pass
        }
    </script>

</body>
</html>

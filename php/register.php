<?php
session_start();
require_once 'auth_middleware.php';
redirectIfLoggedIn();

$name = $email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name should contain only letters and spaces";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long";
    } elseif (strlen($name) > 50) {
        $errors[] = "Name must not exceed 50 characters";
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email must not exceed 100 characters";
	} else {
		require_once 'rdb.php';
		$conn = connect_db();
		$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
		$check->bind_param('s', $email);
		$check->execute();
		$res = $check->get_result();
		if ($res && $res->num_rows > 0) {
			$errors[] = "Email is already registered";
		}
		$check->close();
		$conn->close();
	}

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (strlen($password) > 50) {
        $errors[] = "Password must not exceed 50 characters";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character";
    }

	if (empty($errors)) {
	
		require_once 'rdb.php';
		$conn = connect_db();
		
		$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
		if ($stmt) {
			$stmt->bind_param('sss', $name, $email, $password);
			if ($stmt->execute()) {
				$_SESSION['registration_success'] = "Registration successful!";
				$stmt->close();
				$conn->close();
				header("Location: login.php");
				exit();
			} else {
				$errors[] = 'Database error: ' . $stmt->error;
			}
			$stmt->close();
		} else {
			$errors[] = 'Database error: ' . $conn->error;
		}
		$conn->close();

		if (!empty($errors)) {
			$_SESSION['registration_errors'] = $errors;
		}
	} else {
		$_SESSION['registration_errors'] = $errors;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Register - Skyline Coffee Shop</title>
		<link rel="stylesheet" href="../css/register.css" />
		<link
			href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Open+Sans:wght@400;600&display=swap"
			rel="stylesheet"
		/>
	</head>
	<body>
		<div class="container">
			<nav class="navbar">
				<ul class="nav-links">
					<li><a href="../index.php">Home</a></li>
					<li><a href="menu.php">Menu</a></li>
					<li><a href="customer/profile.php">Profile</a></li>
					
				</ul>
			</nav>
			<div class="welcome-box">
				<div class="hero-section">
					<img
						src="../resources/Brown Modern Circle Coffee Shop Logo.png"
						alt="Cafe Logo"
						class="logo"
					/>
					<h1>Register at Skyline Coffee Shop</h1>
					<p>Create an account to start ordering or managing!</p>
				</div>
				<section class="register-section">
					<?php
					if (isset($_SESSION['registration_errors'])) {
						echo '<div class="error-messages">';
						foreach ($_SESSION['registration_errors'] as $error) {
							echo '<p class="error">' . htmlspecialchars($error) . '</p>';
						}
						echo '</div>';
						unset($_SESSION['registration_errors']);
					}
					?>
					<form id="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required />
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" id="password" name="password" required />
						</div>
						<button type="submit" class="btn">Register</button>
					</form>
					<div class="links">
						<a href="login.php">Login</a> |
						<a href="../index.php">Back to Home</a>
					</div>
				</section>
				<footer class="footer">
					<div class="footer-content">
						<div class="footer-section" id="contact-section">
							<h3>Contact Us</h3>
							<p>
								Email:
								<a href="mailto:info@skylinecoffee.com"
									>info@skylinecoffee.com</a
								>
							</p>
							<p>Phone: <a href="tel:+8801234567890">+880 123 456 7890</a></p>
							<p>Address: 123 Skyline Avenue, Dhaka</p>
						</div>
						<div class="footer-section" id="about-section">
							<h3>About Us</h3>
							<p>
								We are passionate about serving the finest coffee, crafted with
								love and expertise. Join us for a unique coffee experience!
							</p>
						</div>
						<div class="footer-section">
							<h3>Follow Us</h3>
							<div class="social-links">
								<a
									href="https://facebook.com"
									class="social-icon"
									aria-label="Facebook"
								>
									<img
										src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png"
										alt="Facebook Logo"
										class="social-logo"
									/>
								</a>
								<a
									href="https://instagram.com"
									class="social-icon"
									aria-label="Instagram"
								>
									<img
										src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png"
										alt="Instagram Logo"
										class="social-logo"
									/>
								</a>
								<a href="https://x.com" class="social-icon" aria-label="X">
									<img
										src="https://img.icons8.com/ios-filled/50/ffffff/x.png"
										class="social-logo"
									/>
								</a>
							</div>
						</div>
					</div>
					<div class="footer-bottom">
						<p>Skyline Coffee Shop - Where Every Sip Tells a Story</p>
						<p>&copy; 2025 Skyline Coffee Shop. All rights reserved.</p>
					</div>
				</footer>
			</div>
		</div>
	</body>
</html>
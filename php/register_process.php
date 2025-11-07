<?php
session_start();
require_once 'db_connect.php';
require_once 'email_functions.php';

// Helper function for name validation
function validateName($name, $field_name, $is_optional = false) {
    if (empty($name)) {
        return $is_optional ? "" : "$field_name is required.";
    }

    if (strlen($name) < 2 && !$is_optional) return "$field_name must be at least 2 characters.";
    if (strlen($name) > 50) return "$field_name cannot exceed 50 characters.";
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) return "$field_name should only contain letters and spaces.";
    if (preg_match('/\s{2,}/', $name)) return "$field_name should not contain multiple spaces.";
    if (strlen($name) > 1 && strtoupper($name) === $name) return "$field_name should not be all uppercase.";
    if (preg_match('/([a-zA-Z])\\1{2,}/i', $name)) return "$field_name cannot contain three or more repeated letters.";

    $words = preg_split('/\s+/', $name);
    foreach ($words as $word) {
        if (strlen($word) > 0 && !preg_match('/^[A-Z][a-z]*$/', $word) && !preg_match('/^[A-Z]$/', $word)) {
            return "Each word in $field_name must start with an uppercase letter.";
        }
    }

    return "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = [];

    // Sanitize and retrieve input
    $input['id_number'] = trim($_POST['id_number']);
    $input['email'] = trim($_POST['email']);
    $input['first_name'] = trim($_POST['first_name']);
    $input['middle_name'] = trim($_POST['middle_name']);
    $input['family_name'] = trim($_POST['family_name']);
    $input['name_extension'] = trim($_POST['name_extension']);
    $input['birthdate'] = trim($_POST['birthdate']);
    $input['purok_street'] = trim($_POST['purok_street']);
    $input['barangay'] = trim($_POST['barangay']);
    $input['municipality_city'] = trim($_POST['municipality_city']);
    $input['province'] = trim($_POST['province']);
    $input['country'] = trim($_POST['country']);
    $input['zip_code'] = trim($_POST['zip_code']);
    $input['username'] = trim($_POST['username']);
    $input['password'] = $_POST['password'];
    $input['re_password'] = $_POST['re_password'];
    $input['answer1'] = trim($_POST['answer1']);
    $input['answer2'] = trim($_POST['answer2']);
    $input['answer3'] = trim($_POST['answer3']);

    // Validation
    // ID Number
    if (!preg_match('/^\d{4}-\d{4}$/', $input['id_number'])) {
        $errors['id_number'] = 'ID Number must be in the format xxxx-xxxx.';
    } else {
        $stmt = $pdo->prepare("SELECT id_number FROM users WHERE id_number = ?");
        $stmt->execute([$input['id_number']]);
        if ($stmt->fetch()) {
            $errors['id_number'] = 'ID Number is already registered.';
        }
    }

    // Email
    if (empty($input['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    } else {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$input['email']]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email is already registered.';
        }
    }

    // Names
    $first_name_error = validateName($input['first_name'], 'First Name');
    if ($first_name_error) $errors['first_name'] = $first_name_error;

    $middle_name_error = validateName($input['middle_name'], 'Middle Name', true);
    if ($middle_name_error) $errors['middle_name'] = $middle_name_error;

    $family_name_error = validateName($input['family_name'], 'Family Name');
    if ($family_name_error) $errors['family_name'] = $family_name_error;

    if (!empty($input['name_extension']) && !preg_match('/^[a-zA-Z\s\.]*$/', $input['name_extension'])) {
        $errors['name_extension'] = 'Name Extension contains invalid characters.';
    }

    // Age
    if (empty($input['birthdate'])) {
        $errors['birthdate'] = 'Birthdate is required.';
    } else {
        $birthDate = new DateTime($input['birthdate']);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 18) {
            $errors['birthdate'] = 'You must be at least 18 years old.';
        }
    }

    // Address
    if (empty($input['purok_street'])) $errors['purok_street'] = 'Purok/Street is required.';
    if (empty($input['barangay'])) $errors['barangay'] = 'Barangay is required.';
    if (empty($input['municipality_city'])) $errors['municipality_city'] = 'Municipal/City is required.';
    if (empty($input['province'])) $errors['province'] = 'Province is required.';
    if (empty($input['country'])) $errors['country'] = 'Country is required.';
    if (empty($input['zip_code'])) {
        $errors['zip_code'] = 'Zip Code is required.';
    } elseif (!preg_match('/^\d{4,10}$/', $input['zip_code'])) {
        $errors['zip_code'] = 'Zip Code must contain digits only (4-10).';
    }

    // Username
    if (empty($input['username'])) {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $input['username'])) {
        $errors['username'] = 'Username must be 4-50 characters, letters/numbers/underscore only.';
    } else {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$input['username']]);
        if ($stmt->fetch()) {
            $errors['username'] = 'Username is already taken.';
        }
    }

    // Password
    if (empty($input['password'])) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($input['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    } elseif ($input['password'] !== $input['re_password']) {
        $errors['re_password'] = 'Passwords do not match.';
    }

    // Auth Answers
    if (empty($input['answer1'])) $errors['answer1'] = 'Answer to question 1 is required.';
    if (empty($input['answer2'])) $errors['answer2'] = 'Answer to question 2 is required.';
    if (empty($input['answer3'])) $errors['answer3'] = 'Answer to question 3 is required.';

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header('Location: ../register.php');
        exit();
    } else {
        // Hash password and insert into database
        $hashed_password = password_hash($input['password'], PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO users (id_number, email, first_name, middle_name, family_name, name_extension, birthdate, purok_street, barangay, municipality_city, province, country, zip_code, username, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $input['id_number'], $input['email'], $input['first_name'], $input['middle_name'], $input['family_name'], $input['name_extension'],
                $input['birthdate'], $input['purok_street'], $input['barangay'], $input['municipality_city'], $input['province'],
                $input['country'], $input['zip_code'], $input['username'], $hashed_password
            ]);

            $answers = [
                ['user_id_number' => $input['id_number'], 'question_id' => 1, 'answer' => password_hash($input['answer1'], PASSWORD_DEFAULT)],
                ['user_id_number' => $input['id_number'], 'question_id' => 2, 'answer' => password_hash($input['answer2'], PASSWORD_DEFAULT)],
                ['user_id_number' => $input['id_number'], 'question_id' => 3, 'answer' => password_hash($input['answer3'], PASSWORD_DEFAULT)]
            ];

            $sql_answers = "INSERT INTO auth_answers (user_id_number, question_id, answer) VALUES (?, ?, ?)";
            $stmt_answers = $pdo->prepare($sql_answers);

            foreach ($answers as $answer) {
                $stmt_answers->execute([$answer['user_id_number'], $answer['question_id'], $answer['answer']]);
            }

            $pdo->commit();

            // Send placeholder registration email
            send_registration_email($input['email'], $input['username']);

            $_SESSION['success_message'] = 'Registration successful! Please log in.';
            header('Location: ../login.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['errors'] = ['db_error' => 'Registration failed. Please try again. ' . $e->getMessage()];
            $_SESSION['input'] = $input;
            header('Location: ../register.php');
            exit();
        }
    }
} else {
    header('Location: ../register.php');
    exit();
}
?>

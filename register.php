<?php
session_start();
require_once 'php/templates/header-auth.php';

$errors = $_SESSION['errors'] ?? [];
$input = $_SESSION['input'] ?? [];
unset($_SESSION['errors'], $_SESSION['input']);
?>

<form action="php/register_process.php" method="post" id="registration-form" novalidate>
    <h1 class="form-title">Registration Form</h1>

    <!-- Personal Information Section -->
    <div class="form-section">
        <div class="section-title">Personal Information</div>
        <div class="form-grid personal-info-grid">
            <div class="form-group id-number">
                <label for="id_number">ID Number <span class="required">*</span></label>
                <input type="text" name="id_number" id="id_number" placeholder="xxxx-xxxx" value="<?php echo htmlspecialchars($input['id_number'] ?? ''); ?>" required>
                <div class="error" id="id_number_error"><?php echo htmlspecialchars($errors['id_number'] ?? ''); ?></div>
            </div>
            <div class="form-group first-name">
                <label for="first_name">First Name <span class="required">*</span></label>
                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($input['first_name'] ?? ''); ?>" required>
                <div class="error" id="first_name_error"><?php echo htmlspecialchars($errors['first_name'] ?? ''); ?></div>
            </div>
            <div class="form-group middle-name">
                <label for="middle_name">Middle Name <span class="optional">(optional)</span></label>
                <input type="text" name="middle_name" id="middle_name" value="<?php echo htmlspecialchars($input['middle_name'] ?? ''); ?>">
                <div class="error" id="middle_name_error"><?php echo htmlspecialchars($errors['middle_name'] ?? ''); ?></div>
            </div>
            <div class="form-group last-name">
                <label for="family_name">Last Name <span class="required">*</span></label>
                <input type="text" name="family_name" id="family_name" value="<?php echo htmlspecialchars($input['family_name'] ?? ''); ?>" required>
                <div class="error" id="family_name_error"><?php echo htmlspecialchars($errors['family_name'] ?? ''); ?></div>
            </div>
            <div class="form-group suffix">
                <label for="name_extension">Suffix <span class="optional">(optional)</span></label>
                <input type="text" name="name_extension" id="name_extension" value="<?php echo htmlspecialchars($input['name_extension'] ?? ''); ?>">
                <div class="error" id="name_extension_error"><?php echo htmlspecialchars($errors['name_extension'] ?? ''); ?></div>
            </div>
            <div class="form-group email">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>" required>
                <div class="error" id="email_error"><?php echo htmlspecialchars($errors['email'] ?? ''); ?></div>
            </div>
            <div class="form-group birthdate">
                <label for="birthdate">Birthdate <span class="required">*</span></label>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($input['birthdate'] ?? ''); ?>" required>
                <div class="error" id="birthdate_error"><?php echo htmlspecialchars($errors['birthdate'] ?? ''); ?></div>
            </div>
            <div class="form-group age">
                <label for="age">Age</label>
                <input type="text" name="age" id="age" readonly>
            </div>
        </div>
    </div>

    <!-- Address Information Section -->
    <div class="form-section">
        <div class="section-title">Address Information <span class="required">*</span></div>
        <div class="form-grid address-info-grid">
            <div class="form-group purok-street">
                <label for="purok_street">Purok/Street</label>
                <input type="text" name="purok_street" id="purok_street" value="<?php echo htmlspecialchars($input['purok_street'] ?? ''); ?>" required>
                <div class="error" id="purok_street_error"><?php echo htmlspecialchars($errors['purok_street'] ?? ''); ?></div>
            </div>
            <div class="form-group barangay">
                <label for="barangay">Barangay</label>
                <input type="text" name="barangay" id="barangay" value="<?php echo htmlspecialchars($input['barangay'] ?? ''); ?>" required>
                <div class="error" id="barangay_error"><?php echo htmlspecialchars($errors['barangay'] ?? ''); ?></div>
            </div>
            <div class="form-group municipality-city">
                <label for="municipality_city">Municipality/City</label>
                <input type="text" name="municipality_city" id="municipality_city" value="<?php echo htmlspecialchars($input['municipality_city'] ?? ''); ?>" required>
                <div class="error" id="municipality_city_error"><?php echo htmlspecialchars($errors['municipality_city'] ?? ''); ?></div>
            </div>
            <div class="form-group province">
                <label for="province">Province</label>
                <input type="text" name="province" id="province" value="<?php echo htmlspecialchars($input['province'] ?? ''); ?>" required>
                <div class="error" id="province_error"><?php echo htmlspecialchars($errors['province'] ?? ''); ?></div>
            </div>
            <div class="form-group country">
                <label for="country">Country</label>
                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($input['country'] ?? ''); ?>" required>
                <div class="error" id="country_error"><?php echo htmlspecialchars($errors['country'] ?? ''); ?></div>
            </div>
            <div class="form-group zip-code">
                <label for="zip_code">ZIP Code</label>
                <input type="text" name="zip_code" id="zip_code" value="<?php echo htmlspecialchars($input['zip_code'] ?? ''); ?>" required>
                <div class="error" id="zip_code_error"><?php echo htmlspecialchars($errors['zip_code'] ?? ''); ?></div>
            </div>
        </div>
    </div>

    <!-- Account & Authentication Section -->
    <div class="form-section">
        <div class="section-title">Account & Authentication</div>
        <div class="form-grid account-info-grid">
            <div class="form-group password">
                <label for="password">Password <span class="required">*</span></label>
                <div class="password-field">
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" data-target="password"><i class="fa fa-eye"></i></button>
                </div>
                <div id="password-strength"></div>
                <div class="error" id="password_error"><?php echo htmlspecialchars($errors['password'] ?? ''); ?></div>
            </div>
            <div class="form-group re-password">
                <label for="re_password">Re-enter Password <span class="required">*</span></label>
                <div class="password-field">
                    <input type="password" name="re_password" id="re_password" required>
                    <button type="button" class="toggle-password" data-target="re_password"><i class="fa fa-eye"></i></button>
                </div>
                <div class="error" id="re_password_error"><?php echo htmlspecialchars($errors['re_password'] ?? ''); ?></div>
            </div>
        </div>
    </div>

    <!-- Authentication Questions Section -->
    <div class="form-section">
        <div class="section-title">Authentication Questions</div>
        <div class="form-grid auth-questions-grid">
            <div class="form-group question1">
                <label for="answer1">Who is your best friend in Elementary? <span class="required">*</span></label>
                <input type="password" name="answer1" id="answer1" required>
                <div class="error" id="answer1_error"><?php echo htmlspecialchars($errors['answer1'] ?? ''); ?></div>
            </div>
            <div class="form-group question2">
                <label for="answer2">What is the name of your favorite pet? <span class="required">*</span></label>
                <input type="password" name="answer2" id="answer2" required>
                <div class="error" id="answer2_error"><?php echo htmlspecialchars($errors['answer2'] ?? ''); ?></div>
            </div>
            <div class="form-group question3">
                <label for="answer3">Who is your favorite teacher in high school? <span class="required">*</span></label>
                <input type="password" name="answer3" id="answer3" required>
                <div class="error" id="answer3_error"><?php echo htmlspecialchars($errors['answer3'] ?? ''); ?></div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Register</button>
    </div>
</form>

<?php require_once 'php/templates/footer.php'; ?>

<?php //Start building your awesome child theme functions

add_action( 'wp_enqueue_scripts', 'miniture_enqueue_styles', 100 );
function miniture_enqueue_styles() {
    wp_enqueue_style( 'miniture-child-styles',  get_stylesheet_directory_uri() . '/style.css', array( 'nova-miniture-styles' ), wp_get_theme()->get('Version') );
}

// === Hide prices and "Add to Cart" button for non-approved users ===
add_filter('woocommerce_get_price_html', 'ke_hide_price_for_non_customers', 10, 2);
function ke_hide_price_for_non_customers($price, $product) {
    // 如果用户未登录，直接提示登录
    if (!is_user_logged_in()) {
        return '<a href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '">Login to view price</a>';
    }

    // 获取当前用户角色
    $user = wp_get_current_user();
    $allowed_roles = array('customer'); // 只有customer角色可以看到价格

    // 如果不是customer，就隐藏价格
    if (!array_intersect($allowed_roles, (array)$user->roles)) {
        return '<span class="price-locked">Price available for approved customers only</span>';
    }

    // 如果是customer，正常显示价格
    return $price;
}

// === Hide "Add to Cart" button for non-approved users ===
add_filter('woocommerce_is_purchasable', 'ke_hide_cart_button_for_non_customers', 10, 2);
function ke_hide_cart_button_for_non_customers($purchasable, $product) {
    // 未登录用户不能购买
    if (!is_user_logged_in()) {
        return false;
    }

    // 获取当前用户角色
    $user = wp_get_current_user();
    $allowed_roles = array('customer'); // 只有customer能购买

    // 非customer不能购买
    if (!array_intersect($allowed_roles, (array)$user->roles)) {
        return false;
    }

    // customer可以购买
    return $purchasable;
}

/**
 * === B2B Registration Form for Project TCG ===
 */
add_action('woocommerce_register_form_start', 'projecttcg_b2b_registration_form');
function projecttcg_b2b_registration_form() {
    ?>
    <h3>This is Account Sign Up Page.</h3>
    <p>Project TCG is a distributor and does not sell to end consumers.</p>

    <h4>Customer Information</h4>
    <p class="form-row form-row-wide">
        <label for="contact_name">Contact Name&nbsp;<span class="required">*</span></label>
        <input type="text" name="contact_name" id="contact_name" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="company_name">Company Name&nbsp;<span class="required">*</span></label>
        <input type="text" name="company_name" id="company_name" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="reg_company_website">Company Website*</label>
        <input type="text" name="reg_company_website" id="reg_company_website" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="main_email">Main Contact Email&nbsp;<span class="required">*</span></label>
        <input type="email" name="main_email" id="main_email" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="business_phone">Business Phone Number&nbsp;<span class="required">*</span></label>
        <input type="tel" name="business_phone" id="business_phone" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="hear_about">How did you hear about us?&nbsp;<span class="required">*</span></label>
        <input type="text" name="hear_about" id="hear_about" required>
    </p>

    <h4>Customer Billing Address</h4>
    <p><input type="text" name="billing_first_name" placeholder="First Name *" required></p>
    <p><input type="text" name="billing_last_name" placeholder="Last Name *" required></p>
    <p><input type="text" name="billing_address" placeholder="Address *" required></p>
    <p><input type="text" name="billing_city" placeholder="City *" required></p>
    <p><input type="text" name="billing_state" placeholder="State / County *" required></p>
    <p><input type="text" name="billing_postcode" placeholder="Postcode / ZIP *" required></p>

    <h4>Customer Shipping Address</h4>
    <p><em>Copy from billing address if same</em></p>
    <p><input type="text" name="shipping_first_name" placeholder="First Name *" required></p>
    <p><input type="text" name="shipping_last_name" placeholder="Last Name *" required></p>
    <p><input type="text" name="shipping_address" placeholder="Address *" required></p>
    <p><input type="text" name="shipping_city" placeholder="City *" required></p>
    <p><input type="text" name="shipping_state" placeholder="State / County *" required></p>
    <p><input type="text" name="shipping_postcode" placeholder="Postcode / ZIP *" required></p>

    <h4>Licenses & Documents</h4>
    <p>
        <label for="business_license">Business license / Reseller’s license *</label><br>
        <input type="file" name="business_license" id="business_license" accept=".jpg,.jpeg,.png,.docx,.pdf" required>
    </p>
    <p><a href="https://wholesale.projectke.com//wp-content/uploads/Credit-Card-Authorization-Form.docx" download>Download Credit Card Authorization Form</a></p>
    <p>
        <label for="cc_auth">Credit Card Authorization Upload *</label><br>
        <input type="file" name="cc_auth" id="cc_auth" accept=".jpg,.jpeg,.png,.docx,.pdf" required>
    </p>
    <?php
}
add_action('show_user_profile', 'show_uploaded_files_admin');
add_action('edit_user_profile', 'show_uploaded_files_admin');
function show_uploaded_files_admin($user) {
    $license = get_user_meta($user->ID, 'business_license_url', true);
    $cc_auth = get_user_meta($user->ID, 'cc_auth_url', true);
    ?>
    <h3>Uploaded Documents</h3>
    <table class="form-table">
        <tr>
            <th><label>Business License</label></th>
            <td>
                <?php if ($license): ?>
                    <a href="<?php echo esc_url($license); ?>" target="_blank">View / Download</a>
                <?php else: ?>
                    <em>Not uploaded</em>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><label>Credit Card Authorization Form</label></th>
            <td>
                <?php if ($cc_auth): ?>
                    <a href="<?php echo esc_url($cc_auth); ?>" target="_blank">View / Download</a>
                <?php else: ?>
                    <em>Not uploaded</em>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}
/**
 * === Validate B2B Registration Fields Properly ===
 */
add_action('woocommerce_register_post', 'projecttcg_validate_b2b_fields', 10, 3);
function projecttcg_validate_b2b_fields($username, $email, $errors) {
    // Required fields check
    $required_fields = [
        'contact_name'   => 'Contact Name',
        'company_name'   => 'Company Name',
        'main_email'     => 'Main Contact Email',
        'business_phone' => 'Business Phone Number',
        'hear_about'     => 'How did you hear about us?',
        'billing_address'=> 'Billing Address',
        'billing_city'   => 'Billing City',
        'billing_state'  => 'Billing State',
        'billing_postcode' => 'Billing Postcode',
    ];

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors->add($field . '_error', sprintf(__('%s is required.', 'woocommerce'), $label));
        }
    }

    // Validate file uploads
    if (!isset($_FILES['business_license']) || $_FILES['business_license']['error'] !== UPLOAD_ERR_OK) {
        $errors->add('business_license_error', __('Business license / reseller’s license is required.', 'woocommerce'));
    }
    if (!isset($_FILES['cc_auth']) || $_FILES['cc_auth']['error'] !== UPLOAD_ERR_OK) {
        $errors->add('cc_auth_error', __('Credit Card Authorization Form is required.', 'woocommerce'));
    }
}

/**
 * === Save uploaded files to user meta ===
 */
add_action('woocommerce_created_customer', 'save_b2b_registration_files');
function save_b2b_registration_files($customer_id) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    if (isset($_FILES['business_license']) && $_FILES['business_license']['error'] === UPLOAD_ERR_OK) {
        $upload = wp_handle_upload($_FILES['business_license'], ['test_form' => false]);
        if (isset($upload['url'])) {
            update_user_meta($customer_id, 'business_license_url', esc_url($upload['url']));
        }
    }

    if (isset($_FILES['cc_auth']) && $_FILES['cc_auth']['error'] === UPLOAD_ERR_OK) {
        $upload = wp_handle_upload($_FILES['cc_auth'], ['test_form' => false]);
        if (isset($upload['url'])) {
            update_user_meta($customer_id, 'cc_auth_url', esc_url($upload['url']));
        }
    }
}
// Ensure the Woo register form supports file uploads
add_action('woocommerce_register_form_tag', function () {
    echo ' enctype="multipart/form-data"';
});

/**
 * === Display red border for fields with errors ===
 */
add_action('wp_head', function() {
    ?>
    <style>
        input.field-error {
            border: 2px solid #e53935 !important;
            background-color: #ffebee;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const errors = document.querySelectorAll('.woocommerce-error li');
        errors.forEach(function(error) {
            if(error.textContent.toLowerCase().includes('business license')) {
                document.querySelector('#business_license').classList.add('field-error');
            }
            if(error.textContent.toLowerCase().includes('credit card authorization')) {
                document.querySelector('#cc_auth').classList.add('field-error');
            }
        });
    });
    </script>
    <?php
});

/**
 * === Redirect after successful registration to My Account page with notice ===
 */
add_filter('woocommerce_registration_redirect', 'b2b_registration_redirect');
function b2b_registration_redirect($redirect) {
    wc_add_notice(__('Registration successful! Your account is pending review.', 'woocommerce'), 'success');
    return wc_get_page_permalink('myaccount');
}

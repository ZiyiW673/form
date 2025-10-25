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
        <input type="text" name="contact_name" id="contact_name" value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="company_name">Company Name&nbsp;<span class="required">*</span></label>
        <input type="text" name="company_name" id="company_name" value="<?php echo isset($_POST['company_name']) ? esc_attr($_POST['company_name']) : ''; ?>" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="reg_company_website">Company Website*</label>
        <input type="text" name="reg_company_website" id="reg_company_website" value="<?php echo isset($_POST['reg_company_website']) ? esc_attr($_POST['reg_company_website']) : ''; ?>" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="main_email">Main Contact Email&nbsp;<span class="required">*</span></label>
        <input type="email" name="main_email" id="main_email" value="<?php echo isset($_POST['main_email']) ? esc_attr($_POST['main_email']) : ''; ?>" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="business_phone">Business Phone Number&nbsp;<span class="required">*</span></label>
        <input type="tel" name="business_phone" id="business_phone" value="<?php echo isset($_POST['business_phone']) ? esc_attr($_POST['business_phone']) : ''; ?>" required>
    </p>
    <p class="form-row form-row-wide">
        <label for="hear_about">How did you hear about us?&nbsp;<span class="required">*</span></label>
        <input type="text" name="hear_about" id="hear_about" value="<?php echo isset($_POST['hear_about']) ? esc_attr($_POST['hear_about']) : ''; ?>" required>
    </p>

    <h4>Customer Billing Address</h4>
    <p class="form-row form-row-wide"><input type="text" name="billing_first_name" id="billing_first_name" placeholder="First Name *" value="<?php echo isset($_POST['billing_first_name']) ? esc_attr($_POST['billing_first_name']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="billing_last_name" id="billing_last_name" placeholder="Last Name *" value="<?php echo isset($_POST['billing_last_name']) ? esc_attr($_POST['billing_last_name']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="billing_address" id="billing_address" placeholder="Address *" value="<?php echo isset($_POST['billing_address']) ? esc_attr($_POST['billing_address']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="billing_city" id="billing_city" placeholder="City *" value="<?php echo isset($_POST['billing_city']) ? esc_attr($_POST['billing_city']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="billing_state" id="billing_state" placeholder="State / County *" value="<?php echo isset($_POST['billing_state']) ? esc_attr($_POST['billing_state']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="billing_postcode" id="billing_postcode" placeholder="Postcode / ZIP *" value="<?php echo isset($_POST['billing_postcode']) ? esc_attr($_POST['billing_postcode']) : ''; ?>" required></p>

    <h4>Customer Shipping Address</h4>
    <p><em>Copy from billing address if same</em></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_first_name" id="shipping_first_name" placeholder="First Name *" value="<?php echo isset($_POST['shipping_first_name']) ? esc_attr($_POST['shipping_first_name']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_last_name" id="shipping_last_name" placeholder="Last Name *" value="<?php echo isset($_POST['shipping_last_name']) ? esc_attr($_POST['shipping_last_name']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_address" id="shipping_address" placeholder="Address *" value="<?php echo isset($_POST['shipping_address']) ? esc_attr($_POST['shipping_address']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_city" id="shipping_city" placeholder="City *" value="<?php echo isset($_POST['shipping_city']) ? esc_attr($_POST['shipping_city']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_state" id="shipping_state" placeholder="State / County *" value="<?php echo isset($_POST['shipping_state']) ? esc_attr($_POST['shipping_state']) : ''; ?>" required></p>
    <p class="form-row form-row-wide"><input type="text" name="shipping_postcode" id="shipping_postcode" placeholder="Postcode / ZIP *" value="<?php echo isset($_POST['shipping_postcode']) ? esc_attr($_POST['shipping_postcode']) : ''; ?>" required></p>

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
        'contact_name'        => 'Contact Name',
        'company_name'        => 'Company Name',
        'reg_company_website' => 'Company Website',
        'main_email'          => 'Main Contact Email',
        'business_phone'      => 'Business Phone Number',
        'hear_about'          => 'How did you hear about us?',
        'billing_first_name'  => 'Billing First Name',
        'billing_last_name'   => 'Billing Last Name',
        'billing_address'     => 'Billing Address',
        'billing_city'        => 'Billing City',
        'billing_state'       => 'Billing State',
        'billing_postcode'    => 'Billing Postcode',
        'shipping_first_name' => 'Shipping First Name',
        'shipping_last_name'  => 'Shipping Last Name',
        'shipping_address'    => 'Shipping Address',
        'shipping_city'       => 'Shipping City',
        'shipping_state'      => 'Shipping State',
        'shipping_postcode'   => 'Shipping Postcode',
    ];

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors->add($field . '_error', sprintf(__('%s is required.', 'woocommerce'), $label));
        }
    }

    // Validate company website URL
    if (!empty($_POST['reg_company_website'])) {
        $website = trim($_POST['reg_company_website']);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $errors->add('reg_company_website_error', __('Please enter a valid company website URL (including http:// or https://).', 'woocommerce'));
        }
    }

    // Validate main contact email address
    if (!empty($_POST['main_email'])) {
        $email = trim($_POST['main_email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors->add('main_email_error', __('Please enter a valid email address.', 'woocommerce'));
        }
    }

    // Validate business phone length (allow 10-15 digits)
    if (!empty($_POST['business_phone'])) {
        $phone_digits = preg_replace('/\D+/', '', $_POST['business_phone']);
        $length = strlen($phone_digits);
        if ($length < 10 || $length > 15) {
            $errors->add('business_phone_error', __('Please enter a valid phone number with 10 to 15 digits.', 'woocommerce'));
        }
    }

    // Validate billing and shipping postcode format (US ZIP: 12345 or 12345-6789)
    $postcode_fields = [
        'billing_postcode'  => 'Billing Postcode',
        'shipping_postcode' => 'Shipping Postcode',
    ];

    foreach ($postcode_fields as $field => $label) {
        if (!empty($_POST[$field]) && !preg_match('/^\d{5}(-\d{4})?$/', $_POST[$field])) {
            $errors->add($field . '_error', sprintf(__('Please enter a valid %s (12345 or 12345-6789).', 'woocommerce'), $label));
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
        .field-error {
            border: 2px solid #e53935 !important;
            background-color: #ffebee;
        }

        .field-error-group {
            background-color: #fff5f5;
            border-left: 4px solid #e53935;
            padding-left: 12px;
        }

        .field-error-message {
            color: #c62828;
            font-size: 0.9em;
            margin-top: 6px;
            display: block;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.register');
        if (!form) {
            return;
        }

        const fieldSelectors = {
            contact_name: '#contact_name',
            company_name: '#company_name',
            reg_company_website: '#reg_company_website',
            main_email: '#main_email',
            business_phone: '#business_phone',
            hear_about: '#hear_about',
            billing_first_name: '#billing_first_name',
            billing_last_name: '#billing_last_name',
            billing_address: '#billing_address',
            billing_city: '#billing_city',
            billing_state: '#billing_state',
            billing_postcode: '#billing_postcode',
            shipping_first_name: '#shipping_first_name',
            shipping_last_name: '#shipping_last_name',
            shipping_address: '#shipping_address',
            shipping_city: '#shipping_city',
            shipping_state: '#shipping_state',
            shipping_postcode: '#shipping_postcode',
            business_license: '#business_license',
            cc_auth: '#cc_auth'
        };

        const validators = {
            contact_name: value => value.trim() !== '',
            company_name: value => value.trim() !== '',
            reg_company_website: value => /^https?:\/\/.+/i.test(value) && isValidUrl(value),
            main_email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            business_phone: value => {
                const digits = value.replace(/\D+/g, '');
                return digits.length >= 10 && digits.length <= 15;
            },
            hear_about: value => value.trim() !== '',
            billing_first_name: value => value.trim() !== '',
            billing_last_name: value => value.trim() !== '',
            billing_address: value => value.trim() !== '',
            billing_city: value => value.trim() !== '',
            billing_state: value => value.trim() !== '',
            billing_postcode: value => /^\d{5}(-\d{4})?$/.test(value.trim()),
            shipping_first_name: value => value.trim() !== '',
            shipping_last_name: value => value.trim() !== '',
            shipping_address: value => value.trim() !== '',
            shipping_city: value => value.trim() !== '',
            shipping_state: value => value.trim() !== '',
            shipping_postcode: value => /^\d{5}(-\d{4})?$/.test(value.trim()),
            business_license: () => {
                const field = document.querySelector(fieldSelectors.business_license);
                return field && field.files && field.files.length > 0;
            },
            cc_auth: () => {
                const field = document.querySelector(fieldSelectors.cc_auth);
                return field && field.files && field.files.length > 0;
            }
        };

        const errorMessages = {
            contact_name: 'Contact Name is required.',
            company_name: 'Company Name is required.',
            reg_company_website: 'Please enter a valid company website URL (include http:// or https://).',
            main_email: 'Please enter a valid email address.',
            business_phone: 'Please enter a valid phone number with 10 to 15 digits.',
            hear_about: 'Please let us know how you heard about us.',
            billing_first_name: 'Billing first name is required.',
            billing_last_name: 'Billing last name is required.',
            billing_address: 'Billing address is required.',
            billing_city: 'Billing city is required.',
            billing_state: 'Billing state / county is required.',
            billing_postcode: 'Please enter a valid billing postcode (12345 or 12345-6789).',
            shipping_first_name: 'Shipping first name is required.',
            shipping_last_name: 'Shipping last name is required.',
            shipping_address: 'Shipping address is required.',
            shipping_city: 'Shipping city is required.',
            shipping_state: 'Shipping state / county is required.',
            shipping_postcode: 'Please enter a valid shipping postcode (12345 or 12345-6789).',
            business_license: 'Business license / reseller’s license upload is required.',
            cc_auth: 'Credit Card Authorization upload is required.'
        };

        function isValidUrl(value) {
            try {
                new URL(value);
                return true;
            } catch (e) {
                return false;
            }
        }

        function clearFieldError(field) {
            const element = document.querySelector(fieldSelectors[field]);
            if (!element) {
                return;
            }
            element.classList.remove('field-error');
            const parent = element.closest('p');
            if (parent) {
                parent.classList.remove('field-error-group');
                const message = parent.querySelector('.field-error-message');
                if (message) {
                    message.remove();
                }
            }
        }

        function setFieldError(field, messageText) {
            const element = document.querySelector(fieldSelectors[field]);
            if (!element) {
                return;
            }
            element.classList.add('field-error');
            const parent = element.closest('p');
            if (parent) {
                parent.classList.add('field-error-group');
                let message = parent.querySelector('.field-error-message');
                if (!message) {
                    message = document.createElement('span');
                    message.className = 'field-error-message';
                    parent.appendChild(message);
                }
                message.textContent = messageText;
            }
        }

        form.addEventListener('submit', function(event) {
            let firstErrorField = null;
            let hasErrors = false;

            Object.keys(validators).forEach(field => {
                clearFieldError(field);
                const selector = fieldSelectors[field];
                const input = document.querySelector(selector);
                if (!input) {
                    return;
                }

                const value = input.type === 'file' ? '' : input.value || '';
                const isValid = validators[field](value);
                if (!isValid) {
                    hasErrors = true;
                    setFieldError(field, errorMessages[field]);
                    if (!firstErrorField) {
                        firstErrorField = input;
                    }
                }
            });

            if (hasErrors) {
                event.preventDefault();
                if (firstErrorField) {
                    firstErrorField.focus();
                }
            }
        });

        Object.keys(fieldSelectors).forEach(field => {
            const element = document.querySelector(fieldSelectors[field]);
            if (!element) {
                return;
            }
            const eventType = element.type === 'file' ? 'change' : 'input';
            element.addEventListener(eventType, () => {
                const value = element.type === 'file' ? '' : element.value || '';
                if (validators[field](value)) {
                    clearFieldError(field);
                }
            });
        });

        const serverErrorKeywords = {
            contact_name: ['contact name'],
            company_name: ['company name'],
            reg_company_website: ['company website'],
            main_email: ['email address', 'main contact email'],
            business_phone: ['phone number'],
            hear_about: ['hear about us'],
            billing_first_name: ['billing first name'],
            billing_last_name: ['billing last name'],
            billing_address: ['billing address'],
            billing_city: ['billing city'],
            billing_state: ['billing state'],
            billing_postcode: ['billing postcode'],
            shipping_first_name: ['shipping first name'],
            shipping_last_name: ['shipping last name'],
            shipping_address: ['shipping address'],
            shipping_city: ['shipping city'],
            shipping_state: ['shipping state'],
            shipping_postcode: ['shipping postcode'],
            business_license: ['business license'],
            cc_auth: ['credit card authorization']
        };

        const allErrorLists = Array.from(document.querySelectorAll('ul.woocommerce-error'));
        const registerErrorLists = allErrorLists.filter(list => {
            if (!form) {
                return false;
            }
            let sibling = list.nextElementSibling;
            while (sibling && sibling.nodeType !== 1) {
                sibling = sibling.nextElementSibling;
            }
            return sibling === form;
        });

        if (registerErrorLists.length > 0) {
            const listsToRemove = new Set();

            registerErrorLists.forEach(list => {
                list.querySelectorAll('li').forEach(error => {
                    const text = error.textContent;
                    const lowerText = text.toLowerCase();
                    let matched = false;
                    Object.keys(serverErrorKeywords).forEach(field => {
                        if (serverErrorKeywords[field].some(keyword => lowerText.includes(keyword))) {
                            setFieldError(field, text.trim());
                            matched = true;
                        }
                    });
                    if (matched) {
                        listsToRemove.add(list);
                    }
                });
            });

            listsToRemove.forEach(list => list.remove());
        }
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

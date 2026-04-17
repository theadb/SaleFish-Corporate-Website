# ActiveCampaign Registration Integration Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Replace the missing Mailchimp AJAX handlers with a full ActiveCampaign integration that captures all three registration forms, sends a dark-themed admin notification email (preview → andrewdb@salefish.app, live → hello@salefish.app), and sends a styled autoresponder to the registrant.

**Architecture:** Three PHP AJAX handler files fill the missing `ajax/` slots in the Salefish plugin. A shared AC API helper handles all REST calls to ActiveCampaign (list ID 4 — "Website Registrations"). Email notifications use `wp_mail()` in preview mode with the Plinthra dark HTML template; the autoresponder is sent the same way for preview, then handed off to an AC automation in production. A `SALEFISH_PREVIEW_MODE` constant in wp-config.php governs which address gets emails.

**Tech Stack:** PHP 7.4+, WordPress AJAX (`wp_ajax_*`), ActiveCampaign REST API v3 (`salefishapp.api-us1.com`), WordPress `wp_mail()`, Laravel Mix (webpack) for JS rebuild.

**Key constants (already known):**
- AC API base: `https://salefishapp.api-us1.com/api/3`
- AC List ID: `4` (Website Registrations)
- AC Custom Field 1: `SaleFish User Group`
- AC Custom Field 2: `Company Name`
- Preview address: `andrewdb@salefish.app`
- Production notify address: `hello@salefish.app`
- From address: `hello@salefish.app`

---

## Task 1: Create the AC API helper class

**Files:**
- Create: `wp-content/plugins/Salefish/includes/class-activecampaign.php`

The AC API key and base URL are sensitive — never hardcode in the plugin. They should live as WordPress options or wp-config constants. For this site, define them as wp-config constants (document that clearly).

**Step 1: Create the directory**
```bash
mkdir -p wp-content/plugins/Salefish/includes
```

**Step 2: Write the class**

```php
<?php
// wp-content/plugins/Salefish/includes/class-activecampaign.php

if ( ! defined( 'ABSPATH' ) ) exit;

class Salefish_ActiveCampaign {

    private $api_key;
    private $api_url;
    const LIST_ID = 4;

    public function __construct() {
        $this->api_key = defined('SALEFISH_AC_KEY') ? SALEFISH_AC_KEY : '';
        $this->api_url = defined('SALEFISH_AC_URL') ? rtrim(SALEFISH_AC_URL, '/') . '/api/3' : '';
    }

    /**
     * Create or update a contact. Returns contact ID on success, false on failure.
     */
    public function upsert_contact( array $data ): string|false {
        $payload = [
            'contact' => array_filter([
                'email'     => $data['email'] ?? '',
                'firstName' => $data['first_name'] ?? '',
                'lastName'  => $data['last_name'] ?? '',
                'phone'     => $data['phone'] ?? '',
            ])
        ];

        $response = $this->request( 'POST', '/contact/sync', $payload );
        if ( is_wp_error($response) ) return false;

        return $response['contact']['id'] ?? false;
    }

    /**
     * Subscribe contact to list LIST_ID.
     */
    public function subscribe_to_list( string $contact_id ): bool {
        $payload = [
            'contactList' => [
                'list'    => self::LIST_ID,
                'contact' => $contact_id,
                'status'  => 1, // 1 = subscribed
            ]
        ];

        $response = $this->request( 'POST', '/contactLists', $payload );
        return ! is_wp_error($response);
    }

    /**
     * Add a tag to a contact (creates tag if it doesn't exist).
     */
    public function add_tag( string $contact_id, string $tag_name ): bool {
        // Get or create the tag
        $tag_id = $this->get_or_create_tag( $tag_name );
        if ( ! $tag_id ) return false;

        $payload = [
            'contactTag' => [
                'contact' => $contact_id,
                'tag'     => $tag_id,
            ]
        ];

        $response = $this->request( 'POST', '/contactTags', $payload );
        return ! is_wp_error($response);
    }

    /**
     * Set a custom field value on a contact.
     * $field_id: 1 = SaleFish User Group, 2 = Company Name
     */
    public function set_field( string $contact_id, int $field_id, string $value ): bool {
        $payload = [
            'fieldValue' => [
                'contact' => $contact_id,
                'field'   => $field_id,
                'value'   => $value,
            ]
        ];

        $response = $this->request( 'POST', '/fieldValues', $payload );
        return ! is_wp_error($response);
    }

    // -------------------------------------------------------------------------

    private function get_or_create_tag( string $tag_name ): string|false {
        // Search existing
        $response = $this->request( 'GET', '/tags?search=' . urlencode($tag_name) );
        if ( ! is_wp_error($response) && ! empty($response['tags']) ) {
            foreach ( $response['tags'] as $t ) {
                if ( strtolower($t['tag']) === strtolower($tag_name) ) {
                    return $t['id'];
                }
            }
        }

        // Create new
        $create = $this->request( 'POST', '/tags', ['tag' => ['tag' => $tag_name, 'tagType' => 'contact']] );
        if ( is_wp_error($create) ) return false;
        return $create['tag']['id'] ?? false;
    }

    private function request( string $method, string $endpoint, array $body = [] ): array|WP_Error {
        if ( empty($this->api_key) || empty($this->api_url) ) {
            return new WP_Error('ac_not_configured', 'ActiveCampaign credentials not set.');
        }

        $args = [
            'method'  => $method,
            'headers' => [
                'Api-Token'    => $this->api_key,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 15,
        ];

        if ( $method !== 'GET' && ! empty($body) ) {
            $args['body'] = wp_json_encode($body);
        }

        $url      = $this->api_url . $endpoint;
        $response = wp_remote_request( $url, $args );

        if ( is_wp_error($response) ) return $response;

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode( wp_remote_retrieve_body($response), true );

        if ( $code >= 400 ) {
            $msg = $body['message'] ?? 'AC API error ' . $code;
            return new WP_Error( 'ac_api_error', $msg );
        }

        return $body ?: [];
    }
}
```

**Step 3: Add credentials to wp-config.php (on the server)**

The developer or site owner must add these two lines to `wp-config.php` before the `/* That's all, stop editing! */` line:

```php
define( 'SALEFISH_AC_KEY', '383f6863ef1a4d9f95e1e2f21c2395650306ff83b12d447e9ffaeb47a58a158996df9c41' );
define( 'SALEFISH_AC_URL', 'https://salefishapp.api-us1.com' );
define( 'SALEFISH_PREVIEW_MODE', true );  // set false when going live
```

**Step 4: Commit**
```bash
git add wp-content/plugins/Salefish/includes/class-activecampaign.php
git commit -m "feat: add ActiveCampaign API helper class"
```

---

## Task 2: Create shared email templates

**Files:**
- Create: `wp-content/plugins/Salefish/includes/email-templates.php`

Both the admin notification and the autoresponder use the same dark Plinthra design system: `#0f0f0f` body, `#1a1a1a` card, `#7c3aed` purple accent, `#a78bfa` purple links.

The SaleFish logo is the same base64 data URI used in Plinthra's `registrationNotification.ts`.

**Step 1: Write the file**

```php
<?php
// wp-content/plugins/Salefish/includes/email-templates.php

if ( ! defined( 'ABSPATH' ) ) exit;

// Base64 SaleFish logo — same URI used in Plinthra registrationNotification.ts
define( 'SALEFISH_EMAIL_LOGO', 'data:image/png;base64,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' );

/**
 * Build the admin notification email HTML.
 *
 * @param array  $fields    Associative array of all form fields.
 * @param string $form_type 'general' | 'agent' | 'partner'
 * @return string HTML string
 */
function salefish_notification_email_html( array $fields, string $form_type ): string {
    $name    = esc_html( trim( ( $fields['name'] ?? '' ) ) );
    $email   = esc_html( $fields['email'] ?? '' );
    $phone   = esc_html( $fields['phone'] ?? '' );
    $company = esc_html( $fields['company'] ?? '' );
    $date    = ( new DateTime() )->format('l, F j, Y \a\t g:i A T');

    $form_labels = [
        'general' => 'General Registration',
        'agent'   => 'Agent Registration',
        'partner' => 'Partner Registration',
    ];
    $form_label = $form_labels[ $form_type ] ?? 'Registration';

    // Build extra fields table rows
    $extra_rows = '';
    $skip = ['name', 'email', 'phone', 'company', 'action', 'nonce'];
    foreach ( $fields as $key => $val ) {
        if ( in_array($key, $skip, true) || $val === '' ) continue;
        $label = ucwords( str_replace('_', ' ', $key) );
        $extra_rows .= sprintf(
            '<tr><td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%%;">%s</td><td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">%s</td></tr>',
            esc_html($label),
            esc_html($val)
        );
    }

    ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New <?php echo $form_label; ?></title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;max-width:600px;">
          <!-- Header -->
          <tr>
            <td style="padding:40px 40px 30px;text-align:center;">
              <img src="<?php echo SALEFISH_EMAIL_LOGO; ?>" alt="SaleFish" style="height:40px;width:auto;margin-bottom:10px;">
            </td>
          </tr>
          <!-- Content -->
          <tr>
            <td style="padding:0 40px 40px;">
              <h1 style="color:#ffffff;font-size:24px;font-weight:600;margin:0 0 10px;text-align:left;">New <?php echo $form_label; ?></h1>
              <p style="color:#a1a1a1;font-size:16px;line-height:1.6;margin:0 0 30px;">A new contact has submitted the <strong style="color:#ffffff;"><?php echo $form_label; ?></strong> form on salefish.app</p>

              <!-- Registrant Card -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td style="background-color:#0f0f0f;border-radius:8px;padding:24px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="padding-bottom:20px;">
                          <div style="font-size:28px;font-weight:700;color:#ffffff;margin-bottom:4px;"><?php echo $name; ?></div>
                          <div style="font-size:14px;color:#7c3aed;"><?php echo $form_label; ?></div>
                        </td>
                      </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
                      <tr>
                        <td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;width:40%;">Email</td>
                        <td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">
                          <a href="mailto:<?php echo $email; ?>" style="color:#a78bfa;text-decoration:none;"><?php echo $email; ?></a>
                        </td>
                      </tr>
                      <?php if ($phone): ?>
                      <tr>
                        <td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">Phone</td>
                        <td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">
                          <a href="tel:<?php echo $phone; ?>" style="color:#a78bfa;text-decoration:none;"><?php echo $phone; ?></a>
                        </td>
                      </tr>
                      <?php endif; ?>
                      <?php if ($company): ?>
                      <tr>
                        <td style="color:#a1a1a1;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;">Company</td>
                        <td style="color:#ffffff;font-size:14px;padding:8px 0;border-bottom:1px solid #2a2a2a;"><?php echo $company; ?></td>
                      </tr>
                      <?php endif; ?>
                      <?php echo $extra_rows; ?>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Timestamp -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td style="background-color:#2a1f3d;border-left:4px solid #7c3aed;border-radius:4px;padding:15px;">
                    <p style="color:#a78bfa;font-size:14px;margin:0;">Submitted on <?php echo $date; ?></p>
                  </td>
                </tr>
              </table>

              <p style="color:#666666;font-size:14px;line-height:1.6;margin:30px 0 0;">This contact has been added to your ActiveCampaign "Website Registrations" list.</p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding:30px 40px;border-top:1px solid #2a2a2a;text-align:center;">
              <p style="color:#666666;font-size:14px;margin:0 0 5px;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>
              <p style="color:#4a4a4a;font-size:13px;margin:0;">Real Estate Sales Management Platform</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    <?php
    return ob_get_clean();
}

/**
 * Build the autoresponder email HTML sent to the registrant.
 *
 * @param string $first_name
 * @param string $form_type  'general' | 'agent' | 'partner'
 * @return string HTML string
 */
function salefish_autoresponder_email_html( string $first_name, string $form_type ): string {
    $first_name = esc_html($first_name);

    $messages = [
        'general' => [
            'headline' => "Thanks for registering, {$first_name}.",
            'body'     => "We've received your registration and will be in touch shortly. In the meantime, feel free to explore what SaleFish can do for your sales team.",
            'cta_text' => 'Explore SaleFish',
            'cta_url'  => 'https://salefish.app',
        ],
        'agent' => [
            'headline' => "Welcome to the SaleFish network, {$first_name}.",
            'body'     => "We've received your agent registration. A member of our team will reach out soon to walk you through the platform and the projects available in your market.",
            'cta_text' => 'Learn More',
            'cta_url'  => 'https://salefish.app',
        ],
        'partner' => [
            'headline' => "Let's build something together, {$first_name}.",
            'body'     => "We've received your partner inquiry. Our partnerships team will review your submission and reach out within one business day.",
            'cta_text' => 'Learn About Partnerships',
            'cta_url'  => 'https://salefish.app/partners',
        ],
    ];

    $msg = $messages[ $form_type ] ?? $messages['general'];

    ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thanks for registering</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background-color:#0f0f0f;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;max-width:600px;">
          <!-- Header -->
          <tr>
            <td style="padding:40px 40px 30px;text-align:center;">
              <img src="<?php echo SALEFISH_EMAIL_LOGO; ?>" alt="SaleFish" style="height:40px;width:auto;margin-bottom:10px;">
            </td>
          </tr>
          <!-- Content -->
          <tr>
            <td style="padding:0 40px 40px;">
              <h1 style="color:#ffffff;font-size:28px;font-weight:700;margin:0 0 20px;text-align:left;line-height:1.2;"><?php echo $msg['headline']; ?></h1>
              <p style="color:#a1a1a1;font-size:16px;line-height:1.7;margin:0 0 30px;"><?php echo $msg['body']; ?></p>

              <!-- CTA -->
              <table cellpadding="0" cellspacing="0" style="margin:30px 0;">
                <tr>
                  <td style="background-color:#7c3aed;border-radius:6px;padding:0;">
                    <a href="<?php echo esc_url($msg['cta_url']); ?>"
                       style="display:inline-block;padding:14px 28px;color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;letter-spacing:0.3px;">
                      <?php echo esc_html($msg['cta_text']); ?>
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Divider -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                <tr><td style="border-top:1px solid #2a2a2a;"></td></tr>
              </table>

              <p style="color:#666666;font-size:13px;line-height:1.6;margin:0;">You're receiving this because you registered at <a href="https://salefish.app" style="color:#a78bfa;text-decoration:none;">salefish.app</a>. If you didn't register, you can safely ignore this email.</p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding:30px 40px;border-top:1px solid #2a2a2a;text-align:center;">
              <p style="color:#666666;font-size:14px;margin:0 0 5px;font-weight:600;">Real Estate Inventory &amp; Sales Powered by SaleFish</p>
              <p style="color:#4a4a4a;font-size:13px;margin:0;">
                <a href="https://salefish.app/privacy-policy" style="color:#4a4a4a;text-decoration:underline;">Privacy Policy</a>
                &nbsp;&middot;&nbsp;
                <a href="https://salefish.app/terms-of-use" style="color:#4a4a4a;text-decoration:underline;">Terms of Use</a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    <?php
    return ob_get_clean();
}

/**
 * Send the admin notification.
 * In preview mode, goes to andrewdb@salefish.app.
 * In production, goes to hello@salefish.app.
 */
function salefish_send_notification( array $fields, string $form_type ): bool {
    $to      = ( defined('SALEFISH_PREVIEW_MODE') && SALEFISH_PREVIEW_MODE ) ? 'andrewdb@salefish.app' : 'hello@salefish.app';
    $subject = sprintf( '[Preview] New %s on SaleFish.app', ucwords($form_type) . ' Registration' );
    if ( ! defined('SALEFISH_PREVIEW_MODE') || ! SALEFISH_PREVIEW_MODE ) {
        $subject = sprintf( 'New %s on SaleFish.app', ucwords($form_type) . ' Registration' );
    }

    $html    = salefish_notification_email_html( $fields, $form_type );
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: SaleFish <hello@salefish.app>',
    ];

    return wp_mail( $to, $subject, $html, $headers );
}

/**
 * Send the autoresponder to the registrant.
 * In preview mode, goes to andrewdb@salefish.app instead.
 */
function salefish_send_autoresponder( string $to_email, string $first_name, string $form_type ): bool {
    $to      = ( defined('SALEFISH_PREVIEW_MODE') && SALEFISH_PREVIEW_MODE ) ? 'andrewdb@salefish.app' : $to_email;
    $subject = 'We received your registration — SaleFish';
    $html    = salefish_autoresponder_email_html( $first_name, $form_type );
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: SaleFish <hello@salefish.app>',
        'Reply-To: hello@salefish.app',
    ];

    return wp_mail( $to, $subject, $html, $headers );
}
```

**Step 2: Commit**
```bash
git add wp-content/plugins/Salefish/includes/email-templates.php
git commit -m "feat: add Plinthra-styled email template functions"
```

---

## Task 3: Create the general registration handler

**Files:**
- Create: `wp-content/plugins/Salefish/ajax/mailchimp-register.php`

This handler serves the main `#reg_form` on the homepage (and AU/DE/TR variants). Fields: name, email, phone, company, title, demo.

**Step 1: Write the file**

```php
<?php
// wp-content/plugins/Salefish/ajax/mailchimp-register.php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function mailchimp_register_handler() {
    // 1. Verify nonce
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( sanitize_text_field($_POST['nonce']), 'salefish_reg_nonce' ) ) {
        wp_send_json_error( ['message' => 'Invalid request.'], 403 );
    }

    // 2. Sanitize inputs
    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $phone   = sanitize_text_field( $_POST['phone']   ?? '' );
    $company = sanitize_text_field( $_POST['company'] ?? '' );
    $title   = sanitize_text_field( $_POST['title']   ?? '' );
    $demo    = sanitize_text_field( $_POST['demo']    ?? '' );

    // 3. Basic required validation
    if ( empty($email) || ! is_email($email) ) {
        wp_send_json_error( ['message' => 'A valid email is required.'], 422 );
    }
    if ( empty($name) ) {
        wp_send_json_error( ['message' => 'Name is required.'], 422 );
    }

    // 4. Split name into first/last
    $parts      = explode( ' ', $name, 2 );
    $first_name = $parts[0];
    $last_name  = $parts[1] ?? '';

    // 5. ActiveCampaign: create/update contact
    $ac = new Salefish_ActiveCampaign();
    $contact_id = $ac->upsert_contact([
        'email'      => $email,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'phone'      => $phone,
    ]);

    if ( $contact_id ) {
        $ac->subscribe_to_list( $contact_id );
        $ac->add_tag( $contact_id, 'website-registration' );
        if ( ! empty($company) ) {
            $ac->set_field( $contact_id, 2, $company ); // Field 2 = Company Name
        }
        if ( ! empty($title) ) {
            $ac->set_field( $contact_id, 1, $title );   // Field 1 = SaleFish User Group
        }
    }

    // 6. Send emails
    $all_fields = compact('name', 'email', 'phone', 'company', 'title', 'demo');
    salefish_send_notification( $all_fields, 'general' );
    salefish_send_autoresponder( $email, $first_name, 'general' );

    wp_send_json_success( ['message' => 'Registration received.'] );
}

add_action( 'wp_ajax_mailchimp_register',        'mailchimp_register_handler' );
add_action( 'wp_ajax_nopriv_mailchimp_register', 'mailchimp_register_handler' );
```

**Step 2: Commit**
```bash
git add wp-content/plugins/Salefish/ajax/mailchimp-register.php
git commit -m "feat: add general registration AJAX handler with AC + email"
```

---

## Task 4: Create the agent registration handler

**Files:**
- Create: `wp-content/plugins/Salefish/ajax/agents-register.php`

This handler serves `#agent_form` on the Agents/Marketplace page. Fields: name, email, phone, brokerage, website_url, geo_expertise, property_expertise, howhear, see_projects, see_feature.

**Step 1: Write the file**

```php
<?php
// wp-content/plugins/Salefish/ajax/agents-register.php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function agents_register_handler() {
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( sanitize_text_field($_POST['nonce']), 'salefish_agent_nonce' ) ) {
        wp_send_json_error( ['message' => 'Invalid request.'], 403 );
    }

    $name              = sanitize_text_field( $_POST['name']               ?? '' );
    $email             = sanitize_email(      $_POST['email']              ?? '' );
    $phone             = sanitize_text_field( $_POST['phone']              ?? '' );
    $brokerage         = sanitize_text_field( $_POST['brokerage']          ?? '' );
    $website_url       = esc_url_raw(         $_POST['website_url']        ?? '' );
    $geo_expertise     = sanitize_text_field( $_POST['geo_expertise']      ?? '' );
    $property_expertise= sanitize_text_field( $_POST['property_expertise'] ?? '' );
    $howhear           = sanitize_text_field( $_POST['howhear']            ?? '' );
    $see_projects      = sanitize_text_field( $_POST['see_projects']       ?? '' );
    $see_feature       = sanitize_text_field( $_POST['see_feature']        ?? '' );

    if ( empty($email) || ! is_email($email) ) {
        wp_send_json_error( ['message' => 'A valid email is required.'], 422 );
    }
    if ( empty($name) ) {
        wp_send_json_error( ['message' => 'Name is required.'], 422 );
    }

    $parts      = explode( ' ', $name, 2 );
    $first_name = $parts[0];
    $last_name  = $parts[1] ?? '';

    $ac = new Salefish_ActiveCampaign();
    $contact_id = $ac->upsert_contact([
        'email'      => $email,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'phone'      => $phone,
    ]);

    if ( $contact_id ) {
        $ac->subscribe_to_list( $contact_id );
        $ac->add_tag( $contact_id, 'agent-registration' );
        if ( ! empty($brokerage) ) {
            $ac->set_field( $contact_id, 2, $brokerage ); // Company Name = brokerage
        }
    }

    $all_fields = compact(
        'name','email','phone','brokerage','website_url',
        'geo_expertise','property_expertise','howhear','see_projects','see_feature'
    );
    salefish_send_notification( $all_fields, 'agent' );
    salefish_send_autoresponder( $email, $first_name, 'agent' );

    wp_send_json_success( ['message' => 'Registration received.'] );
}

add_action( 'wp_ajax_agents_register',        'agents_register_handler' );
add_action( 'wp_ajax_nopriv_agents_register', 'agents_register_handler' );
```

**Step 2: Commit**
```bash
git add wp-content/plugins/Salefish/ajax/agents-register.php
git commit -m "feat: add agent registration AJAX handler with AC + email"
```

---

## Task 5: Create the partner registration handler

**Files:**
- Create: `wp-content/plugins/Salefish/ajax/partner-register.php`

This handler serves `#partner_form` on the Partners page. Fields: name, company, phone, email, want_to_do, clients.

**Step 1: Write the file**

```php
<?php
// wp-content/plugins/Salefish/ajax/partner-register.php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . '../includes/class-activecampaign.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/email-templates.php';

function partner_register_handler() {
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( sanitize_text_field($_POST['nonce']), 'salefish_partner_nonce' ) ) {
        wp_send_json_error( ['message' => 'Invalid request.'], 403 );
    }

    $name       = sanitize_text_field( $_POST['name']        ?? '' );
    $company    = sanitize_text_field( $_POST['company']     ?? '' );
    $phone      = sanitize_text_field( $_POST['phone']       ?? '' );
    $email      = sanitize_email(      $_POST['email']       ?? '' );
    $want_to_do = sanitize_text_field( $_POST['want_to_do']  ?? '' );
    $clients    = sanitize_text_field( $_POST['clients']     ?? '' );

    if ( empty($email) || ! is_email($email) ) {
        wp_send_json_error( ['message' => 'A valid email is required.'], 422 );
    }
    if ( empty($name) ) {
        wp_send_json_error( ['message' => 'Name is required.'], 422 );
    }

    $parts      = explode( ' ', $name, 2 );
    $first_name = $parts[0];
    $last_name  = $parts[1] ?? '';

    $ac = new Salefish_ActiveCampaign();
    $contact_id = $ac->upsert_contact([
        'email'      => $email,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'phone'      => $phone,
    ]);

    if ( $contact_id ) {
        $ac->subscribe_to_list( $contact_id );
        $ac->add_tag( $contact_id, 'partner-registration' );
        if ( ! empty($company) ) {
            $ac->set_field( $contact_id, 2, $company );
        }
        if ( ! empty($want_to_do) ) {
            $ac->set_field( $contact_id, 1, $want_to_do );
        }
    }

    $all_fields = compact('name','company','phone','email','want_to_do','clients');
    salefish_send_notification( $all_fields, 'partner' );
    salefish_send_autoresponder( $email, $first_name, 'partner' );

    wp_send_json_success( ['message' => 'Registration received.'] );
}

add_action( 'wp_ajax_partner_register',        'partner_register_handler' );
add_action( 'wp_ajax_nopriv_partner_register', 'partner_register_handler' );
```

**Step 2: Commit**
```bash
git add wp-content/plugins/Salefish/ajax/partner-register.php
git commit -m "feat: add partner registration AJAX handler with AC + email"
```

---

## Task 6: Fix functions.php — localize AJAX URL + nonces

**Files:**
- Modify: `wp-content/themes/salefish/functions.php`

Currently, `kickass_scripts()` enqueues the main JS bundle but never passes `ajaxurl` or nonces to the frontend. Without `ajaxurl`, all form AJAX requests fail. The JS references it as a global (`ajaxurl`) which is only defined on wp-admin pages by default.

**Step 1: Replace the `kickass_scripts` function**

Find this in `functions.php`:
```php
function kickass_scripts()
{
    wp_enqueue_style('style-name', get_template_directory_uri() . '/dest/app.css');
    wp_enqueue_script('script-name', get_template_directory_uri() . '/dest/app.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'kickass_scripts');
```

Replace with:
```php
function kickass_scripts()
{
    wp_enqueue_style('style-name', get_template_directory_uri() . '/dest/app.css');
    wp_enqueue_script('script-name', get_template_directory_uri() . '/dest/app.js', array(), '1.0.0', true);

    wp_localize_script('script-name', 'salefishAjax', [
        'ajaxurl'      => admin_url('admin-ajax.php'),
        'regNonce'     => wp_create_nonce('salefish_reg_nonce'),
        'agentNonce'   => wp_create_nonce('salefish_agent_nonce'),
        'partnerNonce' => wp_create_nonce('salefish_partner_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'kickass_scripts');
```

**Step 2: Add nonce to the load_more_post handler** (security fix from audit)

Find `add_action('wp_ajax_load_more_post', ...)` block and add a nonce check at the top of `load_more_post()`:
```php
function load_more_post()
{
    // Add this line:
    check_ajax_referer( 'load_more_nonce', 'nonce' );

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    // ... rest of function unchanged
```

Also add `loadMoreNonce` to the `wp_localize_script` call:
```php
wp_localize_script('script-name', 'salefishAjax', [
    'ajaxurl'       => admin_url('admin-ajax.php'),
    'regNonce'      => wp_create_nonce('salefish_reg_nonce'),
    'agentNonce'    => wp_create_nonce('salefish_agent_nonce'),
    'partnerNonce'  => wp_create_nonce('salefish_partner_nonce'),
    'loadMoreNonce' => wp_create_nonce('load_more_nonce'),
]);
```

**Step 3: Commit**
```bash
git add wp-content/themes/salefish/functions.php
git commit -m "fix: localize ajaxurl and nonces; add nonce check to load_more_post"
```

---

## Task 7: Update the JS to use localized AJAX URL and add agent/partner handlers

**Files:**
- Modify: `wp-content/themes/salefish/assets/js/general.js`

The JS currently uses a bare `ajaxurl` global (only works in wp-admin) and only has a submit handler for `#reg_form`. `#agent_form` and `#partner_form` have no handlers at all.

**Step 1: Update `general.js`**

Replace the REG FORM section and add AGENT + PARTNER handlers:

Find the existing `// REG FORM` block (lines ~240-265) and replace with:

```javascript
  // REG FORM
  $("#reg_form").parsley();
  $("#reg_form").on("submit", function (e) {
    e.preventDefault();
    const $btn = $(this).find('.submit');
    $btn.val('SENDING...').prop('disabled', true);

    let data = $("#reg_form").serialize();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      data: `action=mailchimp_register&nonce=${salefishAjax.regNonce}&${data}`,
      success: function (data) {
        data = JSON.parse(data);
        if (data.success) {
          $(".thank_you_msg").fadeIn();
          $("#reg_form")[0].reset();
        } else {
          alert('Something went wrong. Please try again.');
        }
        $btn.val('REGISTER').prop('disabled', false);
      },
      error: function() {
        alert('Something went wrong. Please try again.');
        $btn.val('REGISTER').prop('disabled', false);
      }
    });
  });

  // AGENT FORM
  if ($("#agent_form").length) {
    $("#agent_form").parsley();
    $("#agent_form").on("submit", function (e) {
      e.preventDefault();
      const $btn = $(this).find('.submit');
      $btn.val('SENDING...').prop('disabled', true);

      let data = $("#agent_form").serialize();
      $.ajax({
        url: salefishAjax.ajaxurl,
        type: "POST",
        data: `action=agents_register&nonce=${salefishAjax.agentNonce}&${data}`,
        success: function (data) {
          data = JSON.parse(data);
          if (data.success) {
            $(".thank_you_msg").fadeIn();
            $("#agent_form")[0].reset();
          } else {
            alert('Something went wrong. Please try again.');
          }
          $btn.val('REGISTER').prop('disabled', false);
        },
        error: function() {
          alert('Something went wrong. Please try again.');
          $btn.val('REGISTER').prop('disabled', false);
        }
      });
    });
  }

  // PARTNER FORM
  if ($("#partner_form").length) {
    $("#partner_form").parsley();
    $("#partner_form").on("submit", function (e) {
      e.preventDefault();
      const $btn = $(this).find('.submit');
      $btn.val('SENDING...').prop('disabled', true);

      let data = $("#partner_form").serialize();
      $.ajax({
        url: salefishAjax.ajaxurl,
        type: "POST",
        data: `action=partner_register&nonce=${salefishAjax.partnerNonce}&${data}`,
        success: function (data) {
          data = JSON.parse(data);
          if (data.success) {
            $(".thank_you_msg").fadeIn();
            $("#partner_form")[0].reset();
          } else {
            alert('Something went wrong. Please try again.');
          }
          $btn.val('REGISTER').prop('disabled', false);
        },
        error: function() {
          alert('Something went wrong. Please try again.');
          $btn.val('REGISTER').prop('disabled', false);
        }
      });
    });
  }

  // CLOSE THANK YOU MESSAGE
  $(".close_thank_you_msg").on("click", function () {
    $(".thank_you_msg").fadeOut();
  });
```

Also remove all `console.log` statements (lines ~8, 244, 250, 253, 255 and app.js line 10).

**Step 2: Rebuild JS assets**

From `wp-content/themes/salefish/`:
```bash
npm run production
```

Expected output: compiled `dest/app.js` with no errors.

**Step 3: Commit**
```bash
git add wp-content/themes/salefish/assets/js/general.js wp-content/themes/salefish/dest/app.js
git commit -m "fix: use localized ajaxurl and nonces; add agent/partner form handlers; remove console.log"
```

---

## Task 8: Fix HTML and grammar bugs

**Files:**
- Modify: `wp-content/themes/salefish/partials/contact-partners.php`
- Modify: `wp-content/themes/salefish/partials/contact.php`
- Modify: `wp-content/themes/salefish/partials/contact-au.php`
- Modify: `wp-content/themes/salefish/partials/contact-agents.php`

**Step 1: Fix unclosed div in contact-partners.php**

Line 59: change `</div` → `</div>`

**Step 2: Fix label capitalization**

In `contact.php` and `contact-au.php`, line ~25:
```html
<label for="demo">would you like a demo?</label>
```
→
```html
<label for="demo">Would you like a demo?</label>
```

In `contact-au.php`, lines 10-12:
```html
Upgrade your
new home and apartments sales
```
→
```html
Upgrade your new home and apartment sales
```

In `contact-agents.php`, line 70:
```html
<label for="see_feature">Features Would You Like to See</label>
```
→
```html
<label for="see_feature">Features You Would Like to See</label>
```

**Step 3: Fix phone input type in contact.php and contact-au.php**

```html
<input type="text" ... name="phone" id="phone"
```
→
```html
<input type="tel" ... name="phone" id="phone"
```

**Step 4: Commit**
```bash
git add wp-content/themes/salefish/partials/
git commit -m "fix: close unclosed div, grammar fixes, phone input type"
```

---

## Task 9: Send preview emails to andrewdb@salefish.app

This is a one-time test step to verify both email templates render correctly before going live.

**Step 1: Add credentials to wp-config.php on the server**

```php
define( 'SALEFISH_AC_KEY', '383f6863ef1a4d9f95e1e2f21c2395650306ff83b12d447e9ffaeb47a58a158996df9c41' );
define( 'SALEFISH_AC_URL', 'https://salefishapp.api-us1.com' );
define( 'SALEFISH_PREVIEW_MODE', true );
```

**Step 2: Trigger a test form submission**

Submit each of the three forms (General, Agent, Partner) with test data. Each submission will:
- Send admin notification to andrewdb@salefish.app (subject line will say `[Preview] New ... Registration`)
- Send autoresponder to andrewdb@salefish.app (instead of the test email)
- Create a test contact in ActiveCampaign (you can delete it from the AC interface afterward)

**Step 3: Approve or request changes**

Once both emails look correct in andrewdb@salefish.app, proceed to Task 10.

---

## Task 10: Go live

Once emails are approved:

**Step 1: Update wp-config.php**
```php
define( 'SALEFISH_PREVIEW_MODE', false );
```

**Step 2: Set up the ActiveCampaign autoresponder automation**

The autoresponder email is currently sent via `wp_mail()`. For it to be "sent from ActiveCampaign" infrastructure in production, set up an automation in AC:

1. Log in to ActiveCampaign → Automations → Create New Automation
2. Trigger: **"Subscribes to list"** → "Website Registrations"
3. Action: **"Send an Email"** → paste the autoresponder HTML from `salefish_autoresponder_email_html()` (or grab the rendered HTML from the preview email)
4. Subject: `We received your registration — SaleFish`
5. From: `hello@salefish.app` / `SaleFish`

Once the automation is created:
- Note the Automation ID (visible in the URL when editing it)
- Optionally: add `$ac->trigger_automation($contact_id, $automation_id)` call to the handlers in place of `salefish_send_autoresponder()` — this makes AC send the email instead of wp_mail

For the admin notification email, the same approach applies: create an AC automation with a "Send Notification Email" action, or leave it as-is via wp_mail (both deliver to hello@salefish.app).

**Step 3: Test with a real submission**

Submit a form with a real email address. Confirm:
- Contact appears in AC under "Website Registrations" list with correct tag
- hello@salefish.app receives the admin notification
- The registrant's email receives the autoresponder

**Step 4: Remove dead code**

```bash
rm -rf wp-content/themes/salefish/mailchimp-api-master/
rm -rf wp-content/themes/salefish/vendor/sendgrid/
git add -A
git commit -m "chore: remove unused Mailchimp and SendGrid vendor libraries"
```

---

## Summary of all files touched

| Action | File |
|--------|------|
| Create | `wp-content/plugins/Salefish/includes/class-activecampaign.php` |
| Create | `wp-content/plugins/Salefish/includes/email-templates.php` |
| Create | `wp-content/plugins/Salefish/ajax/mailchimp-register.php` |
| Create | `wp-content/plugins/Salefish/ajax/agents-register.php` |
| Create | `wp-content/plugins/Salefish/ajax/partner-register.php` |
| Modify | `wp-content/themes/salefish/functions.php` |
| Modify | `wp-content/themes/salefish/assets/js/general.js` |
| Rebuild | `wp-content/themes/salefish/dest/app.js` |
| Modify | `wp-content/themes/salefish/partials/contact-partners.php` |
| Modify | `wp-content/themes/salefish/partials/contact.php` |
| Modify | `wp-content/themes/salefish/partials/contact-au.php` |
| Modify | `wp-content/themes/salefish/partials/contact-agents.php` |
| Server | `wp-config.php` (add 3 constants — done on server, not in git) |
| Delete | `wp-content/themes/salefish/mailchimp-api-master/` (Task 10) |
| Delete | `wp-content/themes/salefish/vendor/sendgrid/` (Task 10) |

## AC data model per registration

| Form | Tag | Company Name field | User Group field |
|------|-----|--------------------|-----------------|
| General | `website-registration` | company | title |
| Agent | `agent-registration` | brokerage | — |
| Partner | `partner-registration` | company | want_to_do |

All three forms subscribe to List ID **4** (Website Registrations).

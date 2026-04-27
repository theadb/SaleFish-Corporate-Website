# Partners Logo Strip Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Replace the constrained logo card in the Partners page 2-column grid with a prominent full-width logo strip, and restructure the three partner-type cards into a clean 3-column layout.

**Architecture:** Two files change — the PHP template (HTML structure) and the SCSS page file (styles). The `.item_img` card is deleted from the `.items` grid; a new `.logo-strip` block is inserted between the section header and `.items`. The `.items` grid is updated from an implicit 2-column/4-child layout to an explicit 3-column/3-child layout.

**Tech Stack:** PHP (WordPress template), SCSS compiled via Laravel Mix (`npm run prod` from theme root), deployed via lftp FTP + git push.

---

### Task 1: Update page-partners.php

**Files:**
- Modify: `wp-content/themes/salefish/page-partners.php`

The `.agents` section currently contains `.container > .header + .items`. `.items` has four children: three `.item` cards and one `.item_img` logo card.

**Step 1: Remove the `.item_img` block and add `.logo-strip` before `.items`**

Replace the entire `.agents` section inner structure so it reads:

```php
<section class="agents">
    <div class="container">
        <div class="header" data-aos="fade-up" data-aos-delay="300">
            <h1>Got Code, Connections or Clients?
                Let's Turn That Into Revenue.</h1>
            <p>Whether you build apps, resell software, advise developers, or just know people who need better
                tools—SaleFish gives you a way to profit.</p>
            <p>Integrate it. Recommend it. White-label it. Sell it.</p>
            <p>We'll handle the heavy lifting. You keep the upside.</p>
            <p>Let's change how new real estate gets sold—and make you a key part of it.</p>
        </div>

        <!-- LOGO STRIP -->
        <div class="logo-strip" data-aos="fade-up">
            <p class="logo-strip__label">Tools You Already Trust. Now Working Together.</p>
            <div class="logo-strip__grid">
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_1.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_2.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_3.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_4.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_5.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
                <div class="logo-strip__col">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/partners/logo_6.png" loading="lazy" decoding="async" alt="" aria-hidden="true">
                </div>
            </div>
        </div>
        <!-- END LOGO STRIP -->

        <div class="items">
            <div class="item" data-aos="fade-up">
                <div class="info">
                    <h4>Plug In. Level Up.</h4>
                    <h3>Technology Partners</h3>
                    <p>Got a killer app, platform, or product? Integrate with SaleFish
                        and become part of the new home sales tech stack. We'll
                        help you slot in, sync up, and scale faster.
                    </p>
                    <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Integrate my app/tool" data-sf-section="Partners — Technology Card">Integrate & Dominate</a>
                </div>
                <div class="content_img">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/img/partners/crm.png"
                        alt="CRM integration dashboard screenshot">
                </div>
            </div>
            <div class="item" data-aos="fade-up" data-aos-delay="100">
                <div class="info">
                    <h4>Know People? Get Paid.</h4>
                    <h3>Referral Partners</h3>
                    <p>You've got the network—builders, brokers, developers. We've
                        got the software that actually works. Send them our way. We'll
                        demo. We'll close. You'll get cash every time we book a meeting.
                    </p>
                    <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Refer builders, brokers, or developers" data-sf-section="Partners — Referral Card">Start Cashing In</a>
                </div>
                <div class="content_img">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/img/partners/notify.png"
                        alt="SaleFish referral notification illustration">
                </div>
            </div>
            <div class="item" data-aos="fade-up" data-aos-delay="200">
                <div class="info">
                    <h4>Sell the Platform That Sells.</h4>
                    <h3>Reseller Partners</h3>
                    <p>Offer SaleFish to your clients and unlock serious recurring revenue.
                        Earn commission while helping sales teams move more real estate,
                        more easily. Everyone wins. Especially you.
                    </p>
                    <a class="button" href="javascript:void(0)" data-sf-modal="partner" data-sf-partner-type="Resell the SaleFish platform" data-sf-section="Partners — Reseller Card">You Had Me at Commission</a>
                </div>
                <div class="content_img">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/img/partners/bag.png"
                        alt="SaleFish reseller partner illustration">
                </div>
            </div>
        </div>
    </div>
</section>
```

Note the staggered AOS delays (0, 100ms, 200ms) on the three cards for a polished cascade entrance.

**Step 2: Verify the PHP file looks correct**

Open `page-partners.php` and confirm:
- No `.item_img` block remains in `.items`
- `.logo-strip` exists between `.header` and `.items`
- `.items` has exactly 3 `.item` children

**Step 3: Commit**

```bash
cd "/Users/andrewdavidblair/Library/Mobile Documents/com~apple~CloudDocs/Work in Progress/SaleFish Marketing Website"
git add wp-content/themes/salefish/page-partners.php
git commit -m "refactor(partners): restructure agents section with full-width logo strip and 3-col card grid"
```

---

### Task 2: Update partners.scss

**Files:**
- Modify: `wp-content/themes/salefish/assets/scss/pages/partners.scss`

**Step 1: Replace `.item_img` block with `.logo-strip` styles and fix `.items` grid**

Inside `.partners .agents`, make these two changes:

**A) Replace `.item_img` styles with `.logo-strip`:**

Remove the entire `.item_img { … }` block and add in its place:

```scss
.logo-strip {
  background: $gray;
  border-radius: var(--sf-radius-lg);
  padding: 52px 64px;
  margin-bottom: 48px;

  @include rs990 { padding: 44px 48px; }
  @include rs768 { padding: 36px 32px; margin-bottom: 40px; }
  @include rs550 { padding: 32px 24px; }

  &__label {
    font-family: $PSB;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: $dark_purple;
    text-align: center;
    margin-bottom: 40px;

    @include rs768 { margin-bottom: 28px; }
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0 40px;
    align-items: center;
    justify-items: center;

    @include rs990 { grid-template-columns: repeat(3, 1fr); gap: 32px 32px; }
    @include rs550 { grid-template-columns: repeat(2, 1fr); gap: 28px 24px; }
  }

  &__col {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;

    img {
      width: 100%;
      height: auto;
      max-height: 52px;
      object-fit: contain;
      display: block;
      opacity: 0.72;
      transition: opacity 0.25s ease, transform 0.25s ease;
      will-change: transform, opacity;

      @include rs990 { max-height: 48px; }
      @include rs550 { max-height: 44px; }
    }

    &:hover img {
      opacity: 1;
      transform: translateY(-3px);
    }
  }
}
```

**B) Update `.items` grid to 3 columns:**

Change the `.items` rule from:
```scss
grid-template-columns: 1fr 1fr;
```
to:
```scss
grid-template-columns: repeat(3, 1fr);
```

And update breakpoints so the 3 cards collapse gracefully:
```scss
.items {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
  align-items: stretch;

  @include rs990 { grid-template-columns: repeat(2, 1fr); gap: 24px; }
  @include rs768 { grid-template-columns: 1fr; gap: 28px; }
}
```

At the rs990 breakpoint with 3 cards in a 2-column grid, the third card will sit alone in the left cell. That's acceptable — if you want it centred, add `& > .item:last-child { grid-column: 1 / -1; max-width: 480px; margin: 0 auto; }` inside `.items @include rs990`.

**Step 2: Verify**

Confirm `partners.scss` has:
- No `.item_img` block
- `.logo-strip` block with `&__label`, `&__grid`, `&__col`
- `.items` with `grid-template-columns: repeat(3, 1fr)`

**Step 3: Commit**

```bash
git add wp-content/themes/salefish/assets/scss/pages/partners.scss
git commit -m "style(partners): add logo-strip styles, update agents grid to 3 columns"
```

---

### Task 3: Build and verify locally

**Step 1: Run the production build**

```bash
cd "/Users/andrewdavidblair/Library/Mobile Documents/com~apple~CloudDocs/Work in Progress/SaleFish Marketing Website/wp-content/themes/salefish"
npm run prod
```

Expected: build completes with no errors. `dest/app.css` timestamp updates.

**Step 2: Check the local site**

Open `https://salefish.local/partners/` (or whatever the local WP URL is) in a browser. Verify:
- Logo strip appears between the section header and the three cards
- 6 logos display in a single row on desktop
- Logos collapse to 3-up at tablet, 2-up at mobile
- Hover effect: logos brighten and lift
- 3 partner cards sit side-by-side on desktop, 2+1 at tablet, stacked on mobile
- AOS animations cascade in correctly

---

### Task 4: Deploy all three locations

**Step 1: Push to GitHub**

```bash
cd "/Users/andrewdavidblair/Library/Mobile Documents/com~apple~CloudDocs/Work in Progress/SaleFish Marketing Website"
git push origin main
```

**Step 2: Deploy changed files to production via FTP**

```bash
FTP_PASS=$(security find-generic-password -s "ftp-salefish" -w)

# Copy files with spaces in path to /tmp first
cp "/Users/andrewdavidblair/Library/Mobile Documents/com~apple~CloudDocs/Work in Progress/SaleFish Marketing Website/wp-content/themes/salefish/page-partners.php" /tmp/page-partners.php
cp "/Users/andrewdavidblair/Library/Mobile Documents/com~apple~CloudDocs/Work in Progress/SaleFish Marketing Website/wp-content/themes/salefish/dest/app.css" /tmp/app.css

lftp -u appsfish,"$FTP_PASS" ftp://salefish.app -e "
set ssl:verify-certificate no
put /tmp/page-partners.php -o public_html/wp-content/themes/salefish/page-partners.php
put /tmp/app.css -o public_html/wp-content/themes/salefish/dest/app.css
bye
"
```

**Step 3: Clear WP Super Cache for the partners page**

```bash
FTP_PASS=$(security find-generic-password -s "ftp-salefish" -w)
lftp -u appsfish,"$FTP_PASS" ftp://salefish.app -e "
set ssl:verify-certificate no
cd public_html/wp-content/cache/supercache/salefish.app
rm partners/index-https.html
rmdir partners
bye
" 2>&1
```

(Errors here just mean the cache file didn't exist — that's fine.)

**Step 4: Verify live**

```bash
curl -s "https://salefish.app/partners/" | grep -o "logo-strip"
```

Expected: `logo-strip` appears in the output confirming the new markup is live.

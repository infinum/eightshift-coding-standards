# Bump Minimum PHP to 8.3 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Raise the minimum supported PHP version of `infinum/eightshift-coding-standards` from 7.4 to 8.3 and ship as 4.0.0.

**Architecture:** Config-only change. We bump the Composer `php` constraint, the PHPCompatibility `testVersion` marker, shrink the CI matrix to `[8.3, 8.4]`, simplify the version-conditional workflow steps now made redundant, update README badge, and write a CHANGELOG entry. Bundled with this: closes Dependabot alert #3 by pinning `shivammathur/setup-php` to `v2.37.1`.

**Tech Stack:** Composer, PHP_CodeSniffer (PHPCS), PHPCompatibilityWP, GitHub Actions, PHPUnit 8 (kept as-is for this plan).

**Out of scope (separate plans):**

- PHP 8 syntax modernization of sniff code → `2026-05-25-modernize-sniffs-to-php-8.md`
- PHPUnit upgrade → `2026-05-25-bump-phpunit.md`

**Branching:** Work on `feat/min-php-8.3` off `main`.

---

### Task 1: Bump Composer PHP constraint

**Files:**

- Modify: `composer.json:25`

- [ ] **Step 1: Edit `composer.json`**

Change line 25 from:

```json
"php": ">=7.4",
```

to:

```json
"php": ">=8.3",
```

- [ ] **Step 2: Regenerate the lock file**

Run: `composer update --lock`
Expected: `composer.lock` updated, `platform.php` reference (if present) reflects new constraint, no dependency resolution errors.

- [ ] **Step 3: Validate `composer.json`**

Run: `composer validate --no-check-all --strict`
Expected: `./composer.json is valid`

- [ ] **Step 4: Commit**

```bash
git add composer.json composer.lock
git commit -m "build: require PHP >= 8.3"
```

---

### Task 2: Bump `testVersion` in shipped ruleset

**Files:**

- Modify: `Eightshift/ruleset.xml:8`

- [ ] **Step 1: Edit `Eightshift/ruleset.xml`**

Change line 8 from:

```xml
<config name="testVersion" value="7.4-"/>
```

to:

```xml
<config name="testVersion" value="8.3-"/>
```

- [ ] **Step 2: Validate the ruleset XML**

Run:

```bash
xmllint --noout --schema vendor/squizlabs/php_codesniffer/phpcs.xsd ./Eightshift/ruleset.xml
```

Expected: `./Eightshift/ruleset.xml validates`

- [ ] **Step 3: Run the ruleset against the in-repo dogfooding suite**

Run: `composer tests:checkcs`
Expected: Either passes cleanly, or surfaces PHPCompatibilityWP findings (new 8.3 deprecations) only in repo source — note any findings to triage in Task 6.

- [ ] **Step 4: Commit**

```bash
git add Eightshift/ruleset.xml
git commit -m "config(ruleset): bump PHPCompatibility testVersion to 8.3-"
```

---

### Task 3: Update sample ruleset `testVersion`

**Files:**

- Modify: `phpcs.xml.dist.sample:28`

> **Why:** The sample is copied verbatim by downstream projects via `cp vendor/.../phpcs.xml.dist.sample phpcs.xml.dist` (README line 39). Leaving it at `7.1-` after we drop 7.4 support is misleading.

- [ ] **Step 1: Edit `phpcs.xml.dist.sample`**

Change line 28 from:

```xml
<config name="testVersion" value="7.1-"/>
```

to:

```xml
<config name="testVersion" value="8.3-"/>
```

- [ ] **Step 2: Validate the sample**

Run:

```bash
xmllint --noout --schema vendor/squizlabs/php_codesniffer/phpcs.xsd ./phpcs.xml.dist.sample
```

Expected: `./phpcs.xml.dist.sample validates`

- [ ] **Step 3: Commit**

```bash
git add phpcs.xml.dist.sample
git commit -m "config(sample): bump PHPCompatibility testVersion to 8.3-"
```

---

### Task 4: Rewrite CI matrix and simplify version-conditional steps

**Files:**

- Modify: `.github/workflows/ci.yml` (multiple line ranges — see steps below)

> **Bundled here:** Dependabot alert #3 (GHSA-5wxr-w449-57cm) is closed by pinning `shivammathur/setup-php@v2` → `@v2.37.1`. Six occurrences updated alongside the matrix work.

- [ ] **Step 1: Pin `shivammathur/setup-php` to `v2.37.1`**

In `.github/workflows/ci.yml`, replace all six occurrences of:

```yaml
uses: shivammathur/setup-php@v2
```

with:

```yaml
uses: shivammathur/setup-php@v2.37.1
```

Lines: 27, 48, 98, 126, 156, 214.

- [ ] **Step 2: Bump fixed `php-version: "7.4"` jobs to `"8.3"`**

Four jobs run a single fixed PHP version: `composer_validate`, `ruleset_validate`, `feature_completeness`, `phpstan`. Replace each:

```yaml
php-version: "7.4"
```

with:

```yaml
php-version: "8.3"
```

Lines: 29, 50, 100, 158.

- [ ] **Step 3: Shrink `lint` matrix**

Replace `.github/workflows/ci.yml:122`:

```yaml
php: ["7.4", "8.0", "8.1", "8.2"]
```

with:

```yaml
php: ["8.3", "8.4"]
```

- [ ] **Step 4: Rewrite `tests` matrix and remove dead branches**

Replace `.github/workflows/ci.yml:182-198`:

```yaml
strategy:
  fail-fast: false
  matrix:
    php: ["7.4", "8.0", "8.1", "8.2"]
    phpcs_branch: ["lowest", "dev-master"]
    wpcs_branch: ["3.0.0", "dev-develop"]
    allowed_failure: [false]
    exclude:
      # Only run low WordPressCS in combination with low PHPCS and high WordPressCS with high PHPCS.
      - phpcs_branch: "3.7.2"
        wpcs_branch: "3.0.0"
      - phpcs_branch: "dev-master"
        wpcs_branch: "dev-develop"
    # Allow failure on non-released version of PHP.
    include:
      - php: "8.3"
        phpcs_branch: "dev-master"
        wpcs_branch: "dev-develop"
        allowed_failure: true
```

with:

```yaml
strategy:
  fail-fast: false
  matrix:
    php: ["8.3", "8.4"]
    phpcs_branch: ["lowest", "dev-master"]
    wpcs_branch: ["3.0.0", "dev-develop"]
    allowed_failure: [false]
    exclude:
      # Only run low WordPressCS in combination with low PHPCS and high WordPressCS with high PHPCS.
      - phpcs_branch: "lowest"
        wpcs_branch: "dev-develop"
      - phpcs_branch: "dev-master"
        wpcs_branch: "3.0.0"
```

> **Note:** The original `exclude` referenced `phpcs_branch: '3.7.2'` which is not a value present in the matrix (`lowest` and `dev-master` are). That was a latent bug — the rewrite uses the actual matrix values. The `include:` block that promoted PHP 8.3 to an allow-fail run is removed because 8.3 is now a baseline.

- [ ] **Step 5: Drop the PHP-version-conditional install steps**

In the `tests` job, replace `.github/workflows/ci.yml:227-238`:

```yaml
- name: "Install Composer dependencies (PHP < 8.0 )"
  if: ${{ matrix.php < 8.0 }}
  uses: ramsey/composer-install@v2
  with:
    custom-cache-suffix: $(date -u "+%Y-%m")

- name: "Install Composer dependencies (PHP >= 8.0)"
  if: ${{ matrix.php >= 8.0 }}
  uses: ramsey/composer-install@v2
  with:
    composer-options: --ignore-platform-req=php+
    custom-cache-suffix: $(date -u "+%Y-%m")
```

with:

```yaml
- name: "Install Composer dependencies"
  uses: ramsey/composer-install@v2
  with:
    composer-options: --ignore-platform-req=php+
    custom-cache-suffix: $(date -u "+%Y-%m")
```

> **Why keep `--ignore-platform-req=php+`:** It lets us install when transitive deps haven't yet declared support for the newest PHP in the matrix (e.g. 8.4 going forward). Harmless on 8.3.

- [ ] **Step 6: Collapse the PHP-version-conditional test runner steps**

Replace `.github/workflows/ci.yml:264-270`:

```yaml
- name: "Run the unit tests - PHP 7.4 - 8.0"
  if: ${{ matrix.php < '8.1' }}
  run: composer tests:run

- name: "Run the unit tests - PHP >= 8.1"
  if: ${{ matrix.php >= '8.1' }}
  run: composer tests:run -- --no-configuration --bootstrap=./Tests/bootstrap.php --dont-report-useless-tests
```

with:

```yaml
- name: "Run the unit tests"
  run: composer tests:run -- --no-configuration --bootstrap=./Tests/bootstrap.php --dont-report-useless-tests
```

- [ ] **Step 7: Verify YAML lints**

Run:

```bash
yq eval '.jobs | keys' .github/workflows/ci.yml
```

Expected: List shows `composer_validate, ruleset_validate, feature_completeness, lint, phpstan, tests` — no parse errors.

- [ ] **Step 8: Commit**

```bash
git add .github/workflows/ci.yml
git commit -m "ci: target PHP 8.3 and 8.4, pin setup-php to v2.37.1

Drops PHP 7.4-8.2 from the matrix, collapses now-redundant
version-conditional steps, and addresses Dependabot alert
GHSA-5wxr-w449-57cm by pinning setup-php to a patched release."
```

---

### Task 5: Update README badge

**Files:**

- Modify: `README.md:9`

- [ ] **Step 1: Edit `README.md`**

Replace line 9:

```markdown
[![Tested on PHP 7.4 to 8.3](https://img.shields.io/badge/tested%20on-%207.4%20|%208.0%20|%208.1%20|%208.2%20|%208.3-green.svg?maxAge=2419200)](https://github.com/infinum/eightshift-coding-standards/actions/workflows/ci.yml)
```

with:

```markdown
[![Tested on PHP 8.3 to 8.4](https://img.shields.io/badge/tested%20on-%208.3%20|%208.4-green.svg?maxAge=2419200)](https://github.com/infinum/eightshift-coding-standards/actions/workflows/ci.yml)
```

- [ ] **Step 2: Update the install example in `README.md`**

Find the block at `README.md:30-34`:

```json
"require-dev": {
  "infinum/eightshift-coding-standards": "^2.0"
}
```

Replace with:

```json
"require-dev": {
  "infinum/eightshift-coding-standards": "^4.0"
}
```

- [ ] **Step 3: Commit**

```bash
git add README.md
git commit -m "docs(readme): reflect PHP 8.3+ requirement and 4.0 install hint"
```

---

### Task 6: Write CHANGELOG entry for 4.0.0

**Files:**

- Modify: `CHANGELOG.md:9-11` (replace `[Unreleased]` placeholder block)

- [ ] **Step 1: Edit `CHANGELOG.md`**

Replace:

```markdown
## [Unreleased]

_No documentation available about unreleased changes yet._
```

with:

```markdown
## [Unreleased]

_No documentation available about unreleased changes yet._

## [4.0.0](https://github.com/infinum/eightshift-coding-standards/compare/3.1.0...4.0.0)

### Changed

- **BREAKING:** Raised the minimum supported PHP version to 8.3. Consumers on PHP < 8.3 must stay on the 3.x line.
- CI test matrix narrowed to PHP 8.3 and 8.4 (latest stable).
- PHPCompatibility `testVersion` in `Eightshift/ruleset.xml` raised to `8.3-`.
- Sample ruleset `phpcs.xml.dist.sample` `testVersion` raised to `8.3-` to match the new minimum.
- Pinned `shivammathur/setup-php` GitHub Action to `v2.37.1` to address Dependabot alert [GHSA-5wxr-w449-57cm](https://github.com/advisories/GHSA-5wxr-w449-57cm).
```

> **Note:** Append to the "Changed" list any PHPCompatibilityWP findings surfaced in Task 2 Step 3 that required fixes in repo source.

- [ ] **Step 2: Commit**

```bash
git add CHANGELOG.md
git commit -m "docs(changelog): document 4.0.0 PHP 8.3 minimum bump"
```

---

### Task 7: Full local verification

- [ ] **Step 1: Fresh dependency install**

Run:

```bash
rm -rf vendor
composer install
```

Expected: install completes; no platform-requirement errors on a local PHP 8.3+ runtime. If running on PHP < 8.3 locally, add `--ignore-platform-req=php` once to verify the install graph.

- [ ] **Step 2: Validate composer**

Run: `composer validate --no-check-all --strict`
Expected: `./composer.json is valid`

- [ ] **Step 3: Validate all XML rulesets**

Run:

```bash
xmllint --noout --schema vendor/squizlabs/php_codesniffer/phpcs.xsd ./Eightshift/ruleset.xml
xmllint --noout --schema vendor/squizlabs/php_codesniffer/phpcs.xsd ./phpcs.xml.dist.sample
xmllint --noout --schema vendor/phpcsstandards/phpcsdevtools/DocsXsd/phpcsdocs.xsd ./Eightshift/Docs/*/*Standard.xml
```

Expected: every file reports `validates`.

- [ ] **Step 4: Lint PHP**

Run: `composer lint`
Expected: `No syntax error found`.

- [ ] **Step 5: Run PHPStan**

Run: `composer exec -- phpstan analyse`
Expected: clean run, or only pre-existing baseline errors.

- [ ] **Step 6: Run sniff feature completeness**

Run: `composer check:complete`
Expected: no missing docs/tests.

- [ ] **Step 7: Run ruleset self-check**

Run: `composer tests:checkcs`
Expected: clean. Triage any new PHPCompatibility findings into Task 2's CHANGELOG note.

- [ ] **Step 8: Run unit tests**

Run: `composer tests:run -- --no-configuration --bootstrap=./Tests/bootstrap.php --dont-report-useless-tests`
Expected: all tests pass.

---

### Task 8: Push and open PR

- [ ] **Step 1: Push the branch**

```bash
git push -u origin feat/min-php-8.3
```

- [ ] **Step 2: Open PR**

Run:

```bash
gh pr create --title "feat!: require PHP >= 8.3 (4.0.0)" --body "$(cat <<'EOF'
## Summary
- Bumps minimum PHP from 7.4 to 8.3 (breaking → 4.0.0).
- CI matrix narrows to PHP 8.3 and 8.4; removes now-dead version-conditional steps.
- Closes Dependabot alert GHSA-5wxr-w449-57cm by pinning `shivammathur/setup-php` to `v2.37.1`.
- Updates `testVersion` in shipped ruleset and the sample to `8.3-`.
- Updates README badge and CHANGELOG.

## Test plan
- [ ] CI green on PHP 8.3 and 8.4 across all jobs.
- [ ] `composer validate --strict` passes locally.
- [ ] `composer tests:checkcs` clean.
- [ ] `composer tests:run` clean on PHP 8.3 and 8.4.
- [ ] Dependabot alert #3 auto-resolves after merge.
EOF
)"
```

- [ ] **Step 3: Verify CI runs**

Run: `gh pr checks --watch`
Expected: every job green on 8.3 and 8.4. Fix and recommit per job until all pass.

---

## Self-review checklist (executed against this plan)

- [x] **Spec coverage:** composer constraint (Task 1), shipped ruleset (Task 2), sample ruleset (Task 3), CI matrix + setup-php pin + conditional cleanup (Task 4), README (Task 5), CHANGELOG with 4.0.0 (Task 6), local verification (Task 7), PR (Task 8). Dependabot alert #3 bundled per user's earlier ask.
- [x] **Placeholder scan:** No TBDs. Every edit shows the exact before/after content.
- [x] **Type consistency:** All file paths, line numbers, and command strings cross-checked against the working copy.

# Bump PHPUnit to ^11.x Implementation Plan

> **Status:** Gated. Do NOT start until both `2026-05-25-bump-min-php-to-8.3.md` and `2026-05-25-modernize-sniffs-to-php-8.md` are merged.

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans.

**Goal:** Move `phpunit/phpunit` from `^8.5.52` to `^11.x` now that the project requires PHP 8.3.

**Architecture:** PHPCS ships its own test runner (`./vendor/squizlabs/php_codesniffer/tests/AllTests.php`) which our `tests:run` script invokes. The PHPUnit version mostly affects assertion API and deprecation behaviour. The upgrade hops PHPUnit 8 → 11 across three breaking releases (9, 10, 11) — we land it as a single bump because we don't author legacy assertion calls.

**Tech Stack:** PHP 8.3+, PHPUnit 11, PHPCSStandards/PHP_CodeSniffer test runner.

**Branching:** `feat/phpunit-11` off `main` after the modernization plan merges.

**Key compatibility risks:**

1. PHPCS's own test harness must support PHPUnit 11. Verify before starting.
2. PHPUnit 10 removed `@dataProvider` annotation in favour of `#[DataProvider]` attribute (annotation still works in 11 but is deprecated). Audit `Tests/` for fixture-style providers.
3. `phpunit.xml.dist` schema location must move from `6.3` to `11.x`.
4. PHPUnit 10+ removed `--whitelist` and tightened `<coverage>` config — check `phpunit.xml.dist`.

---

### Task 1: Compatibility probe

- [ ] **Step 1: Check PHPCS test runner support for PHPUnit 11**

Run:

```bash
composer show squizlabs/php_codesniffer
cat vendor/squizlabs/php_codesniffer/composer.json | jq '.require, .["require-dev"]'
```

Expected: confirm the installed PHPCS version's `require-dev` allows PHPUnit 11. If it caps at PHPUnit 10, target `^10.5` instead and document the choice in the CHANGELOG entry.

- [ ] **Step 2: Audit `Tests/` for legacy PHPUnit API usage**

Run:

```bash
rg -n '@dataProvider|setUp\(\): void|tearDown\(\): void|expectException\(|assertEquals\(' Tests/
```

Expected: a list of every test method touching API that changed across PHPUnit 9-11. Save to `docs/superpowers/plans/_artifacts/2026-05-25-phpunit-audit.txt`.

- [ ] **Step 3: Commit probe artifact**

```bash
git add docs/superpowers/plans/_artifacts/2026-05-25-phpunit-audit.txt
git commit -m "chore: probe PHPUnit 11 compatibility surface"
```

---

### Task 2: Bump the dependency

**Files:**

- Modify: `composer.json:31`

- [ ] **Step 1: Edit `composer.json`**

Replace:

```json
"phpunit/phpunit": "^8.5.52",
```

with the target chosen in Task 1 — `^11.0` if PHPCS supports it, else `^10.5`. Example:

```json
"phpunit/phpunit": "^11.0",
```

- [ ] **Step 2: Update composer**

Run: `composer update phpunit/phpunit --with-all-dependencies`
Expected: clean resolution. If conflicts surface (e.g. PHPCS test runner pins PHPUnit), fall back to the lower major and update the CHANGELOG entry accordingly.

- [ ] **Step 3: Commit**

```bash
git add composer.json composer.lock
git commit -m "build(dev): bump phpunit to ^11.0"
```

---

### Task 3: Update `phpunit.xml.dist`

**Files:**

- Modify: `phpunit.xml.dist:4`

- [ ] **Step 1: Update schema location**

Replace:

```xml
xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/6.3/phpunit.xsd"
```

with the schema URL matching the installed major. For PHPUnit 11:

```xml
xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/11.0/phpunit.xsd"
```

- [ ] **Step 2: Run a migration check**

Run:

```bash
./vendor/bin/phpunit --migrate-configuration
```

Expected: either "no migration needed" or a backup file `.phpunit.xml.dist.bak`. Inspect the diff and accept it.

- [ ] **Step 3: Validate the file**

Run: `xmllint --noout phpunit.xml.dist`
Expected: parses with no errors.

- [ ] **Step 4: Commit**

```bash
git add phpunit.xml.dist
git commit -m "config(phpunit): bump schema to PHPUnit 11"
```

---

### Task 4: Fix audit findings

For each file flagged in Task 1's audit artifact, make the minimal API update:

| Legacy                                                | Modern                                                                   |
| ----------------------------------------------------- | ------------------------------------------------------------------------ |
| `/** @dataProvider providerFoo */`                    | `#[DataProvider('providerFoo')]` (PHP 8 attribute)                       |
| `assertEquals($a, $b)` on floats                      | `assertEqualsWithDelta(...)`                                             |
| `expectException(\Throwable::class)` followed by code | unchanged in 11, but verify message-based `expectExceptionMessage` works |

One commit per logical group.

- [ ] **Step 1..N:** Apply each fix, run `composer tests:run`, commit.

---

### Task 5: Verify & ship

- [ ] **Step 1: Local test run**

Run: `composer tests:run -- --no-configuration --bootstrap=./Tests/bootstrap.php --dont-report-useless-tests`
Expected: all tests pass on PHP 8.3 and 8.4.

- [ ] **Step 2: CHANGELOG entry** (new `## [4.2.0]`):

```markdown
### Changed

- Dev-dependency `phpunit/phpunit` bumped from `^8.5` to `^11.0`. No runtime impact for consumers — affects test authoring only.
```

- [ ] **Step 3: Open PR titled `build: bump PHPUnit to ^11.0 (4.2.0)`.**

---

## Self-review notes

- The plan deliberately routes through Task 1 (compatibility probe) before any code change, because PHPUnit 11 support depends on what PHPCS's own test harness allows. Failing fast there avoids wasted refactors.
- If Task 1 forces a fallback to `^10.5`, all later tasks still apply — only the schema URL and target constraint change.

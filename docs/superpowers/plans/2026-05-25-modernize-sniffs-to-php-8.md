# Modernize Sniff Code to PHP 8 Implementation Plan

> **Status:** Gated. Do NOT start until `2026-05-25-bump-min-php-to-8.3.md` is merged and 4.0.0 is released.

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task.

**Goal:** Rewrite the Eightshift sniff sources to use PHP 8.x features now that the minimum is 8.3.

**Architecture:** File-by-file modernization. Each sniff is reviewed in isolation. Apply the smallest PHP 8 idiom that genuinely improves readability or type safety — do not refactor for its own sake. Run the full test + sniff suite after every file.

**Tech Stack:** PHP 8.3, PHP_CodeSniffer 3.x sniff API, PHPUnit 8 (until follow-up plan upgrades it).

**Branching:** `feat/php-8-modernize` off `main` after 4.0.0 is tagged.

**Constraint:** This package's source is itself an example of "good" Eightshift code. Optimize for clarity over cleverness — readers will copy these patterns.

---

## Target features (apply only where they help)

| Feature                                              | PHP                   | Use when                                                      |
| ---------------------------------------------------- | --------------------- | ------------------------------------------------------------- |
| Constructor property promotion                       | 8.0                   | Constructor only assigns params to props.                     |
| `readonly` properties                                | 8.1                   | Property set once in constructor, never mutated.              |
| Typed properties                                     | 7.4 (already allowed) | Any untyped property where the type is obvious.               |
| `match` expression                                   | 8.0                   | `switch` blocks that only return/assign.                      |
| Named arguments                                      | 8.0                   | Calls with many positional bools/strings.                     |
| First-class callable syntax `foo(...)`               | 8.1                   | Wherever `[$this, 'foo']` or `Closure::fromCallable` is used. |
| `never` return type                                  | 8.1                   | Methods that always throw.                                    |
| `new` in initializers                                | 8.1                   | Default param values that need an object.                     |
| Enums                                                | 8.1                   | String-keyed constant groups used as a closed set.            |
| Nullsafe `?->`                                       | 8.0                   | Chained nullable accesses.                                    |
| `str_contains` / `str_starts_with` / `str_ends_with` | 8.0                   | Existing `strpos !== false` checks.                           |

---

### Task 1: Inventory sniff source files

**Files:**

- Read-only: `Eightshift/Sniffs/**/*.php`, `Eightshift/Helpers/*.php` (if any)

- [ ] **Step 1: List sniff files**

Run: `fd -e php . Eightshift/Sniffs Eightshift/Helpers 2>/dev/null`
Expected: a complete inventory. Write it into `docs/superpowers/plans/_artifacts/2026-05-25-sniff-inventory.txt`.

- [ ] **Step 2: For each file, jot a one-line modernization candidate**

Format: `path/to/Sniff.php: typed props, match in process(), str_contains x2`
Save to the same artifact file. This drives the per-file tasks below.

- [ ] **Step 3: Commit the inventory**

```bash
git add docs/superpowers/plans/_artifacts/2026-05-25-sniff-inventory.txt
git commit -m "chore: inventory sniffs for PHP 8 modernization"
```

---

### Task 2..N: Per-file modernization

For each file in the inventory, create one task that follows this exact template:

```
### Task N: Modernize <relative path>

**Files:**
- Modify: <path>
- Test:   <matching Tests/.../UnitTest.php and .inc fixtures, if any>

- [ ] Step 1: Read the file end-to-end.
- [ ] Step 2: Apply only the PHP 8 idioms identified in the inventory. No drive-by rewrites.
- [ ] Step 3: Run `composer lint` — expect no syntax errors.
- [ ] Step 4: Run `composer tests:run -- --filter <SniffName>` — expect all related tests pass.
- [ ] Step 5: Run `composer tests:checkcs` — expect no regressions on the dogfooded sources.
- [ ] Step 6: Run `composer exec -- phpstan analyse` — expect no new errors.
- [ ] Step 7: Commit:
      git add <files>
      git commit -m "refactor(<sniff-area>): modernize <SniffName> to PHP 8"
```

> **DO NOT batch multiple sniffs into one commit.** One commit per file keeps the diff reviewable and bisectable.

---

### Final task: CHANGELOG + release

- [ ] **Step 1: Append to `CHANGELOG.md`** under a new `## [4.1.0]` heading (modernization is internal-only, additive, no API break):

```markdown
### Changed

- Internal: sniff sources modernized to PHP 8.3 idioms (typed properties, constructor promotion, readonly, match, first-class callables, `str_*` helpers). No behavioural change to any sniff.
```

- [ ] **Step 2: Full verification pass** — repeat Task 7 from the 8.3 bump plan in full.
- [ ] **Step 3: Open PR titled `refactor: modernize sniff sources to PHP 8 (4.1.0)`.**

---

## Self-review notes

- This plan deliberately stays light because the per-file work cannot be specified concretely until the inventory exists. The inventory task (Task 1) is the gate that produces the concrete task list.
- The template in Task 2..N is deliberately strict (one commit per file) to keep review fast.

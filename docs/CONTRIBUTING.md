# Contributing Workflow Guide (How We Work)

This guide explains **exactly when** to create branches, commit, open PRs, and how we decide work is **Done**.

---

## 1) What is an Issue, Branch, Commit, PR?

### Issue (Task)

A GitHub Issue represents **one unit of work** (one feature, bugfix, chore, or doc update).

* Example: “Implement login backend”
* Example: “Build hostel config UI”

### Branch (Workspace)

A Git branch is where you do the work for **one issue**.

* One issue → one branch

### Commit (Save point)

A commit is a **small checkpoint** of working progress.

* Multiple commits usually exist inside one issue branch.

### PR (Pull Request)

A PR is how we propose merging our branch into `develop`.

* PR is where review happens.
* PR is how we keep `develop` stable.

---

## 2) Core rules (must follow)

* **Never push directly to `main`**.
* Do not push directly to `develop` (use PR).
* **Every task = one GitHub issue.**
* **Every issue = one branch.**
* **Every branch = one PR.**
* Every PR must be reviewed by the other teammate before merging.

---

## 3) Branching strategy

### Permanent branches

* `main` → always stable, demo-ready
* `develop` → integration branch for all merged work

### Working branches (create from `develop`)

Use kebab-case and keep names short.

**Formats**

* `feature/<area>-<issueNumber>-<short-desc>`
* `fix/<area>-<issueNumber>-<short-desc>`
* `chore/<area>-<issueNumber>-<short-desc>`

**Examples**

* `feature/auth-4-login-session`
* `feature/hostel-config-9-admin-ui`
* `fix/allocations-14-seat-double-booking`
* `chore/docs-3-setup-guide`

---

## 4) When to create a branch

Create a new branch when:

* You start working on a **new issue**
* You are fixing a bug tied to an issue
* You are doing a refactor/chore that deserves review

Do not reuse a branch for multiple issues.

---

## 5) When to commit

Commit when:

* You finished a meaningful step that **runs** (or at least doesn’t break the app)
* You completed a sub-task that can be reviewed on its own
* You want a safe checkpoint before changing direction

Avoid:

* One giant commit for the whole issue
* Committing broken code that blocks others

**Good commit rhythm**: ~3–10 commits per issue branch.

---

## 6) Commit message format (required)

**Format**
`type(area): short message (#issueNumber)`

**Allowed types**

* `feat` → new feature
* `fix` → bug fix
* `chore` → tooling/config/cleanup
* `docs` → documentation
* `refactor` → restructure without behavior change
* `test` → tests

**Examples**

* `feat(auth): add login endpoint (#4)`
* `feat(ui): build login form and validation (#6)`
* `fix(allocations): prevent seat double booking (#14)`
* `docs(setup): add xampp setup steps (#3)`
* `chore(db): add indexes for applications (#10)`

**Rules**

* Use present tense: “add”, “fix”, “update”
* Keep it short
* Always include `(#issueNumber)`

---

## 7) Pull Requests (PRs)

### When to open a PR

Open a PR when:

* The issue work is complete
* You ran basic manual tests
* Your branch is updated with latest `develop`

### PR title format (required)

`[AREA] Title (Fixes #issueNumber)`

**Examples**

* `[AUTH] Login + sessions (Fixes #4)`
* `[HOSTEL-CONFIG] Admin config screens (Fixes #9)`
* `[ALLOCATIONS] Seat assignment flow (Fixes #14)`

### PR description (required)

Include these sections:

**What changed**

* …

**How to test**

1. …
2. …
3. …

**Screenshots (if UI)**

* Add page screenshots or short clip

**Risk / Notes**

* Edge cases, DB changes, migrations, etc.

**Fixes**

* Fixes #ISSUE_NUMBER

---

## 8) Definition of Done (DoD)

### What DoD means

**DoD = Definition of Done**.
It is a shared checklist that answers:

> “Is this issue truly finished and safe to merge?”

DoD prevents:

* Half-done features
* Missing validation/security
* UI without loading/error states
* Forgotten schema updates
* Demo-breaking bugs

### Where to write DoD

* **One-time (full checklist):** keep it in this document (`docs/CONTRIBUTING.md`).
* **In every Issue:** just reference it:

  * “DoD: Must meet project DoD (see `docs/CONTRIBUTING.md`) + Acceptance Criteria.”
* **In every PR:** include a short checklist (best enforcement point).

### When to use DoD

* **Before opening PR:** author checks DoD items
* **During review:** reviewer checks the same DoD items
* **Before merging:** confirm critical DoD items are met

### DoD Checklist

#### General (always)

* [ ] Issue Acceptance Criteria met
* [ ] Branch and commits follow naming rules
* [ ] PR opened to `develop` with `Fixes #ISSUE_NUMBER`
* [ ] PR reviewed and approved by teammate
* [ ] No debug leftovers (`var_dump`, `die`, test prints)
* [ ] No secrets committed (no real passwords/tokens; no `config.php`)
* [ ] Basic manual testing completed

#### Backend/API (if touched)

* [ ] Prepared statements used (PDO)
* [ ] Server-side validation exists
* [ ] Consistent JSON response shape used
* [ ] Role/permission checks enforced server-side

#### Database (if touched)

* [ ] `database/schema.sql` updated (and/or change documented)
* [ ] Indexes/constraints added where needed
* [ ] Seed/demo data updated if required

#### Frontend/UI (if touched)

* [ ] UI matches `/docs/UI_GUIDE.md`
* [ ] Loading, empty, and error states exist
* [ ] Forms show clear validation messages
* [ ] Basic responsive check (mobile + desktop)

#### Docs (if needed)

* [ ] Setup/demo steps updated if behavior changed

---

## 9) Keeping your branch updated (avoid conflicts)

### Option A: merge `develop` into your branch

```bash
git checkout develop
git pull origin develop
git checkout your-branch
git merge develop
```

### Option B: rebase your branch on `develop`

```bash
git checkout develop
git pull origin develop
git checkout your-branch
git rebase develop
```

If you rebased after pushing:

```bash
git push --force-with-lease
```

Only do this if you understand it and you tell the teammate.

---

## 10) Merging PRs

Recommended: **Squash and merge**

* Keeps `develop` clean (1 commit per PR)
* Use PR title as squash message

Rule: Don’t merge your own PR without at least 1 approval.

---

## 11) Conflict handling

If conflicts happen:

1. Tell teammate which files conflict.
2. The person currently responsible for that issue resolves conflicts.
3. Retest the flow and push.

Avoid conflicts:

* Don’t edit the same layout/core files in parallel.
* Agree on ownership before touching shared files.

---

## 12) Daily habit (2-week deadline)

* Pick an issue → create branch → work → commit often → open PR early
* If stuck for 30–60 minutes, ask teammate
* Keep PRs small and frequent

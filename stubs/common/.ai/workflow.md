# Workflow

The full process from idea to merged feature. The main session always acts as **Tariq
(Team Leader / orchestrator)**; developers and QA run as parallel subagents.

## 1. Sprint planning (`/sprint-plan`)

Input: a feature list or goal from the owner, a BRD in `.ai/docs/`, or the top of
`.ai/backlog.md`.

0. If planning from a BRD: Tariq resolves every ambiguity with the owner FIRST
   (answers logged in DECISIONS.md), extracts the features into `.ai/backlog.md`
   by priority, then plans the sprint from the top of the backlog.
1. Tariq reads PROJECT.md, DECISIONS.md, backlog.md, and the codebase where needed.
2. Splits the work into tasks sized for half a day or less each. Every task gets:
   - A step-by-step implementation plan precise enough that no questions are needed.
   - An exact **file list** (files it will create/modify). Two parallel tasks must
     never share a file — overlap means a dependency or a merged task.
   - Acceptance criteria and required test cases.
   - Dependencies on other tasks (by ID) and an assigned agent.
3. Backend and frontend halves of one feature are separate tasks: backend task first,
   frontend task depends on it (contract = the API/props spec written in the task body).
   When both must start together, the task body pins the contract so they can proceed
   in parallel against it.
4. Tariq writes `.ai/sprints/sprint-<N>.md` (from `.ai/templates/sprint.md`), creates one
   GitHub issue per task (`gh issue create`) titled `[Sprint <N>] <Task name>`, records
   issue numbers in the sprint file, creates the feature branches off `develop`, and
   updates BOARD.md.

### Shared-file rules (conflict prevention)

- Migrations: filename timestamps are taken at branch time; tasks creating migrations for
  the same table must be sequential (dependency), never parallel.
- Route files, `app.ts`/`app.js`, nav/menu components, `composer.json`, `package.json`:
  never edited by two parallel tasks. Either serialize via dependency or leave a
  `wiring` checklist item for Tariq to apply at merge time.
- New code goes in new files whenever reasonable — new service class, new page,
  new component — so parallel diffs stay disjoint.

## 2. Execution (`/sprint-work`)

1. Tariq reads the sprint file and picks every task whose dependencies are all `done`
   and whose agent is free (one active task per agent).
2. For each picked task: create the task branch, create the agent's worktree
   (`git worktree add .worktrees/<agent> task/...`), set the task `in_progress` on
   BOARD.md, then spawn all agents **in parallel in one batch**, each with the full
   issue body pasted into its prompt.
3. Each agent, inside its own worktree only:
   - Implements exactly the plan; touches only the declared file list.
   - Writes the required Pest (or frontend) tests and runs them until green.
   - Runs `vendor/bin/pint --dirty` before committing.
   - Commits per `.ai/conventions.md` (small, prefixed, one line).
   - Reports back: what was done, test results, and any deviation from the plan.
4. Tariq updates BOARD.md and the sprint file after every completion (status,
   commit hashes, notes), merges finished task branches into the feature branch in
   dependency order, resolves any conflicts himself, applies wiring checklist items,
   and immediately dispatches newly-unblocked tasks. Repeat until the sprint is done.

## 3. Review and PR (`/sprint-review`)

When all tasks of a feature are merged into its feature branch:

1. **QA (Noor)** reviews on the feature branch: business logic vs acceptance criteria of
   every task, edge cases, validation, authorization. Audits test coverage and writes the
   manual test script. Verdict: approve, or defect list (each defect becomes a
   `bugfix` task routed back through step 2).
2. **Tariq** does the technical review: architecture, security, N+1 queries, conventions,
   migrations safety. Writes his review notes.
3. Tariq runs the full test suite + pint, then opens ONE PR `feature/... -> develop`
   using `.ai/templates/pr.md` (`gh pr create`), including QA's manual test script and
   both reviews' notes. Closes the task issues via the PR body (`Closes #..`).
4. When CI is green and nothing is outstanding, Tariq merges (squash-off — keep the
   branch's commits: use merge commit), deletes the feature and task branches, removes
   worktrees, updates BOARD.md and PROJECT.md (new module/route docs), and logs any new
   decisions in DECISIONS.md.

## 4. Release

`develop -> main` PR by Tariq when the owner asks for a release. Same review gates.

## State discipline (session resumability)

Every state change is written to disk the moment it happens:

- BOARD.md — task status changes, who is working, timestamps.
- Sprint file — statuses, commit hashes, review verdicts.
- DECISIONS.md — every non-obvious technical decision, one line each.

A brand-new session must be able to continue the sprint with zero re-discovery, using
only these files plus `git log`/worktree inspection. Update state BEFORE starting risky
long steps, not just after finishing them.

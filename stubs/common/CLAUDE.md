# {{PROJECT_NAME}} — AI Workspace

Multi-agent Laravel project. Stack: **{{STACK_LABEL}}** (`{{STACK_KEY}}`).

## Session bootstrap (ALWAYS do this first)

Every session — new or resumed — starts by reading, in order:

1. `.ai/state/PROJECT.md` — what this project is, modules, architecture.
2. `.ai/state/BOARD.md` — live board: current sprint, who is doing what, last update per task.
3. `.ai/sprints/` — the current sprint file (highest number) for full task specs.
4. `.ai/state/DECISIONS.md` — decisions already made; never re-litigate them.

Also relevant when planning: `.ai/backlog.md` (features waiting for a sprint) and
`.ai/docs/` (BRDs/PRDs — planning input only, never a developer spec).

Do NOT re-explore the codebase from scratch if these files answer the question. They are the single source of truth and are always kept current.

### Resuming after an interruption

If BOARD.md shows a task `in_progress`:

1. Check its worktree under `.worktrees/<agent>` — run `git status` and `git log` there.
2. Committed work = done up to that point. Uncommitted diff = resume exactly from it.
3. Continue the task from where it stopped; never restart from zero.

## The team

Roster, models, and effort per agent: `.ai/team.yml`. Roles and the full process: `.ai/workflow.md`.

- **Tariq** — Team Leader: plans sprints, assigns tasks, merges, resolves conflicts, technical review, opens the single PR per feature.
- **Omar, Khalid** — Backend developers.
- **Sara, Lina** — Frontend developers.
- **Noor** — QA: business-logic review, test-case audit, manual test scripts.

The main session acts as Tariq (orchestrator). Developers and QA run as subagents, in parallel, each in their own git worktree.

## Commands

- `/sprint-plan` — plan a sprint: split work into detailed, dependency-mapped tasks; create GitHub issues; write the sprint file.
- `/sprint-work` — run all ready (unblocked) tasks in parallel via agents.
- `/sprint-review` — QA + technical review of a finished feature, then one PR.
- `/sprint-status` — summarize BOARD + sprint progress.

## Hard rules (never violate)

- Git, commits, PRs: follow `.ai/conventions.md` exactly. Commits are one short line, prefixed (`feature:`, `bugfix:`, ...), authored ONLY by the configured git user. Never add Co-Authored-By, AI attribution, emails, or any signature. Never mention AI/Claude in commits, PRs, or issues.
- Code comments: English only, few and short, only where intent is not obvious from the code.
- State files (`BOARD.md`, sprint file) are updated at every task start/finish — this is what makes sessions resumable.
- Agents never touch files outside their task's declared file list. Shared files are handled per `.ai/workflow.md`.
- Stack-specific rules: `.ai/stack.md`.

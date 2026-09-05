# Conventions

## Git identity

All commits are authored by the repository's configured git user (the human owner).
NEVER add: `Co-Authored-By`, `Generated with`, AI names, AI emails, session links,
or any attribution trailer. One-line messages only — no body.

## Commit messages

Format: `<type>: <short imperative summary>` — lowercase, ≤ 60 chars, one line.

| Type       | Use for                                    |
|------------|--------------------------------------------|
| `feature:` | New user-facing capability                  |
| `bugfix:`  | Fixing broken behavior                      |
| `refactor:`| Code change with no behavior change         |
| `test:`    | Adding or fixing tests only                 |
| `chore:`   | Tooling, deps, config, CI                   |
| `docs:`    | Documentation and .ai state files           |
| `style:`   | Formatting, naming, no logic change         |

Examples: `feature: add invoice model and migration`, `bugfix: prevent duplicate order submit`.

One commit per logical change. Commit only files belonging to the current task.

## Branches

- `main` — production. Only receives merges from `develop`.
- `develop` — integration. Only receives feature PRs merged by the Team Leader.
- `feature/s<N>-<slug>` — one per feature, branched off `develop`. Owned by the Team Leader.
- `task/s<N>-t<NN>-<slug>` — one per task, branched off its feature branch
  (or off `develop` for standalone tasks). Owned by exactly one agent.

Nobody commits directly to `main` or `develop`. Developers commit only to their own task branch.

## Worktrees (parallel work)

Each agent works in its own worktree so parallel tasks never fight over the working directory:

```
git worktree add .worktrees/<agent> <task-branch>
```

Worktrees are removed by the Team Leader after the task branch is merged
(`git worktree remove .worktrees/<agent>`). `.worktrees/` is git-ignored.

## Code style

- Laravel best practices: thin controllers, FormRequests for validation, services/actions
  for business logic, Eloquent scopes, policies for authorization, queued jobs for slow work.
- Pest for tests. Pint for formatting (run before every commit: `vendor/bin/pint --dirty`).
- Comments: English only, short, few — only for non-obvious intent. No noise comments.
- Frontend follows `.ai/stack.md` (shadcn conventions where applicable).

## Pull requests

One PR per feature, opened and merged by the Team Leader only, from the template in
`.ai/templates/pr.md`. Title: `Feature: <name> (Sprint <N>)`. Body must include:
summary of what was done, the automated test cases that were run, the manual test
script (exact steps, from opening the browser), and the Team Leader's review notes.
No AI attribution anywhere. Merge only when CI is green and QA has approved.

## Issues

One GitHub issue per task, created by the Team Leader from `.ai/templates/issue.md`.
Title: `[Sprint <N>] <Task name>`. The body must be detailed enough that the assigned
agent NEVER needs to ask a question: exact plan, exact file list, acceptance criteria,
required test cases, and dependencies (`Depends on #<issue>`).

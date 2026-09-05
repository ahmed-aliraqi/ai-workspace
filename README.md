# AI Workspace

Global installer (like `laravel/installer`) that drops a complete **AI multi-agent
development workspace** into any Laravel project: a named team of agents with fixed
models/effort, sprint-based planning with dependency-mapped tasks, a conflict-free git
flow built on worktrees, review gates (QA business review + Team Leader technical
review), one clean PR per feature, and full on-disk state so any new session resumes
instantly.

## Install (global)

Until it's published on Packagist, install from the local path or your Git remote:

```bash
# from a git remote
composer global config repositories.ai-workspace vcs git@github.com:ahmed-aliraqi/ai-workspace.git
composer global require ahmed-aliraqi/ai-workspace:dev-main

# or from the local path
composer global config repositories.ai-workspace path /home/ahmed/code/packages/ai-workspace
composer global require ahmed-aliraqi/ai-workspace:@dev
```

Make sure `~/.config/composer/vendor/bin` is on your `PATH`.

Once published on Packagist it becomes just:

```bash
composer global require ahmed-aliraqi/ai-workspace
```

## Usage

```bash
cd my-laravel-app
ai-workspace install                 # interactive stack menu
ai-workspace install --stack=inertia-react --setup   # non-interactive + install deps
ai-workspace status                  # print the current sprint board
```

Stacks: `inertia-vue`, `inertia-react`, `api`, `blade`.

`--setup` also runs the stack's dependency installation (composer/npm). Without it,
the exact commands live in the installed `.ai/stack.md`.

## What gets installed

```
CLAUDE.md                  # session bootstrap: read state first, hard rules
.ai/
  team.yml                 # roster: names, roles, models, effort
  workflow.md              # sprint planning -> parallel execution -> review -> one PR
  conventions.md           # commits (feature:/bugfix:, one line, no AI attribution),
                           # branches, worktrees, code style
  stack.md                 # playbook for the chosen stack (shadcn conventions, wiring)
  state/                   # PROJECT.md, BOARD.md, DECISIONS.md — live, resumable state
  sprints/                 # sprint-<N>.md — tasks, plans, deps, statuses (issue mirror)
  templates/               # sprint / GitHub issue / PR templates
.claude/
  agents/                  # Tariq (TL, opus/high), Omar+Khalid (BE), Sara+Lina (FE),
                           # Noor (QA) — model + effort pinned per agent
  commands/                # /sprint-plan /sprint-work /sprint-review /sprint-status
.github/workflows/ci.yml   # Pint + Pest + frontend build on PRs
```

## The workflow in one minute

1. `/sprint-plan <goal>` — Tariq splits the goal into half-day tasks, each with an exact
   plan, an exclusive file list, acceptance criteria, required tests, and dependencies;
   creates `[Sprint N] ...` GitHub issues; writes the sprint file.
2. `/sprint-work` — all unblocked tasks run **in parallel**, one agent per task, each in
   its own git worktree on its own `task/...` branch, so agents never conflict. Tariq
   merges finished branches in dependency order and dispatches newly-unblocked tasks.
3. `/sprint-review` — Noor (QA) reviews business logic vs acceptance criteria and writes
   the manual test script; Tariq reviews technically, then opens ONE PR per feature with
   test cases + manual steps, and merges when CI is green.
4. Disconnected mid-sprint? Open a new session — `CLAUDE.md` bootstraps from
   `.ai/state/` and the worktrees' git history, and continues where it stopped.

## Git flow

`main` ← `develop` ← `feature/s<N>-<slug>` ← `task/s<N>-t<NN>-<slug>`

Developers only touch their own task branch inside their own worktree. Only Tariq
merges, resolves conflicts, and opens PRs. Commits: `feature: ...`, `bugfix: ...` —
one short line, author = the repo's git user, never any AI attribution.

## Requirements

- PHP ≥ 8.2, Composer, git, Node/npm (for frontend stacks)
- [`gh` CLI](https://cli.github.com) authenticated (issues + PRs)
- Claude Code opened inside the project

## Publishing to Packagist (when ready)

Push to GitHub, tag `v1.0.0`, submit the repo on packagist.org — then the global
install works exactly like the Laravel installer.

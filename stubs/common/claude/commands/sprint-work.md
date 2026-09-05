---
description: Execute the current sprint — run all unblocked tasks in parallel via agents, merge as they finish
---

Act as **Tariq (Team Leader / orchestrator)**. Follow `.ai/workflow.md` section 2 exactly.

1. Read `.ai/state/BOARD.md` and the current sprint file. If a task is already
   `in_progress`, first inspect its worktree (`git -C .worktrees/<agent> status/log`)
   and resume it rather than restarting.
2. Select every task whose dependencies are all done and whose agent is free
   (one active task per agent).
3. For each selected task: create the task branch off its feature branch, add the
   agent's worktree (`git worktree add .worktrees/<agent> task/...`), mark it
   `in_progress` in BOARD.md and the sprint file.
4. Spawn ALL selected agents in ONE parallel batch. Each agent prompt must contain the
   complete task spec from the sprint file (plan, file list, acceptance criteria,
   required tests, contract) plus the worktree path and branch name. $ARGUMENTS
5. As each agent reports back: verify its tests pass, update BOARD.md and the sprint
   file (status, commits, notes), merge the finished task branch into the feature branch
   in dependency order, resolve conflicts yourself, apply the task's wiring checklist,
   remove the worktree, and immediately dispatch any newly-unblocked tasks (repeat from
   step 2).
6. When every task of a feature is merged, tell the owner the feature is ready for
   `/sprint-review`. When the whole sprint is done, summarize: tasks completed, commits,
   what remains.

Never let two active tasks share a file. Never commit to `develop` or `main` directly.

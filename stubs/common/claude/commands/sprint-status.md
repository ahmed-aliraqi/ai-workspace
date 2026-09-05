---
description: Show sprint progress from the state files — no codebase exploration
---

Read `.ai/state/BOARD.md` and the current sprint file in `.ai/sprints/` (plus
`gh pr list` / `gh issue list` if the repo has a remote) and give the owner a compact
status report:

- Sprint goal and overall progress (done / in progress / todo / blocked).
- Per agent: current task and its state (include worktree branch if in progress).
- Dependency situation: what is blocked and what unblocks it.
- Review/PR state per feature.
- The single most useful next command (`/sprint-work`, `/sprint-review`, or `/sprint-plan`).

Do not explore the codebase — the state files are the source of truth. If BOARD.md
contradicts git (e.g. a worktree exists for a task marked done), flag it and propose
the fix.

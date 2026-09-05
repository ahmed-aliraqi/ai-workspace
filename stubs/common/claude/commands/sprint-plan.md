---
description: Plan a sprint — decompose features into dependency-mapped tasks, create GitHub issues, write the sprint file
---

Act as **Tariq (Team Leader)**. Input: $ARGUMENTS — a goal/feature list, a BRD path
under `.ai/docs/`, or empty (then pull the top of `.ai/backlog.md`).

Follow `.ai/workflow.md` section 1 exactly:

0. If the input is a BRD: read it fully, list any ambiguities as questions for the
   owner and STOP until answered (record answers in `.ai/state/DECISIONS.md`), then
   extract its features into `.ai/backlog.md` ordered by priority before planning.
1. Read `.ai/state/PROJECT.md`, `.ai/state/BOARD.md`, `.ai/state/DECISIONS.md`,
   `.ai/backlog.md`, `.ai/stack.md`, and the relevant code.
2. Decompose into half-day tasks. For EVERY task write: a step-by-step plan executable
   with zero questions (exact class names, routes, columns, props, endpoints, validation
   rules), an exclusive file list, acceptance criteria, required test cases, dependencies,
   and the assigned agent. Backend before frontend; pin the API/props contract in the
   task body when frontend must start in parallel.
3. Verify no two parallelizable tasks share a file (including migrations on the same
   table, route files, nav components, package manifests). Overlap = add a dependency or
   leave wiring for Tariq.
4. Balance assignment across Omar, Khalid, Sara, Lina — maximize how many tasks are
   unblocked at once.
5. Write `.ai/sprints/sprint-<N>.md` from `.ai/templates/sprint.md`.
6. Create the GitHub issues: `gh issue create --title "[Sprint <N>] <Task>" --body-file ...`
   using `.ai/templates/issue.md`. Record issue numbers in the sprint file.
7. Create the feature branch(es) off `develop`. Update `BOARD.md` (current sprint,
   task table) and remove the pulled features from `.ai/backlog.md`.
8. Show the owner a compact plan summary: tasks, agents, dependency graph, expected
   parallelism. Then stop — execution starts with `/sprint-work`.

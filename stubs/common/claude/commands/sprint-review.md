---
description: Review a finished feature — QA business review + technical review, then one PR by the Team Leader
---

Act as **Tariq (Team Leader)**. Feature to review: $ARGUMENTS (default: the feature
branch whose tasks are all merged, per the sprint file).

Follow `.ai/workflow.md` section 3 exactly:

1. Spawn **noor-qa** on the feature branch with: the sprint file's acceptance criteria
   for every task in the feature, and instructions to return a verdict, defects,
   coverage gaps, and the manual test script.
2. If Noor reports defects: turn each into a `bugfix` task in the sprint file (with
   plan + file list), dispatch them like `/sprint-work`, then re-run this review.
3. Do YOUR technical review of the full feature diff (`git diff develop...feature/...`):
   architecture, security (authorization, mass assignment, injection), N+1 queries,
   migration safety, conventions compliance, dead code. Write concrete notes.
4. Run the full suite: `php artisan test`, `vendor/bin/pint --test`, frontend build if
   applicable. Everything must be green.
5. Open ONE PR `feature/... -> develop` with `gh pr create`, body from
   `.ai/templates/pr.md`: summary, changes, automated test cases, Noor's manual test
   script, QA verdict, your review notes, `Closes #<issues>`. No AI attribution anywhere.
6. When CI is green: merge (merge commit, not squash), delete merged branches, remove
   leftover worktrees, update BOARD.md, PROJECT.md (new modules), DECISIONS.md.
7. Report to the owner: PR link, what shipped, review highlights.

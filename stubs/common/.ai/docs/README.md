# Docs — BRD / PRD

Drop business requirement documents here (`.md` preferred; PDFs/others fine too).
One file per product area or feature set, e.g. `brd-invoicing.md`.

Flow: BRD in this folder → `/sprint-plan .ai/docs/<file>` → Tariq extracts features
into `.ai/backlog.md` (prioritized), then decomposes the top items into sprint tasks
with plans, file lists, and dependencies.

A BRD is never a spec for developers — developers only ever receive task specs from
the sprint file. If a BRD is ambiguous, Tariq lists the open questions for the owner
BEFORE planning, resolves them, and records the answers in `.ai/state/DECISIONS.md`.

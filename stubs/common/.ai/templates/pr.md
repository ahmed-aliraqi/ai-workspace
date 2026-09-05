<!-- PR body template. Title: Feature: <name> (Sprint <N>). Opened by the Team Leader only. -->

## Summary

<3-6 lines: what this feature does and the approach taken.>

## Changes

- <area>: <what changed>

## Automated test cases

<Every test that covers this feature — name + what it asserts. All green.>

- `it validates duplicate invoice numbers` — rejects a second invoice with the same number
- ...

## Manual test script

<Exact steps a human follows, from the browser. Written by QA (Noor).>

1. Run `composer run dev`, open http://localhost:8000
2. Log in as ...
3. Go to ... and click ...
4. Expect: ...

## QA review (business logic)

<Noor's verdict + notes.>

## Technical review

<Tariq's notes: architecture, security, performance, anything future work should know.>

Closes #<issue>, #<issue>

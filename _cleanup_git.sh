#!/usr/bin/env bash
set -e
cd "D:/Program Marzuki/Projek Framework/SEOFAST" || exit 1

echo '=== STATUS ==='
git status --short

echo
echo '=== REMOVE TEMP SCRIPT FROM REPO ==='
git rm --cached _gitpush.sh >/dev/null 2>&1 || true
rm -f _gitpush.sh

echo
echo '=== COMMIT CLEANUP ==='
git add -A
git commit -m "chore: remove temporary push helper script" && echo 'COMMIT OK'

echo
echo '=== PUSH ==='
git push origin master && echo 'PUSH OK'

echo
echo '=== FINAL STATUS ==='
git status

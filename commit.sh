#!/bin/bash
set -e
git checkout -b feat/add-ccmart-folder-final-5
rm -rf ccmart
mkdir ccmart
touch ccmart/.gitkeep
git add ccmart
git commit -m "feat: Add empty ccmart directory"
git status
